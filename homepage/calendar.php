<?php
session_start();
include('../LogReg/database.php');


$current_month = isset($_GET['month']) ? intval($_GET['month']) : date('m');
$current_year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');


if (isset($_GET['action'])) {
    if ($_GET['action'] == 'prev') {
        if ($current_month == 1) {
            $current_month = 12;
            $current_year--;
        } else {
            $current_month--;
        }
    } elseif ($_GET['action'] == 'next') {
        if ($current_month == 12) {
            $current_month = 1;
            $current_year++;
        } else {
            $current_month++;
        }
    }
}


$query = "SELECT * FROM esports_events WHERE MONTH(event_date) = $current_month AND YEAR(event_date) = $current_year ORDER BY event_date";
$result = mysqli_query($conn, $query);

$events_by_date = [];
while ($row = mysqli_fetch_assoc($result)) {
    $date = date('Y-m-d', strtotime($row['event_date'])); 
    $events_by_date[$date][] = [
        'id' => $row['id'],
        'title' => $row['event_title'],
        'description' => $row['event_description'],
        'hover_text' => $row['hover_text'],
        'image_url' => $row['image_url'],
    ];
}


$days_in_month = cal_days_in_month(CAL_GREGORIAN, $current_month, $current_year);
$first_day_of_month = strtotime("$current_year-$current_month-01");
$day_of_week = date('w', $first_day_of_month);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Calendar</title>
    <link rel="stylesheet" href="style.css">
    <style>
        #calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            margin: 20px;
        }
        .day {
            border: 1px solid #ddd;
            padding: 10px;
            min-height: 100px;
            position: relative;
        }
        .event-cell {
            position: relative;
        }
        .hover-content {
            display: none;
            position: absolute;
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            left: 0;
            top: 100%;
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
        #navigation {
            text-align: center;
            margin-bottom: 20px;
        }
        #navigation button {
            margin: 0 10px;
            padding: 10px 15px;
            background-color: #aa1345;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #navigation span {
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <header>
        <img src="img/EzR Logo.png" alt="Logo" class="logo">
        <h1>EZ reborn gears</h1>
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="calendar.php">Events</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="cart.php">Cart</a></li>
            </ul>
        </nav>
    </header>

    <div id="content">
        <h1>Event Calendar</h1>
        <div id="navigation">
            <form method="GET" style="display: inline;">
                <input type="hidden" name="month" value="<?php echo $current_month; ?>">
                <input type="hidden" name="year" value="<?php echo $current_year; ?>">
                <button type="submit" name="action" value="prev">&lt; Previous</button>
            </form>
            <span><?php echo date('F Y', strtotime("$current_year-$current_month-01")); ?></span>
            <form method="GET" style="display: inline;">
                <input type="hidden" name="month" value="<?php echo $current_month; ?>">
                <input type="hidden" name="year" value="<?php echo $current_year; ?>">
                <button type="submit" name="action" value="next">Next &gt;</button>
            </form>
        </div>
        <div id="calendar">
            <?php
            
            for ($i = 0; $i < $day_of_week; $i++) {
                echo '<div class="day"></div>';
            }

            
            for ($day = 1; $day <= $days_in_month; $day++) {
                $date = sprintf('%04d-%02d-%02d', $current_year, $current_month, $day);
                echo '<div class="day">';
                echo "<strong>$day</strong><br>";

                
                if (isset($events_by_date[$date])) {
                    foreach ($events_by_date[$date] as $event) {
                        echo '<div class="event-cell">';
                        echo htmlspecialchars($event['title']);
                        echo '<div class="hover-content">';
                        if ($event['image_url']) {
                            echo '<img src="' . htmlspecialchars($event['image_url']) . '" alt="Event Image">';
                        }
                        echo '<p>' . htmlspecialchars($event['hover_text']) . '</p>';
                        echo '<a href="eventreg.php?event_id=' . urlencode($event['id']) . '">Sign Up</a>';
                        echo '</div></div>';
                    }
                }
                echo '</div>';
            }
            ?>
        </div>
    </div>
</body>
</html>
