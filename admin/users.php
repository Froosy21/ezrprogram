<?php
session_start();
include('../LogReg/database.php');

$sql = "SELECT id, fname, lname, phonenum, email, userpass FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1 class="content">User Management</h1>
    <table class="content">
        <tr class= "content">
            <th>Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Password</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $name = $row["fname"] . " " . $row["lname"];
                echo "<tr>
                        <td>" . htmlspecialchars($name) . "</td>
                        <td>" . htmlspecialchars($row["email"]) . "</td>
                        <td>" . htmlspecialchars($row["phonenum"]) . "</td>
                        <td>" . htmlspecialchars($row["userpass"]) . "</td>
                        <td>
                            <a href='updateuser.php?id=" . $row['id'] . "'>Update</a>
                            <form action='deleteuser.php' method='POST' style='display:inline;'>
                                <input type='hidden' name='id' value='" . $row['id'] . "'>
                                <input type='submit' value='Delete' onclick='return confirm(\"Are you sure you want to delete this user?\");'>
                            </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No users found</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>