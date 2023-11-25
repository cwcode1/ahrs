<?php
$servername = "localhost";
$username = "root";
$password = "Moindabi1@";
$dbname = "aztecusers";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle CSV file upload and data insertion for Injury Data
function handleCSVUploadInjuryData($conn) {
    // Retrieve the uploaded file from the form
    $file = $_FILES["injuryDataFile"]["tmp_name"];
    $handle = fopen($file, "r");

    // Skip the first row (headers)
    fgetcsv($handle, 1000, ",");

    // Loop through the CSV file and insert Injury Data into the database
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $firstname = $data[0];
        $lastname = $data[1];
        $dateofbirth = $data[2];
        $type = $data[3];
        $testdate = $data[4];
        $incidentdate = $data[5];
        $asi_1 = $data[6];
        $asi_2 = $data[7];
        $asi_3 = $data[8];
        $asi_4 = $data[9];
        $asi_5 = $data[10];
        $asi_6 = $data[11];
        $asi_7 = $data[12];

        // Find patientid from the patients table based on name and dateofbirth
        $patientIdQuery = "SELECT patientid FROM patients WHERE firstname = '$firstname' AND lastname = '$lastname' AND dateofbirth = '$dateofbirth'";
        $result = $conn->query($patientIdQuery);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $patientId = $row["patientid"];

            // Insert Injury Data into the injurydata table
            $sql = "INSERT INTO injurydata (patientid, type, testdate, incidentdate, asi_1, asi_2, asi_3, asi_4, asi_5, asi_6, asi_7) 
                    VALUES ('$patientId', '$type', '$testdate', '$incidentdate', '$asi_1', '$asi_2', '$asi_3', '$asi_4', '$asi_5', '$asi_6', '$asi_7')";

            if ($conn->query($sql) === TRUE) {
                echo "Injury Data inserted successfully for patient $firstname $lastname<br>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Patient not found for $firstname $lastname and date of birth $dateofbirth<br>";
        }
    }

    fclose($handle);
}

// Check if the form is submitted and the file is uploaded
if (isset($_POST["submitInjuryBtn"]) && isset($_FILES["injuryDataFile"])) {
    handleCSVUploadInjuryData($conn);
}

$conn->close();
?>
