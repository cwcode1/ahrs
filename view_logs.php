<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "Moindabi1@";
$dbname = "aztecusers";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch logs from the user_logs table
$query = "SELECT * FROM `user_logs`";
$result = $conn->query($query);

// Display the results
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Logs</title>
    <!-- Add any additional styles or link to a CSS file if needed -->
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .container {
            margin-top: 50px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .logout-btn {
            background-color: #00FF00; /* Green color */
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            position: absolute;
            top: 10px; /* Adjust top position as needed */
            right: 10px; /* Adjust right position as needed */
        }
    </style>
    <script src="common.js"></script>
</head>
<body>
    <div class="container">
        
        <h2>User Logs</h2>

        <table>
            <thead>
                <tr>
                    <th>Log ID</th>
                    <th>Logger</th>
                    <th>Login Time</th>
                    <th>Logout Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['logid']}</td>";
                    echo "<td>{$row['logger']}</td>";
                    echo "<td>{$row['logintime']}</td>";
                    echo "<td>{$row['logouttime']}</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <!-- Logout button -->
        <form method="post" action="logout.php" id="logoutForm">
            <button class="logout-btn" type="button" onclick="confirmLogout()">Logout</button>
        </form>
    </div>
</body>
</html>
<?php
// Close the database connection
$conn->close();
?>
