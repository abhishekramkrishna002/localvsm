<?php

if(isset($_GET['oauth_key']) && !empty($_GET['oauth_key']))
{
    //print_r($_GET['oauth_key']);
   logout($_GET['oauth_key']); 
}
function logout($ouath_key) {
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "vsn";

// Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "DELETE FROM vsn.oauth where oauth_key='$ouath_key'";
    //echo $sql;
    $result = $conn->query($sql);
  
        header("Location: http://localhost/vsn/");
    
    $conn->close();
    
}

?>