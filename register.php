<?php
// 1. Database Configuration
$server = "localhost";
$username = "root";
$password = "";
$dbname = "resort booking"; // Ensure this matches your phpMyAdmin exactly

// 2. Create Connection
$con = mysqli_connect($server, $username, $password, $dbname);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// 3. Check if form was actually submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Sanitize input to prevent basic SQL injection
    $name     = mysqli_real_escape_string($con, $_POST['name']);
    $email    = mysqli_real_escape_string($con, $_POST['email']);
    $phone    = mysqli_real_escape_string($con, $_POST['phone']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // 4. SQL Query (Note the backticks for column names with spaces)
    $query = "INSERT INTO `register` (`Name`, `Email`, `Phone number`, `Password`) 
              VALUES ('$name', '$email', '$phone', '$password')";

    if (mysqli_query($con, $query)) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($con);
    }
} else {
    echo "Please submit the form first.";
}

mysqli_close($con);
?>