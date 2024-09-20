<?php
session_start();
include('../LogReg/database.php'); 


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_event'])) {
        $event_date = $_POST['event_date'];
        $event_title = $_POST['event_title'];
        $event_description = $_POST['event_description'];
        $hover_text = $_POST['hover_text'];
        $image_url = $_POST['image_url'];
        $form_url = $_POST['form_url']; 

        $stmt = $conn->prepare("INSERT INTO esports_events (event_date, event_title, event_description, hover_text, image_url, form_url) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $event_date, $event_title, $event_description, $hover_text, $image_url, $form_url);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['delete_event'])) {
        $event_id = $_POST['event_id'];

        $stmt = $conn->prepare("DELETE FROM esports_events WHERE id = ?");
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $stmt->close();
    }
}



$query = "SELECT * FROM esports_events ORDER BY event_date";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events</title>
    <link rel="stylesheet" href="home.css">
    <style>
        
    </style>
</head>
<body>
    >
    <nav class="sidebar">
        <ul>
            <li><a href="javascript:void(0)" onclick="loadPage('dashboard')">Dashboard</a></li>
            <li><a href="javascript:void(0)" onclick="loadPage('orders')">Orders</a></li>
            <li><a href="javascript:void(0)" onclick="loadPage('users')">User Management</a></li>
            <li><a href="javascript:void(0)" onclick="loadPage('products')">Products</a></li>
            <li><a href="javascript:void(0)" onclick="loadPage('inventory')">Inventory</a></li>
            <li><a href="javascript:void(0)" onclick="loadPage('events')">Manage Events</a></li>
        </ul>
    </nav>

                    <div id="content">
                    <h1>Manage E-Sports Events</h1>

            <form method="POST" class="event-form">
                    <div class="form-group">
                    <label for="event_date">Event Date:</label>
                    <input type="date" name="event_date" required>
                    </div>
    
                    <div class="form-group">
                    <label for="event_title">Event Title:</label>
                    <input type="text" name="event_title" required>
                    </div>
    
                    <div class="form-group">
                    <label for="event_description">Event Description (optional):</label>
                    <textarea name="event_description"></textarea>
                    </div>

                    <div class="form-group">
                    <label for="hover_text">Hover Text:</label>
                    <input type="text" name="hover_text">
                    </div>

                    <div class="form-group">
                     <label for="image_url">Image URL:</label>
                    <input type="text" name="image_url">
                    </div>

                    <div class="form-group">
                    <label for="form_url">Form URL:</label>
                    <input type="text" name="form_url" required>
                    </div>

                <button type="submit" name="add_event">Add Event</button>
            </form>



        <h2>Existing Events</h2>
        <table>
            <tr>
                <th>Date</th>
                <th>Title</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['event_date']); ?></td>
                <td><?php echo htmlspecialchars($row['event_title']); ?></td>
                <td><?php echo htmlspecialchars($row['event_description']); ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                        <button type="submit" name="delete_event">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
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
