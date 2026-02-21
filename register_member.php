<?php

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $trainer_id = isset($_POST['trainer_id']) ? intval($_POST['trainer_id']) : 0;
    $photo_path = $_POST['photo_path'];
    $valid_until = $_POST['valid_until'];


    $sql = "INSERT INTO members 
            (first_name, last_name, email, phone_number, photo_path, valid_until, trainer_id)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $run = $conn->prepare($sql);
    $run->bind_param("ssssssi", $first_name, $last_name, $email, $phone_number, $photo_path, $valid_until, $trainer_id);
    $run->execute();

    $conn->close();

    $_SESSION['success_message'] = 'Gym member successfully added';
    header('location: admin_dashboard.php');
    exit();
}
?>