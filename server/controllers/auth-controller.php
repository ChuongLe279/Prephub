<?php
// xử lí logic api cho user: login, logout, register, ...
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// cái này để auto complete cho code editor thôi
// tức là cho editor biết $conn có kiểu PDO để gợi ý method nhanh hơn
/**
 * @var PDO $conn
 */

// Helper để lấy dữ liệu đầu vào (hỗ trợ cả JSON và $_POST)
function getAuthInput() {
    $input = json_decode(file_get_contents('php://input'), true);
    return [
        'first_name' => trim($_POST['first_name'] ?? $input['first_name'] ?? ''),
        'last_name'  => trim($_POST['last_name']  ?? $input['last_name']  ?? ''),
        'email'      => trim($_POST['email']      ?? $input['email']      ?? ''),
        'password'   => $_POST['password']        ?? $input['password']   ?? ''
    ];
}

// Helper để trả về kết quả (JSON cho API, Redirect cho Form)
function authResponse($success, $message, $redirectPath, $errorSessionKey = null) {
    // Kiểm tra xem có phải yêu cầu từ API/AJAX không
    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    $isJson = isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false;

    if ($isAjax || $isJson) {
        if ($success) {
            sendJson(["success" => true, "message" => $message]);
        } else {
            sendError($message, 401);
        }
    } else {
        // Nếu là Form truyền thống thì dùng Redirect
        if (!$success && $errorSessionKey) {
            $_SESSION[$errorSessionKey] = $message;
        }
        header("Location: " . $redirectPath);
        exit();
    }
}

// Xử lý register
function handleRegister() {
    global $conn;
    $data = getAuthInput();

    if (empty($data['email']) || empty($data['password'])) {
        authResponse(false, "Vui lòng nhập đầy đủ thông tin", "/client/pages/home.php", "register_error");
        return;
    }

    $uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );

    $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);

    try {
        // kiểm tra email đã tồn tại chưa
        $stmt = $conn->prepare("SELECT email FROM users WHERE email = :email");
        $stmt->execute(['email' => $data['email']]);

        if ($stmt->fetch()) {
            authResponse(false, "Email này đã được đăng ký!", "/client/pages/home.php", "register_error");
        } else {
            $insert = $conn->prepare("INSERT INTO users (uuid, first_name, last_name, email, password) VALUES (:uuid, :first_name, :last_name, :email, :password)");
            $insert->execute([
                'uuid'       => $uuid,
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $data['email'],
                'password'   => $password_hash
            ]);
            $_SESSION['active_form'] = 'login';
            authResponse(true, "Đăng ký thành công!", "/client/pages/home.php");
        }
    } catch (PDOException $e) {
        sendError("Lỗi database: " . $e->getMessage(), 500);
    }
}

// Xử lý login
function handleLogin() {
    global $conn;
    $data = getAuthInput();

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $data['email']]);
        $user = $stmt->fetch();

        if ($user && password_verify($data['password'], $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['email'] = $user['email'];
            
            $_SESSION['is_premium'] = !empty($user['is_premium']);
            $_SESSION['has_course'] = !empty($user['has_course']);
            if (!empty($user['premium_plan'])) {
                $_SESSION['premium_plan'] = $user['premium_plan'];
                $_SESSION['premium_until'] = $user['premium_until'];
                $plans = require __DIR__ . '/../config/premiumPlan.php';
                if (isset($plans[$user['premium_plan']])) {
                    $_SESSION['premium_name'] = $plans[$user['premium_plan']]['name'];
                    $_SESSION['premium_period'] = $plans[$user['premium_plan']]['period'];
                }
            }
            
            $_SESSION['payment_history'] = [];
            $txStmt = $conn->prepare("SELECT tx_id as id, plan_id, plan_name, price, period, status, created_at FROM transaction_history WHERE user_id = :user_id ORDER BY created_at ASC");
            $txStmt->execute(['user_id' => $user['id']]);
            $history = $txStmt->fetchAll();
            if ($history) {
                $_SESSION['payment_history'] = $history;
                $_SESSION['last_payment'] = end($_SESSION['payment_history']);
            }
            
            authResponse(true, "Đăng nhập thành công", "/client/pages/home.php");
        } else {
            authResponse(false, "Email hoặc mật khẩu không chính xác", "/client/pages/home.php", "login_error");
        }
    } catch (PDOException $e) {
        sendError("Lỗi database: " . $e->getMessage(), 500);
    }
}

//Xử lý reset password
//Note cho phần security: Luôn luôn hiện "Hãy kiểm tra email của bạn" sau khi gửi cho dù email có tồn tại trong db hay không.
//Tránh việc người dùng dò ra email nào hợp lệ trong db
function handleReset(){
    global $conn;
    $data = getAuthInput();
    $token = bin2hex(random_bytes(16));
    $token_hash = hash("sha256", $token);
    //token trong 15' thôi
    $expir = date("Y-m-d H:i:s", time()+ 60 * 15); 

    
    $resetLink = 'http://localhost:3000/client/pages/reset.php?token=' . urlencode($token);
    //Tránh việc điều chỉnh token thẳng trên URL
    $resetLink = htmlspecialchars($resetLink, ENT_QUOTES, 'UTF-8');
    
    try{
        $sql = "UPDATE users
                SET reset_token_hash = :reset_token_hash,
                    reset_token_expires_at = :reset_token_expires_at
                WHERE email = :email";

        $stmt = $conn->prepare($sql);
        
        $stmt->execute([
            'reset_token_hash' => $token_hash,
            'reset_token_expires_at' => $expir,
            'email' => $data['email']
        ]);
        
        //Phần gửi mail. 
        if ($stmt->rowCount() > 0){
            $mail = require_once __DIR__ . '/../services/mailer.php';
            $mail->setFrom("noreply@prephub.com"); //Cái này vẫn hiển thị là prephub207@gmail.com do mình không setup workspace được. Mà để vậy cx không sao đâu.
            $mail->addAddress($data['email']);
            $mail->Subject = "Đặt lại mật khẩu Prephub";

            //Phần css/html của email gửi cho người dùng.
            ob_start();
            require __DIR__ . '/../../client/pages/components/reset-mail.php';
            $mail->Body = ob_get_clean();

            try{
                $mail->send();
            }catch (Exception $exception){
                echo "Không gửi được. Mail error: {$mail->ErrorInfo}";
            }
            
        }
    }catch (PDOException $e) {
        sendError("Lỗi database: " . $e->getMessage(), 500);
    }
}

// Xử lý đăng xuất
// Hàm xử lý đăng xuất t dời r. StackOverflow bảo là làm nó thành file riêng để tránh việc thực thi nhầm (accidentally executed)
