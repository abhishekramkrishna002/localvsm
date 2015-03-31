


//if(isset($_POST['email'])&&  isset($_POST['first_name']) ){
//    $email=$_POST['email'];
////    $password= md5($_POST['password']);
////    $confirm_password = $_POST['confirm_password'];
//    $first_name = $_POST['first_name'];
//   // $last_name = $_POST['last_name'];
//    
////    if($password != $confirm_password){
////        $result_array = array('status'=>'error','statusCode'=>404,
////                    'message'=>'password mismatch');
////    print_r(json_encode($result_array));
////   
////    }
//    $SQL = "SELECT * FROM profile";
//    $result = $conn->query($SQL);
//    
//    while($row = $result->fetch_assoc()){
//        $uname = $row['profile_email'];
//        if($uname == $email){
//            $result_array = array('status'=>'error','statusCode'=>404,
//                    'message'=>'email already exists');
//                 print_r(json_encode($result_array));
//        }else{
//            $activation_code = rand();
//            $SQL = "INSERT INTO profile(`profile_name`,`email`) values ('$first_name','$email')";
//            $conn->query($SQL);
//            $params=array('host'=>"ssl://smtp.gmail.com",'port'=>465,'auth'=>true,'username'=>"fassha08@gmail.com",'password'=>"noteb00k");
//            $smtp_mail= &Mail::factory("smtp",$params);
//            print_r($params);
//            $value=$smtp_mail->send ( array("fassha08@gmail.com") , null , "test message" );
//
//            
//           
//            
//           print_r($value);
//            if($value){
//            $apiResponse = array(
//                                'status'=>'success',
//                                'statusCode'=>201,
//                                'message'=>'User created successfully, email sent',
//                                
//                            );
//             print_r(json_encode($apiResponse));
//            }
//        }
//    }
//}

<?php

require_once "Mail.php";
require_once('Mail/mime.php');
include '../libs/sql.php';

if(isset($_POST['email'])&&  isset($_POST['first_name']) ){
  $email=$_POST['email'];
  $first_name = $_POST['first_name'];
   // $last_name = $_POST['last_name'];
    
    $SQL = "SELECT * FROM profile";
    $result = $conn->query($SQL);
    
    while($row = $result->fetch_assoc()){
        $uname = $row['profile_email'];
        if($uname == $email){
            $result_array = array('status'=>'error','statusCode'=>404,
                    'message'=>'email already exists');
                 print_r(json_encode($result_array));
        }else{
            
        $activation_code = rand();
         $SQL = "INSERT INTO profile(`profile_name`,`email`) values ('$first_name','$email')";
           $conn->query($SQL);
  
  
  
            $from    = "fassha08@gmail.com"; // the email address

            $to=$email;


               $subject = 'Testing from VSM';


                $body = '<html>
                <head>
                  <title>Test by VSM</title>
                </head>
                <body>
                <p>This is a test of <b>HTML</b></p>
                <p>from VSM</p>
                </body>
                </html>';

// login data

    $host    = "smtp.gmail.com";

    $port    =  "587";

    $user    = "fassha08@gmail.com";
    $pass    = "noteb00k";
    
    $smtp    = @Mail::factory("smtp", array("host"=>$host, "port"=>$port, "auth"=> true, "username"=>$user, "password"=>$pass));

    $headers = array("From"=> $from,

        "To"=>$to, 
        "Subject"=>$subject,
        "MIME-Version"=>"1.0",
        "Content-type"=>"text/html; charset=iso-8859-1"
        );


    $mail    = @$smtp->send($to, $headers, $body);

    if (PEAR::isError($mail)){
        echo "error: {$mail->getMessage()}";
    } else {
        echo "Message sent";
    }
        }
    }
}
?>
