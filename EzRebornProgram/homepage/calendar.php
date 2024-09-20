<?php
session_start();
include('../LogReg/database.php'); 


$query = "SELECT * FROM esports_events ORDER BY event_date";
$result = mysqli_query($conn, $query);


$events_by_date = [];
while ($row = mysqli_fetch_assoc($result)) {
    $date = $row['event_date'];
    $events_by_date[$date][] = [
        'title' => $row['event_title'],
        'description' => $row['event_description'],
        'hover_text' => $row['hover_text'],
        'image_url' => $row['image_url'],
        'form_url' => $row['form_url'] 
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Calendar</title>
    <link rel="stylesheet" href="style.css">
    <style>
        #content {
            margin-left: 10px; /*para magpa left sa sidebar*/
            padding: 15px;
            width: calc(100% - 260px); /* Adjust width para hindi mag overlap sa sidebar */
            background-image: url('img/calendar-bg.jpg');
            background-size: cover;
            background-position: center;
            color: black;
        }
        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.8);
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f4f4f4;
        }
        .event-cell {
            position: relative;
            display: inline-block;
            margin-right: 10px;
            padding: 5px;
        }
        .hover-content {
            display: none;
            position: absolute;
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }
        .event-cell:hover .hover-content {
            display: block;
        }
        .hover-content img {
            max-width: 200px;
            height: auto;
            display: block;
            margin-bottom: 10px;
        }
        .hover-content a {
            display: inline-block;
            margin-top: 10px;
            padding: 5px 10px;
            background-color: #aa1345;
            color: #fff;
            text-decoration: none;
            border-radius: 3px;
        }
        .hover-content a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <!-- Sidebar Navigation -->
    <header>
        <img src="img/EzR Logo.png" alt="Logo" class="logo">
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="schedule.php">Events</a></li>
                <li class="current"><a href="about.php">About Us</a></li>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="../LogReg/logout.php">Log Out</a></li>
                <li><a href="calendar.php">Calendar</a></li>
            </ul>
        </nav>
    </header>

    <div id="content">
        <h1>Upcoming Events</h1>

        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Events</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events_by_date as $date => $events): ?>
                <tr>
                    <td><?php echo htmlspecialchars($date); ?></td>
                    <td>
                        <?php foreach ($events as $event): ?>
                        <div class="event-cell">
                            <strong><?php echo htmlspecialchars($event['title']); ?></strong>
                            <div class="hover-content">
                                <?php if ($event['image_url']): ?>
                                <img src="<?php echo htmlspecialchars($event['image_url']); ?>" alt="Event Image">
                                <?php endif; ?>
                                <p><?php echo htmlspecialchars($event['hover_text']); ?></p>
                                <?php if ($event['form_url']): ?>
                                <a href="<?php echo htmlspecialchars($event['form_url']); ?>" target="_blank">Sign Up</a> <!-- Form URL link -->
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
