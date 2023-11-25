<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if a file was uploaded
    if (isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] === UPLOAD_ERR_OK) {
        // Get the uploaded file information
        $fileTmpPath = $_FILES['csvFile']['tmp_name'];
        $fileName = $_FILES['csvFile']['name'];

        // Check if the file is a CSV file
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        if (strtolower($fileExtension) === 'csv') {
            // Read CSV file
            $csvData = array_map('str_getcsv', file($fileTmpPath));
        } else {
            echo 'Please upload a valid CSV file.';
        }
    } else {
        echo 'Error uploading the file.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV File Reader</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #ddd;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
         /* Set a fixed height for the div */
         .scroll-pane {
            overflow-x: auto;
            max-height: 300px; /* Adjust the height as needed */
        }
    </style>
</head>
<body>
    <h1>CSV File Reader</h1>

    <form action="read_csv.php" method="post" enctype="multipart/form-data">
        <label for="csvFile">Choose a CSV file:</label>
        <input type="file" name="csvFile" id="csvFile" accept=".csv" required>
        <button type="submit">Upload and Read</button>
    </form>

    <?php
        // Display CSV data in HTML table if available
        if (isset($csvData)) {
            echo '<h2>CSV Data:</h2>';
            echo '<div style="overflow-x:auto;">';  // Add scrollpane
            echo '<table>';
            // Display headers
            echo '<tr>';
            foreach ($csvData[0] as $header) {
                echo '<th>' . htmlspecialchars($header) . '</th>';
            }
            echo '</tr>';
            
            // Display rows
            for ($i = 2; $i <= min(10, count($csvData)); $i++) {
                echo '<tr>';
                foreach ($csvData[$i - 1] as $cell) {
                    echo '<td>' . htmlspecialchars($cell) . '</td>';
                }
                echo '</tr>';
            }
            echo '</table>';
            echo '</div>';

            // Buttons for preprocessing and saving
            echo '<button onclick="preprocessing()">Preprocessing</button>';
            echo '<button onclick="saveData()">Save</button>';
        }
    ?>

    <script>
        function preprocessing() {
            // Add preprocessing logic here
            alert('Preprocessing logic goes here.');
        }

        function saveData() {
            // Add saving logic here
            alert('Saving logic goes here.');
        }
    </script>
</body>
</html>
