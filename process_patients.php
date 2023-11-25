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

// Function to handle CSV file upload and data insertion for patients
function handleCSVUploadPatients($conn) {
    // Retrieve the uploaded file from the form
    $file = $_FILES["patientFile"]["tmp_name"];
    $handle = fopen($file, "r");

    // Skip the first row (headers)
    fgetcsv($handle, 1000, ",");

    // Loop through the CSV file and insert data into the database
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $firstname = $data[0];
        $lastname = $data[1];
        $dateofbirth = $data[2];
        $address = $data[3];
        $city = $data[4];
        $state = $data[5];
        $zipcode = $data[6];
        $email = $data[7];

        // Assuming $data[2] contains the date of birth in 'MM/DD/YYYY' format
        $dob = DateTime::createFromFormat('m/d/Y', $data[2]);
        $formattedDateOfBirth = $dob->format('Y-m-d');

        // Insert data into the database
        $sql = "INSERT INTO patients (firstname, lastname, dateofbirth, address, city, state, zipcode, email) 
                VALUES ('$firstname', '$lastname', '$formattedDateOfBirth', '$address', '$city', '$state', '$zipcode', '$email')";

        if ($conn->query($sql) === TRUE) {
            echo "Patient information inserted successfully<br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    fclose($handle);
}

// Check if the form is submitted and the file is uploaded
if (isset($_POST["submitPatientsBtn"]) && isset($_FILES["patientFile"])) {
    handleCSVUploadPatients($conn);
}

$conn->close();
?>
