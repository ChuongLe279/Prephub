<?php
/**
 * @var PDO $conn
 */
$token = $_GET['token'];
$token_hash = hash("sha256", $token);

global $conn;
$sql = "SELECT * FROM users
        WHERE reset_token_hash = :reset_token_hash";

$stmt = $conn->prepare($sql);
        
$stmt->execute([
    'reset_token_hash' => $token_hash,
]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user === false) {
    die("Không tìm thấy token!");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("Token quá hạn. Hãy thử lại!");
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>

    <h1>Reset Password</h1>

    <form method="post" action="process-reset-password.php">

        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <label for="password">New password</label>
        <input type="password" id="password" name="password">

        <label for="password_confirmation">Repeat password</label>
        <input type="password" id="password_confirmation"
               name="password_confirmation">

        <button>Send</button>
    </form>

</body>
</html>