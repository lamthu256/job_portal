<?php
require_once '../db_connect.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$application_id = $data['application_id'] ?? '';
$status = $data['status'] ?? '';
$title = $data['title'] ?? '';
$companyName = $data['company_name'] ?? '';

date_default_timezone_set('Asia/Ho_Chi_Minh');
$time = date('Y-m-d H:i:s');

if (!$application_id || !$status || !$title || !$companyName) {
    echo json_encode(['success' => false, 'message' => 'Missing fields']);
    exit;
}

$field = strtolower($status) . '_at';
$stmt = $conn->prepare("UPDATE applications SET status = ?, $field = ? WHERE id = ?");
$stmt->bind_param(
    "ssi",
    $status,
    $time,
    $application_id
);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Application updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update application']);
}
