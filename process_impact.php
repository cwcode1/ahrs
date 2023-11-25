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

// Function to handle CSV file upload and data insertion for Impact data
function handleCSVUploadImpact($conn) {
    // Retrieve the uploaded file from the form
    $file = $_FILES["impactDataFile"]["tmp_name"];
    $handle = fopen($file, "r");

    // Skip the first row (headers)
    fgetcsv($handle, 1000, ",");

    // Loop through the CSV file and insert Impact data into the database
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $firstname = $data[0];
        $lastname = $data[1];
        $dateofbirth = $data[2];
        $testtype = $data[3];
        $testdate = $data[4];
        $testversion = $data[5];
        $verbalmemorycomposite = $data[6];
        $visualmemorycomposite = $data[7];
        $visualmotorcomposite = $data[8];
        $reactiontimecomposite = $data[9];
        $headache = $data[10];
        $nausea = $data[11];

        // Find patientid from the patients table based on name and dateofbirth
        $patientIdQuery = "SELECT patientid FROM patients WHERE firstname = '$firstname' AND lastname = '$lastname' AND dateofbirth = '$dateofbirth'";
        $result = $conn->query($patientIdQuery);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $patientId = $row["patientid"];

            // Insert Impact data into the impact table
            $sql = "INSERT INTO impact (patientid, testtype, testdate, testversion, verbalmemorycomposite, visualmemorycomposite, visualmotorcomposite, reactiontimecomposite, headache, nausea) 
                    VALUES ('$patientId', '$testtype', '$testdate', '$testversion', '$verbalmemorycomposite', '$visualmemorycomposite', '$visualmotorcomposite', '$reactiontimecomposite', '$headache', '$nausea')";

            if ($conn->query($sql) === TRUE) {
                echo "Impact Data inserted successfully for patient $firstname $lastname<br>";
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
if (isset($_POST["submitImpactBtn"]) && isset($_FILES["impactDataFile"])) {
    handleCSVUploadImpact($conn);
}

$conn->close();
?>
