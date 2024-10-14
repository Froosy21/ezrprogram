<?php  
session_start();
include('../LogReg/database.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_event'])) {
   
    $event_date = $_POST['event_date'];
    $event_title = $_POST['event_title'];
    $event_description = $_POST['event_description'];
    $hover_text = $_POST['hover_text'];

    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        
        $target_dir = "../uploads/";
        $image_name = basename($_FILES['image']['name']);
        $target_file = $target_dir . $image_name;

       
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            
            $stmt = $conn->prepare("INSERT INTO esports_events (event_date, event_title, event_description, hover_text, image_url) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $event_date, $event_title, $event_description, $hover_text, $target_file);

            
            if ($stmt->execute()) {
                echo "Event added successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Failed to upload image.";
        }
    } else {
        echo "Please upload a valid image file.";
    }
}

// Ma baton sang events
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
        <h1>Manage E-Sports Events</h1>

        <!-- Form pakadto add event -->
        <form method="POST" enctype="multipart/form-data" class="event-form">
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
                <label for="image">Upload Image:</label>
                <input type="file" name="image" accept="image/*" required>
            </div>

            <button type="submit" name="add_event">Add Event</button>
        </form>

        
        <h2>Existing Events</h2>
        <table>
            <tr>
                <th>Date</th>
                <th>Title</th>
                <th>Description</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['event_date']); ?></td>
                <td><?php echo htmlspecialchars($row['event_title']); ?></td>
                <td><?php echo htmlspecialchars($row['event_description']); ?></td>
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