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

// Function to handle CSV file upload and data insertion for ADHD data
function saveADHD($conn, $filePath, $uploadedFileName) {
    // Skip the first row (headers)
    $file = fopen($filePath, 'r');
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
        $patientIdQuery = "SELECT patientid FROM patients WHERE firstname = ? AND lastname = ? AND dateofbirth = ?";
        $stmt = $conn->prepare($patientIdQuery);
        $stmt->bind_param("sss", $firstname, $lastname, $dateofbirth);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $patientId = $row["patientid"];

            // Insert ADHD data into the adhddata table
            $sql = "INSERT INTO adhddata (patientid, referralsource, previousdiagnosis, previoustesting, testingneeded, testingperformed, finaldiagnosis) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssss", $patientId, $referralSource, $previousDiagnosis, $previousTesting, $testingNeeded, $testingPerformed, $finalDiagnosis);
            
            if ($stmt->execute()) {
                echo "ADHD Data inserted successfully for patient $firstname $lastname<br>";
            } else {
                echo "Error inserting ADHD data: " . $stmt->error . "<br>";
            }
        } else {
            echo "Patient not found for $firstname $lastname and date of birth $dateofbirth<br>";
        }

        $stmt->close(); // Close the prepared statement
    }
    
    // Close the connection after handling all records
    $conn->close();
}

// Check if the form is submitted (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if a file is uploaded successfully
    if (isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] == UPLOAD_ERR_OK) {
        // Define the target directory for file uploads
        $targetDir = 'uploads/';
        $uploadedFileName = 'edited_data.csv'; // Specify the file name
        
        // Get the file name and create the full path
        // $uploadedFileName = basename($_FILES['csvFile']['name']);
        $targetFilePath = $targetDir . $uploadedFileName;

        // Move the uploaded file to the target directory
    //     if (move_uploaded_file($_FILES['csvFile']['tmp_name'], $targetFilePath)) {
    //         // Establish a database connection
    //         $conn = connectToDatabase();

    //         // Call the function to save ADHD data from the CSV file
    //         saveADHD($conn, $targetFilePath, $uploadedFileName);

    //         // Provide feedback to the user
    //         echo "File uploaded successfully. ADHD data inserted.";
    //     } else {
    //         echo "Error uploading the file.";
    //     }
    // } else {
    //     echo "No file uploaded or an error occurred during upload.";
    // }
        $conn = connectToDatabase();
        if (saveADHD($conn, $targetFilePath, $uploadedFileName)){
            echo "File uploaded successfully. ADHD data inserted.";
        }
    }
}
?>
