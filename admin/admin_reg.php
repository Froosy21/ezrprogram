<?php  
session_start();
include('../LogReg/database.php');

// Fetch registrations with event details including contact info and social media
$query = "SELECT r.*, e.event_title, e.event_date 
          FROM event_registrations r 
          JOIN esports_events e 
          ON r.event_id = e.id 
          ORDER BY e.event_date, r.first_name"; // Updated to use 'first_name' instead of 'user_name'
$result = mysqli_query($conn, $query);

// Initialize array to organize events and users
$events = [];

// Organize data by event
while ($row = mysqli_fetch_assoc($result)) {
    $event_date = date("F j, Y", strtotime($row['event_date'])); // Format event date
    $events[$row['event_title'] . " (" . $event_date . ")"][] = [
        'user_id'     => $row['id'], // Assuming 'id' is the user ID column
        'first_name'  => $row['first_name'],
        'last_name'   => $row['last_name'],
        'user_email'  => $row['user_email'],
        'contact_no'  => $row['contact_no'],
        'social_media'=> $row['social_media'],
        'discord_tag' => $row['discord_tag'] // Add Discord tag to user data
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Users</title>
    <link rel="stylesheet" href="home.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <nav class="sidebar">
        <!-- Your existing sidebar -->
    </nav>

    <div id="content">
        <h1>Registered Users for Events</h1>

        <?php foreach ($events as $event_title => $users): ?>
            <h2><?php echo htmlspecialchars($event_title); ?></h2>
            <table>
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Contact No.</th>
                        <th>Social Media</th>
                        <th>Discord Tag</th> <!-- New column for Discord tag -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['user_email']); ?></td>
                            <td><?php echo htmlspecialchars($user['contact_no']); ?></td>
                            <td><a href="<?php echo htmlspecialchars($user['social_media']); ?>" target="_blank">Social Media</a></td>
                            <td><?php echo htmlspecialchars($user['discord_tag']); ?></td> <!-- Display Discord tag -->
                            <td>
                                <!-- Remove Data Button -->
                                <form action="remove_user.php" method="POST" onsubmit="return confirm('Are you sure you want to remove this user?');">
                                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['user_id']); ?>">
                                    <button type="submit" class="remove-btn">Remove Data</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    </div>

    <script>
        // Your existing script
    </script>
</body>
</html>
