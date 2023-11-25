<?php
// Start the session
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['user'])) {
    // Redirect to the login page if not logged in
    header("Location: login.html");
    exit();
}

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

// Default values
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
$filterColumn = isset($_GET['filter_column']) ? $_GET['filter_column'] : '';
$filterValue = isset($_GET['filter_value']) ? $_GET['filter_value'] : '';

// Construct SQL query based on filter and limit
$query = "SELECT * FROM `users`";
if (!empty($filterColumn) && !empty($filterValue)) {
    $query .= " WHERE `$filterColumn` = '$filterValue'";
}
$query .= " LIMIT $limit";

// Execute the query
$result = $conn->query($query);

// Display the results
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users</title>
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
        form {
            margin-top: 20px;
        }
        select {
            padding: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }
        .logout-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 10px;
            cursor: pointer;
        }
    </style>
     <script src="common.js"></script>
</head>
<body>
    <div class="container">
        <!-- Include the logout button -->
        <?php include('logout_button.php'); ?>

        <h2>Current AHRS Users</h2>
        <form action="view_users.php" method="get">
            <label for="limit">Show:</label>
            <select name="limit">
                <option value="10" <?php if ($limit == 10) echo 'selected'; ?>>10</option>
                <option value="20" <?php if ($limit == 20) echo 'selected'; ?>>20</option>
                <option value="30" <?php if ($limit == 30) echo 'selected'; ?>>30</option>
                <option value="40" <?php if ($limit == 40) echo 'selected'; ?>>40</option>
                <option value="50" <?php if ($limit == 50) echo 'selected'; ?>>50</option>
            </select>

            <label for="filter_column">Filter by:</label>
            <select name="filter_column">
                <option value="firstname" <?php if ($filterColumn == 'firstname') echo 'selected'; ?>>First Name</option>
                <option value="userid" <?php if ($filterColumn == 'userid') echo 'selected'; ?>>User ID</option>
                <option value="role" <?php if ($filterColumn == 'role') echo 'selected'; ?>>Role</option>
            </select>

            <label for="filter_value">Filter Value:</label>
            <input type="text" name="filter_value" value="<?php echo $filterValue; ?>">

            <button type="submit">Apply Filters</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Date of Birth</th>
                    <th>Sex</th>
                    <th>Role</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['userid']}</td>";
                    echo "<td>{$row['firstname']}</td>";
                    echo "<td>{$row['lastname']}</td>";
                    echo "<td>{$row['dateofbirth']}</td>";
                    echo "<td>{$row['sex']}</td>";
                    echo "<td>{$row['role']}</td>";
                    echo "<td>{$row['email']}</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
