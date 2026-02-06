<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['status'=>'error','message'=>'Not logged in']);
    exit;
}
include 'db.php';
$data = json_decode(file_get_contents('php://input'), true);
if (!$data || empty($data['title']) || empty($data['fields'])) {
    echo json_encode(['status'=>'error','message'=>'Invalid input']);
    exit;
}
$title = $conn->real_escape_string($data['title']);
$fields = json_encode($data['fields']);
$user_id = $_SESSION['user_id'];
$form_link = bin2hex(random_bytes(5));
$stmt = $conn->prepare("INSERT INTO forms (user_id,title,form_fields,form_link) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $user_id, $title, $fields, $form_link);
if ($stmt->execute()) {
    echo json_encode(['status'=>'success','form_link'=>$form_link]);
} else {
    echo json_encode(['status'=>'error','message'=>$stmt->error]);
}
$stmt->close();