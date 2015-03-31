<?php

function check_user($username_user,$password_user,$device){
    include 'sql.php';
    $id=get_profile_id($username_user, $password_user);
    
    $SQL = "SELECT * FROM user WHERE profile_id=$id";
    
    $result = $conn->query($SQL);
   
    if ($result->num_rows > 0) {

            $row = $result->fetch_assoc();
        if(($row['username'] == $username_user) && ($row['password'] == $password_user)){
            get_oauth($username_user, $password_user, $device);
            $conn->close();
        }
        else {
    $result_array = array('status'=>'error','statusCode'=>404,
                    'message'=>'User not found');
    print_r(json_encode($result_array));
   
}
    }
}

function get_profile_id($username_user, $password_user) {
    $profile_id = null;
    if (isset($username_user) && isset($password_user)) {
        include 'sql.php';
        $sql = "SELECT profile_id FROM vsn.user where username='$username_user' and password='$password_user'";
        
        $result = $conn->query($sql);
        

        if ($result->num_rows > 0) {

            $row = $result->fetch_assoc();
            $profile_id = $row['profile_id'];
            
        }
    }
    
    return $profile_id;
}

function check_oauth($profile_id, $device) {
    $oauth_key = null;
    if (isset($profile_id) && isset($device)) {
        include 'sql.php';

        $date = date_create();
        date_timestamp_set($date, time());
        $today = date_format($date, "Y-m-d H:i:s");
        $sql = "SELECT * FROM vsn.oauth where profile_id=$profile_id and device = '$device'  ";

        // echo $sql;
        $result = $conn->query($sql);
//        /print_r()
        if ($result->num_rows > 0) {

            $row = $result->fetch_assoc();
            $expiry_date = $row['oauth_expiry'];
            if ($expiry_date >= $today) {
                $oauth_key = $row['oauth_key'];
            }
        }
    }

    return $oauth_key;
}

function get_oauth($username_user, $password_user, $device) {
    if (isset($username_user) && isset($password_user) && isset($device)) {

        $profile_id = get_profile_id($username_user, $password_user);
        $oauth_key = check_oauth($profile_id, $device);
        $status = "failure";
        if ($profile_id != null && $oauth_key == null) {
            $servername = "localhost";
            $username = "root";
            $password = "root";
            $dbname = "vsn";
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                echo 'no connection';
                die("Connection failed: " . $conn->connect_error);
            }
            /*
             * generate and write the oauth key to db
             */
            $token = md5(uniqid(rand(), true));
            $date = date_create();
            date_timestamp_set($date, time() + ( 2 * 24 * 60 * 60));
            $dtm = date_format($date, "Y-m-d H:i:s");
            $sql = "insert into vsn.oauth(oauth_key,oauth_expiry,device,profile_id) value ('$token','$dtm','$device',$profile_id)";
            $result = $conn->query($sql);
            $oauth_key = $token;
            $result_array = array('status'=>'success',
                    'statusCode'=>200,
                    'message'=>'User found', 'oauth' => $oauth_key, 'number_of_devices_logged_in' => get_users_logged_in($profile_id));
            $conn->close();
        }
        else if ($oauth_key != null) {
            $status = "sucess";
            $result_array = array('status'=>'success',
                    'statusCode'=>200,
                    'message'=>'User found', 'oauth' => $oauth_key, 'number_of_devices_logged_in' => get_users_logged_in($profile_id));
        }
        else
        {
            $result_array = array('status'=>'failure',
                    'statusCode'=>404,
                    'message'=>'User not found');
        }
        
        print_r(json_encode($result_array));
        print_r("<a href='http://localhost/vsn/api/logout/?oauth_key=".$oauth_key."'>logout</a>");
    }
}

function get_users_logged_in($profile_id) {
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "vsn";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        echo 'no connection';
        die("Connection failed: " . $conn->connect_error);
    }

    $date = date_create();
    date_timestamp_set($date, time());
    $today = date_format($date, "Y-m-d H:i:s");
    $sql = "SELECT * FROM vsn.oauth where profile_id='$profile_id' ";

     //echo $sql;
    $result = $conn->query($sql);
   // echo $result;
   if (!$result) {
    die(sprintf("Error: %s", $conn->error));
}
    $count = 0;
    while ($row = $result->fetch_assoc()) {
        //$row = $result->fetch_assoc();
        $expiry_date = $row['oauth_expiry'];
        if ($expiry_date >= $today) {
            //  $oauth_key = $row['oauth_key'];
            $count++;
        }
    }
    return $count;
}


?>