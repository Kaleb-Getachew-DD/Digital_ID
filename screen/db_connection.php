<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ccbd_id_card_genarator";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
