<?php
// server/controllers/score-controller.php
require_once __DIR__ . '/../db/config.php';
require_once __DIR__ . '/../models/attempt.php';

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];

try {
	if ($method === 'POST') {
		$inputJSON = file_get_contents('php://input');
		$data = json_decode($inputJSON, true);

		$test_uuid = $data['test_uuid'] ?? '';
		$answers = $data['answers'] ?? [];
		$time_spent = $data['time_spent'] ?? 0;

		if (empty($test_uuid)) {
			http_response_code(400);
			echo json_encode(['error' => 'Thiếu test_uuid để chấm điểm']);
			exit;
		}

		$user_id = $_SESSION['user_id'] ?? null;
		if (!$user_id) {
			http_response_code(401);
			echo json_encode(['error' => 'Vui lòng đăng nhập để nộp bài!']);
			exit;
		}

		$real_attempt_id = submitAndGrade($conn, $user_id, $test_uuid, $answers, $time_spent);

		if (!$real_attempt_id) {
			http_response_code(500);
			echo json_encode(['error' => 'Chấm điểm thất bại!']);
			exit;
		}

		echo json_encode([
			'status' => 'success',
			'attempt_id' => $real_attempt_id
		]);
		exit;
	} else if ($method === 'GET') {
		$attempt_id = $_GET['attempt_id'] ?? '';

		if (empty($attempt_id)) {
			http_response_code(400);
			echo json_encode(['error' => 'Thiếu attempt_id']);
			exit;
		}

		$results = getReviewDetails($conn, $attempt_id);

		echo json_encode([
			'status' => 'success',
			'data' => $results
		]);
		exit;
	}
} catch (Exception $e) {
	http_response_code(500);
	echo json_encode(['error' => 'Lỗi server: ' . $e->getMessage()]);
}