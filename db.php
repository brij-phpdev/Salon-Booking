<?php
$servername = "62.72.28.103";
$database = "u963541063_salon_booking";
$username = "u963541063_salon_bkng_usr";
$password = "8}_!.)%%Atnh65@11Oct23";
 
// Create connection
 
$conn = mysqli_connect($servername, $username, $password, $database);
 
// Check connection
 
if (!$conn) {
 
    die("Connection failed: " . mysqli_connect_error());
 
}
echo "Connected successfully";
mysqli_close($conn);
?>