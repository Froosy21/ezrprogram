<?php  
session_start();
include('../LogReg/database.php');

// Fetch registrations with event details including contact info and social media
$query = "SELECT r.*, e.event_title, e.event_date 
          FROM event_registrations r 
          JOIN esports_events e 
          ON r.event_id = e.id 
          ORDER BY e.event_date, r.first_name"; 
$result = mysqli_query($conn, $query);

// Initialize array to organize events and users
$events = [];

// Organize data by event
while ($row = mysqli_fetch_assoc($result)) {
    $event_date = date("F j, Y", strtotime($row['event_date'])); 
    $events[$row['event_title'] . " (" . $event_date . ")"][] = [
        'user_id'     => $row['id'], 
        'first_name'  => $row['first_name'],
        'last_name'   => $row['last_name'],
        'user_email'  => $row['user_email'],
        'contact_no'  => $row['contact_no'],
        'social_media'=> $row['social_media'],
        'discord_tag' => $row['discord_tag'] 
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
        body {
            background-color: #f9f9f9; /* Light background */
            color: #333; /* Text color */
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .sidebar {
            background-color: #aa1345; /* Dark sidebar */
            color: white;
            width: 250px;
            height: 100vh;
            position: fixed;
            padding: 15px;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
        }

        #content {
            margin-left: 270px; /* Space for the sidebar */
            padding: 20px; /* Padding for the content */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            margin-left: 20px; /* Shift table to the right */
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #34495e; /* Darker header background */
            color: white; /* White text for headers */
        }

        .remove-btn {
            background-color: #c21212; /* Red remove button */
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .remove-btn:hover {
            background-color: #a00; /* Darker red on hover */
        }
    </style>
</head>
<body>
    <nav class="sidebar">
        <ul>
            <li><a href="javascript:void(0)" onclick="loadPage('dashboard')">Dashboard</a></li>
            <li><a href="javascript:void(0)" onclick="loadPage('orders')">Orders</a></li>
            <li><a href="javascript:void(0)" onclick="loadPage('users')">User Management</a></li>
            <li><a href="javascript:void(0)" onclick="loadPage('admin_events')">Events</a></li>
            <li><a href="javascript:void(0)" onclick="loadPage('products')">Products</a></li>
            <li><a href="javascript:void(0)" onclick="loadPage('inventory')">Inventory</a></li>
            <li><a href="javascript:void(0)" onclick="loadPage('admin_reg')">Event Registration</a></li>
        </ul>
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
        function loadPage(page) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', page + '.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById('content').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }
    </script>
</body>
</html>