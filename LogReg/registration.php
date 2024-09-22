<?php
session_start();
include('database.php');

function validate($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if (isset($_POST['register'])) {
    $fname = validate($_POST['fname']);
    $lname = validate($_POST['lname']);
    $phonenum = validate($_POST['number']);
    $email = validate($_POST['email']);
    $userpass = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];

    if (empty($fname)) {
        header("Location: registration.php?error=First name is required");
        exit();
    } elseif (empty($lname)) {
        header("Location: registration.php?error=Last name is required");
        exit();
    } elseif (empty($phonenum)) {
        header("Location: registration.php?error=Phone number is required");
        exit();
    } elseif (empty($email)) {
        header("Location: registration.php?error=Email is required");
        exit();
    } elseif (empty($userpass)) {
        header("Location: registration.php?error=Password is required");
        exit();
    } elseif ($userpass != $confirmpassword) {
        header("Location: registration.php?error=Passwords do not match");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $stmt = $conn->prepare("INSERT INTO users (fname, lname, phonenum, email, userpass) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $fname, $lname, $phonenum, $email, $userpass);
        $stmt->execute();

        $_SESSION['email'] = $email;
        $_SESSION['id'] = $conn->insert_id;
        $_SESSION['fname'] = $fname;

        header("Location: registration.php?success=Registered successfully!");
        exit();
    } else {
        header("Location: registration.php?error=Email already exists");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <form action="registration.php" method="post" autocomplete="off">
        <h1>REGISTER</h1>
        <?php if(isset($_GET['error'])) { ?>
            <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } elseif(isset($_GET['success'])) { ?>
            <p class="success"><?php echo $_GET['success']; ?></p>
            <button><a href="index.php">Continue to Login</a></button>
        <?php } ?>
        <label for="fname">First Name</label>
        <input type="text" name="fname" id="fname" placeholder="Enter Your First Name" required><br>
        <label for="lname">Last Name</label>
        <input type="text" name="lname" id="lname" placeholder="Enter Your Last Name" required><br>
        <label for="number">Phone Number</label>
        <input type="text" name="number" id="phonenum" maxlength="11" placeholder="Enter Your Phone Number" required><br>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="Enter Your Email" required><br>
        <label for="password">Password</label>
        <input type="password" name="password" id="userpass" maxlength="16" placeholder="Enter Your Password" required><br>
        <label for="confirmpassword">Confirm Password</label>
        <input type="password" name="confirmpassword" id="confirmpassword" maxlength="16" placeholder="Please Confirm Your Password" required><br>
        <button type="submit" name="register">Register</button>
        <a href="index.php">Already have a registered account?</a>
    </form>
</body>
</html>
