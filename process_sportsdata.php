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

// Function to handle CSV file upload and data insertion for Sports Data
function handleCSVUploadSportsData($conn) {
    // Retrieve the uploaded file from the form
    $file = $_FILES["sportsDataFile"]["tmp_name"];
    $handle = fopen($file, "r");

    // Skip the first row (headers)
    fgetcsv($handle, 1000, ",");

    // Loop through the CSV file and insert Sports Data into the database
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $firstname = $data[0];
        $lastname = $data[1];
        $dateofbirth = $data[2];
        $sport = $data[3];
        $skynumbase = $data[4];
        $datebase = $data[5];
        $skynumfollowup1 = $data[6];
        $datefollowup1 = $data[7];
        $skynumfollowup2 = $data[8];
        $datefollowup2 = $data[9];

        // Find patientid from the patients table based on name and dateofbirth
        $patientIdQuery = "SELECT patientid FROM patients WHERE firstname = '$firstname' AND lastname = '$lastname' AND dateofbirth = '$dateofbirth'";
        $result = $conn->query($patientIdQuery);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $patientId = $row["patientid"];

            // Insert Sports Data into the sportsdata table
            $sql = "INSERT INTO sportsdata (patientid, sport, skynumbase, datebase, skynumfollowup1, datefollowup1, skynumfollowup2, datefollowup2) 
                    VALUES ('$patientId', '$sport', '$skynumbase', '$datebase', '$skynumfollowup1', '$datefollowup1', '$skynumfollowup2', '$datefollowup2')";

            if ($conn->query($sql) === TRUE) {
                echo "Sports Data inserted successfully for patient $firstname $lastname<br>";
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
if (isset($_POST["submitSportsBtn"]) && isset($_FILES["sportsDataFile"])) {
    handleCSVUploadSportsData($conn);
}

$conn->close();
?>
