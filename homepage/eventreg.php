<?php
session_start();
include('../LogReg/database.php');

// Check if event_id is provided
if (isset($_GET['event_id'])) {
    $event_id = intval($_GET['event_id']);

    // Fetch event details
    $stmt = $conn->prepare("SELECT * FROM esports_events WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $event_result = $stmt->get_result();
        $event = $event_result->fetch_assoc();
        $stmt->close();

        // Check if the event exists
        if (!$event) {
            echo "Event not found.";
            exit();
        }
    } else {
        echo "SQL Error: " . $conn->error;
        exit();
    }
} else {
    // Redirect if no event_id
    header('Location: calendar.php');
    exit();
}

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $user_email = trim($_POST['user_email']);
    $contact_no = trim($_POST['contact_no']);
    $social_media = trim($_POST['social_media']);

    // Validate email format
    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit();
    }

    // Prepare and execute the registration
    $stmt = $conn->prepare("INSERT INTO event_registrations (event_id, first_name, last_name, user_email, contact_no, social_media) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("isssss", $event_id, $first_name, $last_name, $user_email, $contact_no, $social_media);
        if ($stmt->execute()) {
            $stmt->close();
            // Redirect to success page
            header('Location: registration_success.php');
            exit();
        } else {
            echo "SQL Error: " . $stmt->error;
            exit();
        }
    } else {
        echo "SQL Error: " . $conn->error;
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register for <?php echo htmlspecialchars($event['event_title']); ?></title>
</head>
<body>
    <h1>Register for <?php echo htmlspecialchars($event['event_title']); ?></h1>
    <form method="POST">
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" required>
        <br>
        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" required>
        <br>
        <label for="user_email">Email:</label>
        <input type="email" name="user_email" required>
        <br>
        <label for="contact_no">Contact Number:</label>
        <input type="text" name="contact_no" required>
        <br>
        <label for="social_media">Social Media URL:</label>
        <input type="url" name="social_media">
        <br>
        <button type="submit">Register</button>
    </form>
</body>
</html>