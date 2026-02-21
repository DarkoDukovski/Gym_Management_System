<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $trainer_id = $_POST['trainer_id'];

    $sql = "SELECT photo_path FROM trainers WHERE trainer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $trainer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $trainer = $result->fetch_assoc();

    if ($trainer && $trainer['photo_path']) {
        $photo_path = $trainer['photo_path'];
        if (file_exists($photo_path)) {
            unlink($photo_path);
        }
    }

    $sql = "DELETE FROM trainers WHERE trainer_id = ?";
    $run = $conn->prepare($sql);
    $run->bind_param("i", $trainer_id);
    $message = "";

    if ($run->execute()) {
        $message = "Trainer deleted successfully";
    } else {
        $message = "Failed to delete trainer";
    }

    $_SESSION['success_message'] = $message;
    header('location: admin_dashboard.php');
    exit();
}
?>