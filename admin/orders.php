<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - EZReborn</title>
    <link rel="stylesheet" type="text/css" href="home.css">
</head>
<body>
    <nav class="content">
        <h1>Orders</h1>
        <button id="update-all-btn">Update All Payments</button> <!-- New button -->

        <div id="orders-container"> <!-- Container to refresh orders dynamically -->
            <?php include 'renderOrders.php'; ?> <!-- Orders HTML is rendered here -->
        </div>
    </nav>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Handle the "Update All Payments" button click
        $('#update-all-btn').click(function() {
            $.ajax({
                url: 'updatePaymentStatus.php',
                type: 'POST',
                success: function(response) {
                    $('#orders-container').html(response); // Refresh the orders table
                    alert('Payments updated successfully!');
                },
                error: function() {
                    alert('Failed to update payment statuses.');
                }
            });
        });
    </script>
</body>
</html>
