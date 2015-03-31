<?php
include '../libs/helper.php';
//print_r($_POST);

if (isset($_POST['useragent']) && isset($_POST['username']) && isset($_POST['password']) && !empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['useragent'])) {
    $username_user = $_POST['username'];
    $password_user = md5($_POST['password']);
    $device = $_POST['useragent'];
    get_oauth($username_user, $password_user, $device);
    } 
    else {
    $result_array = array('status'=>'error','statusCode'=>404,
                    'message'=>'User not found');
    print_r(json_encode($result_array));
   
}

?>
