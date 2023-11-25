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

// Function to handle CSV file upload and data insertion
function handleCSVUpload($conn) {
    // Retrieve the uploaded file from the form
    $file = $_FILES["file"]["tmp_name"];
    $handle = fopen($file, "r");

    // Skip the first row (headers)
    fgetcsv($handle, 1000, ",");

    // Loop through the CSV file and insert data into the database
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $first_name = $data[0];
        $last_name = $data[1];
        $date_of_birth = $data[2];
        $gender = $data[3];
        $address = $data[4];
        $city = $data[5];
        $state = $data[6];
        $zip_code = $data[7];
        $phone_number = $data[8];
        $email = $data[9];
        // Add more fields as needed

        // Insert data into the database
        $sql = "INSERT INTO patients (first_name, last_name, date_of_birth, gender, address, city, state, zip_code, phone_number, email) 
                VALUES ('$first_name', '$last_name', '$date_of_birth', '$gender', '$address', '$city', '$state', '$zip_code', '$phone_number', '$email')";
        // Add more fields as needed

        if ($conn->query($sql) === TRUE) {
            echo "Record inserted successfully<br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    fclose($handle);
}

// Check if the form is submitted and the file is uploaded
if (isset($_POST["submitBtn"]) && isset($_FILES["file"])) {
    handleCSVUpload($conn);
}

$conn->close();
?>
