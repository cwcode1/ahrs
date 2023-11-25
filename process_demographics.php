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

// Function to handle CSV file upload and data insertion for Patient Demographics
function handleCSVUploadDemographics($conn) {
    // Retrieve the uploaded file from the form
    $file = $_FILES["demographicsDataFile"]["tmp_name"];
    $handle = fopen($file, "r");

    // Skip the first row (headers)
    fgetcsv($handle, 1000, ",");

    // Loop through the CSV file and insert Patient Demographics into the database
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $firstname = $data[0];
        $lastname = $data[1];
        $dateofbirth = $data[2];
        $gender = $data[3];
        $age = $data[4];
        $handedness = $data[5];
        $height = $data[6];
        $weight = $data[7];
        $educationlevel = $data[8];

        // Find patientid from the patients table based on name and dateofbirth
        $patientIdQuery = "SELECT patientid FROM patients WHERE firstname = '$firstname' AND lastname = '$lastname' AND dateofbirth = '$dateofbirth'";
        $result = $conn->query($patientIdQuery);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $patientId = $row["patientid"];

            // Insert Patient Demographics into the patientdemographics table
            $sql = "INSERT INTO patientdemographics (patientid, gender, age, handedness, height, weight, educationlevel) 
                    VALUES ('$patientId', '$gender', '$age', '$handedness', '$height', '$weight', '$educationlevel')";

            if ($conn->query($sql) === TRUE) {
                echo "Patient Demographics inserted successfully for patient $firstname $lastname<br>";
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
if (isset($_POST["submitDemographicsBtn"]) && isset($_FILES["demographicsDataFile"])) {
    handleCSVUploadDemographics($conn);
}

$conn->close();
?>
