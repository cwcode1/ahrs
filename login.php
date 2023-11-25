
<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "Moindabi1@";
$dbname = "aztecusers";

// Start the session
session_start();

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize user inputs
function sanitize_input($input) {
    // Remove leading and trailing whitespaces
    $input = trim($input);
    // Prevent SQL injection
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    $input = htmlentities($input);
    return $input;
}

// Function to authenticate user and return user role
function authenticateUser($username, $password) {
    global $conn;

    // SQL query using a prepared statement to prevent SQL injection
    $query = "SELECT email, password_hash, role FROM users WHERE email = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($storedUsername, $storedPasswordHash, $assignedRole);
        $stmt->fetch();

        // Use password_verify to securely compare the entered password with the stored hash
        if (password_verify($password, $storedPasswordHash)) {
            // Authentication successful, return user role
            
            return $assignedRole;
        } else {
        }
    } else {
        //user does not exist
        header("Location: index.php?error=User does not exist. Please check your credentials and try again.");
        exit();
         

    }
    

    $stmt->close();
    return null; // Return null if authentication fails
}

// Get user input for email and password
$enteredEmail = sanitize_input($_POST["email"]);
$enteredPassword = sanitize_input($_POST["password"]);

// Call the authenticateUser function with user input
$userRole = authenticateUser($enteredEmail, $enteredPassword);

if ($userRole) {
    // Authentication successful
    echo "Login successful!\n";

    // Set session variables
    $_SESSION['user'] = [
        'email' => $enteredEmail,
        'role' => $userRole
    ];

    // SQL query to insert login details into the logs table
    $insertLogQuery = "INSERT INTO user_logs (logger, logintime, logouttime) VALUES (?, NOW(), NULL)";
    
    $stmt = $conn->prepare($insertLogQuery);
    $stmt->bind_param("s", $enteredEmail);
    $stmt->execute();
    $stmt->close();

    //Retrieve logid for use when logging out 
    $retrieveLogIdQuery = "SELECT logid FROM user_logs WHERE logger = ? AND logintime = (SELECT MAX(logintime) FROM user_logs WHERE logger = ?)";

    $stmtRetrieveLogId = $conn->prepare($retrieveLogIdQuery);
    $stmtRetrieveLogId->bind_param("ss", $enteredEmail, $enteredEmail);
    $stmtRetrieveLogId->execute();
    $stmtRetrieveLogId->bind_result($logid);
    $stmtRetrieveLogId->fetch();
    $stmtRetrieveLogId->close();

    // Set the logid in the session for later use
    $_SESSION['logid'] = $logid;

    // Set the session if authentication is successful
    $_SESSION['loggedin'] = true;

    // Retrieve additional user information from the database
    $queryUserInfo = "SELECT userid, role FROM users WHERE email = ?";
    $stmtUserInfo = $conn->prepare($queryUserInfo);
    $stmtUserInfo->bind_param("s", $enteredEmail);
    $stmtUserInfo->execute();
    $stmtUserInfo->bind_result($userid, $role);
    $stmtUserInfo->fetch();

    // Store user information in session variables
    $_SESSION['userid'] = $userid;
    $_SESSION['role'] = $role;

    // Close the statement
    $stmtUserInfo->close();

    //redirect to the necessary page
        // Check the user's role and display appropriate message
    switch ($userRole) {
        case 'admin':
            echo "You are logged in as Admin.";
            // Add admin-specific logic or redirection here
            header("Location: admin.html");
            break;
        case 'committee':
            echo "You are logged in as Committee.";
            // Add committee-specific logic or redirection here
            header("Location: committee.html");
            break;
        case 'researcher':
            echo "You are logged in as Researcher.";
            // Add researcher-specific logic or redirection here
            header("Location: researcher.html");
            break;
        default:
            echo "Unknown role.";
    }
    exit(); // Ensure that the script stops execution after redirection
} else {
    // Authentication failed
        header("Location: index.php?error=User does not exist in our system.");
        exit();
    
}

// Close the database connection
$conn->close();
?>
