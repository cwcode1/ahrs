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

    <form action="editable_csv.php" method="post" enctype="multipart/form-data">
        <label for="csvFile">Choose a CSV file:</label>
        <input type="file" name="csvFile" id="csvFile" accept=".csv" required>
        <button type="submit">Upload and Read</button>
    </form>

    <?php
    // Display CSV data in HTML table if available
    if (isset($csvData)) {
        echo '<h2>CSV Data:</h2>';
        echo '<div class="scroll-pane">';  // Add scrollpane
        echo '<table id="editableTable">';
        // Display headers
        echo '<tr>';
        foreach ($csvData[0] as $header) {
            echo '<th>' . htmlspecialchars($header) . '</th>';
        }
        echo '</tr>';

        // Display rows
        for ($i = 1; $i < count($csvData); $i++) {
            echo '<tr>';
            foreach ($csvData[$i] as $cell) {
                echo '<td contenteditable="true">' . htmlspecialchars($cell) . '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
        echo '</div>';

        // Buttons for preprocessing and saving
        echo '<button onclick="saveData()">Save</button>';
        echo '<h3>Please ensure that data is saved before submitting</h3>';
        echo '<form action="save_to_db.php" method="post">';
        echo '   <button type="submit">Submit</button>';
        echo '</form>';
    }
    ?>

    <script>

        function saveData() {
            // Extract data from the editable table
            const table = document.getElementById('editableTable');
            const rows = table.getElementsByTagName('tr');
            const newData = [];

            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                const newRow = [];

                for (let j = 0; j < cells.length; j++) {
                    newRow.push(cells[j].innerText);
                }

                newData.push(newRow);
            }

            // Convert the edited data to JSON
            const jsonData = JSON.stringify(newData);

            // Send the edited data to the server for saving
            fetch('save_data.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: jsonData,
            })
            .then(response => response.json())
            .then(data => {
                alert('Data saved successfully.');
            })
            .catch(error => {
                alert('Error saving data.');
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>
