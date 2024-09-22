<?php
session_start();
include('database.php');

function validate($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if (isset($_POST['login'])) {
    $email = validate($_POST['email']);
    $userpass = validate($_POST['userpass']); 

    if (empty($email)) {
        header("Location: index.php?error=Email is required");
        exit();
    } elseif (empty($userpass)) {
        header("Location: index.php?error=Password is required");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if ($row['userpass'] === $userpass) { 
            $_SESSION['email'] = $email;
            $_SESSION['id'] = $row['id'];
            $_SESSION['fname'] = $row['fname']; 
            header("Location: ../homepage/home.php");
            exit();
        } else {
            header("Location: index.php?error=Incorrect email or password");
            exit();
        }
    } else {
        header("Location: index.php?error=Incorrect email or password");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>