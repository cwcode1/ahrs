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
?>