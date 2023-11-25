<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the JSON data from the request body
    $json_data = file_get_contents("php://input");

    // Decode the JSON data
    $data = json_decode($json_data, true);

    // Specify the directory where the file will be saved
    $upload_directory = __DIR__ . "/uploads/";

    // Check if the directory exists, and create it if not
    if (!is_dir($upload_directory)) {
        mkdir($upload_directory, 0777, true);
    }

    // Specify the file path
    $csv_file_path = $upload_directory . "edited_data.csv";

    // Open the file for writing
    $csv_file = fopen($csv_file_path, "w");

    // Check if the file was opened successfully
    if ($csv_file === false) {
        echo json_encode(["status" => "error", "message" => "Unable to open the CSV file for writing"]);
        exit;
    }

    // Write data to the file
    foreach ($data as $row) {
        fputcsv($csv_file, $row);
    }

    // Close the file
    fclose($csv_file);

    // Respond with a success message
    echo json_encode(["status" => "success"]);
} else {
    // Respond with an error message if not a POST request
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

?>
