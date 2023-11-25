<?php
// Start or resume session
session_start();

// Retrieve user email from the session or any other source
$userEmail = isset($_SESSION['user']) ? $_SESSION['user'] : '';
$logid = isset($_SESSION['logid']) ? $_SESSION['logid'] : '';

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Create connection (assuming $conn is already defined in your script)
$servername = "localhost";
$username = "root";
$password = "Moindabi1@";
$dbname = "aztecusers";

$conn = new mysqli($servername, $username, $password, $dbname);

// Update the logout time for the corresponding logid
$updateLogoutTimeQuery = "UPDATE user_logs SET logouttime = NOW() WHERE logid = ?";
$stmtUpdateLogoutTime = $conn->prepare($updateLogoutTimeQuery);
$stmtUpdateLogoutTime->bind_param("i", $logid); // Corrected this line
$stmtUpdateLogoutTime->execute();
$stmtUpdateLogoutTime->close();

// Close the database connection
$conn->close();

// Redirect to the login page
header("Location: login.html");
exit();
?>
