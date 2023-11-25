<?php
$servername = "localhost";
$username = "root";
$password = "Moindabi1@";
$dbname = "aztecusers";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function handleCSVUploadPatientInfo($conn) {
    $file = $_FILES["patientInfoFile"]["tmp_name"];
    $handle = fopen($file, "r");

    // Skip the first row (headers)
    fgetcsv($handle, 1000, ",");

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $firstname = $data[0];
        $lastname = $data[1];
        $dateofbirth = $data[2];
        $examDate = $data[3];
        $age = $data[4];
        $yearInSchool = $data[5];
        $gender = $data[6];
        $race = $data[7];
        $HSGPA = $data[8];
        $sport = $data[9];
        $position = $data[10];
        $currentMeds = $data[11];
        $contactOrGlasses = $data[12];
        $hoursSleepLastNight = $data[13];
        $avgHoursSleepWeek = $data[14];

        $patientIdQuery = "SELECT patientid FROM patients WHERE firstname = '$firstname' AND lastname = '$lastname' AND dateofbirth = '$dateofbirth'";
        $result = $conn->query($patientIdQuery);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $patientId = $row["patientid"];

            $sql = "INSERT INTO patient_current_info (patientid, examDate, age, yearInSchool, gender, race, HSGPA, sport, position, currentMeds, contactOrGlasses, hoursSleepLastNight, avgHoursSleepWeek) 
                    VALUES ('$patientId', '$examDate', '$age', '$yearInSchool', '$gender', '$race', '$HSGPA', '$sport', '$position', '$currentMeds', '$contactOrGlasses', '$hoursSleepLastNight', '$avgHoursSleepWeek')";

            if ($conn->query($sql) === TRUE) {
                echo "Patient Info inserted successfully for $firstname $lastname<br>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Patient not found for $firstname $lastname and date of birth $dateofbirth<br>";
        }
    }

    fclose($handle);
}

if (isset($_POST["submitPatientInfoBtn"]) && isset($_FILES["patientInfoFile"])) {
    handleCSVUploadPatientInfo($conn);
}

$conn->close();
?>
