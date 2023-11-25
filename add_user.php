<?php
// Function to clean user email
function cleanUserEmail($email) {
    do {
        $email = trim($email);

        if (empty($email)) {
            echo "Email cannot be blank.\n";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format. Missing @ symbol.\n";
        }
    } while (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL));

    return $email;
}

// Function to clean user password
function cleanUserPassword($password) {
    do {
        $password = trim($password);

        if (strlen($password) < 6) {
            echo "Password must contain at least 6 characters.\n";
        } elseif (strlen($password) > 14) {
            echo "Password must not exceed 14 characters.\n";
        } elseif (!preg_match("/[a-zA-Z]+/", $password)) {
            echo "Password must contain at least one alphabet character.\n";
        } elseif (!preg_match("/\d+/", $password)) {
            echo "Password must contain at least one numerical character.\n";
        }
    } while (strlen($password) < 6 || strlen($password) > 14 || !preg_match("/[a-zA-Z]+/", $password) || !preg_match("/\d+/", $password));

    return $password;
}


// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "Moindabi1@";
$dbname = "aztecusers";

// Get user input from HTML form
$firstName = $_POST["firstname"];
$lastName = $_POST["lastname"];
$dateOfBirth = $_POST["dateofbirth"];
$sex = $_POST["sex"];
$role = $_POST["role"]; 
$userEmail = $_POST["email"];
//clean user email
$email = cleanUserEmail($userEmail);
$user_password = cleanUserPassword($_POST["password"]);
//clean user password
$password_hash = password_hash($user_password, PASSWORD_DEFAULT);  // Hash the password

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//start check
// Check if the email already exists
$checkQuery = "SELECT * FROM `users` WHERE `email` = ?";
$checkStmt = $conn->prepare($checkQuery);
$checkStmt->bind_param("s", $email);

// Set the email parameter
$email_to_insert = $email; 

$checkStmt->execute();

// Store the result so we can check if the email already exists
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    // Email already exists, do not proceed with registration
    echo "Email already exists in the database. Please choose a different email.";
}
//end check
else{
    try {
        // SQL query using a prepared statement to prevent SQL injection
        
        $query = "INSERT INTO `users` (`userid`, `firstname`, `lastname`, `dateofbirth`, `sex`, `role`, `email`, `password_hash`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isssssss", $userid, $firstName, $lastName, $dateOfBirth, $sex, $role, $email, $password_hash);
    
        // Assuming `userid` is an auto-increment field
        $userid = 0;
    
        $stmt->execute();
        
        echo "User registered successfully";
    
        $stmt->close();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        // Close the database connection
        $conn->close();
    }
}
?>
