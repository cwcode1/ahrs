<?php
$servername = "localhost";
$username = "root";
$password = "Moindabi1@";
$dbname = "aztecusers";

// Function to establish a database connection
function connectToDatabase() {
    global $servername, $username, $password, $dbname;
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Function to display a data preview
function displayDataPreview($file) {
    echo "<h3>Data Preview:</h3>";
    echo "<table border='1'>";
    echo "<tr><th>First Name</th><th>Last Name</th><th>Date of Birth</th><th>Referral Source</th><th>Previous Diagnosis</th><th>Previous Testing</th><th>Testing Needed</th><th>Testing Performed</th><th>Final Diagnosis</th></tr>";

    $handle = fopen($file, "r");
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        echo "<tr>";
        foreach ($data as $value) {
            echo "<td>$value</td>";
        }
        echo "</tr>";
    }

    echo "</table>";
    fclose($handle);

    // Prompt user for confirmation before insertion
    echo "<br><form method='post' action=''>";
    echo "<input type='submit' name='confirmInsert' value='Confirm Insertion'>";
    echo "</form>";
}

// Function to handle CSV file upload and data insertion for ADHD data
function handleCSVUploadADHD($conn, $file) {
    // Skip the first row (headers)
    fgetcsv($file, 1000, ",");

    while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
        $firstname = $data[0];
        $lastname = $data[1];
        $dateofbirth = $data[2];
        $referralSource = $data[3];
        $previousDiagnosis = $data[4];
        $previousTesting = $data[5];
        $testingNeeded = $data[6];
        $testingPerformed = $data[7];
        $finalDiagnosis = $data[8];

        // Find patientid from the patients table based on name and dateofbirth
        $patientIdQuery = "SELECT patientid FROM patients WHERE firstname = '$firstname' AND lastname = '$lastname' AND dateofbirth = '$dateofbirth'";
        $result = $conn->query($patientIdQuery);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $patientId = $row["patientid"];

            // Insert ADHD data into the adhddata table
            $sql = "INSERT INTO adhddata (patientid, referralsource, previousdiagnosis, previoustesting, testingneeded, testingperformed, finaldiagnosis) 
                    VALUES ('$patientId', '$referralSource', '$previousDiagnosis', '$previousTesting', '$testingNeeded', '$testingPerformed', '$finalDiagnosis')";

            if ($conn->query($sql) === TRUE) {
                echo "ADHD Data inserted successfully for patient $firstname $lastname<br>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Patient not found for $firstname $lastname and date of birth $dateofbirth<br>";
        }
    }
}

// Check if the form is submitted and the file is uploaded
if (isset($_POST["submitADHDBtn"]) && isset($_FILES["adhdDataFile"])) {
    $file = $_FILES["adhdDataFile"]["tmp_name"];
    $conn = connectToDatabase();
    
    // Display data preview
    displayDataPreview($file);

    // Handle CSV upload and data insertion
    if (isset($_POST["confirmInsert"])) {
        handleCSVUploadADHD($conn, $file);
    }

    $conn->close();
}
?>
