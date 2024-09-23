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
    $discord_tag = trim($_POST['discord_tag']); // New Discord tag input

    // Validate email format
    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit();
    }

    // Prepare and execute the registration
    $stmt = $conn->prepare("INSERT INTO event_registrations (event_id, first_name, last_name, user_email, contact_no, social_media, discord_tag) VALUES (?, ?, ?, ?, ?, ?, ?)"); // Updated query
    if ($stmt) {
        $stmt->bind_param("issssss", $event_id, $first_name, $last_name, $user_email, $contact_no, $social_media, $discord_tag); // Updated binding
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
    <link rel="stylesheet" href="home.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .content {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin: 15px 0 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="url"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            margin-bottom: 15px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="url"]:focus {
            border-color: #2c3e50;
            outline: none;
        }

        button {
            background-color: #2c3e50;
            color: #fff;
            border: none;
            padding: 12px 15px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #34495e;
        }

        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 2px;
            background-color: #e74c3c;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            width: 100%;
            transition: background-color 0.3s;
        }

        .back-button:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="content">
        <h1>Register for <?php echo htmlspecialchars($event['event_title']); ?></h1>
        <form method="POST">
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" required>

            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" required>

            <label for="user_email">Email:</label>
            <input type="email" name="user_email" required>

            <label for="contact_no">Contact Number:</label>
            <input type="text" name="contact_no" required>

            <label for="social_media">Social Media URL:</label>
            <input type="url" name="social_media">

            <label for="discord_tag">Discord Tag:</label> <!-- New Discord tag field -->
            <input type="text" name="discord_tag" placeholder="Your Discord Tag (e.g., User#1234)" required>

            <button type="submit">Register</button>
        </form>
        <a href="calendar.php" class="back-button">Back</a>
    </div>
</body>
</html>
