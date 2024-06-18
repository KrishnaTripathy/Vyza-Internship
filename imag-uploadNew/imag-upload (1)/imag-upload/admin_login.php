<?php
session_start();
include "admin_authenticate.php";


if (isset($_POST['uname']) && isset($_POST['password'])) {


    
    $username = $_POST['uname'];
    $pass = $_POST['password'];


    if (empty($username)) {
        header("Location: admin_upload.php?error=UserName is required");
        exit();
    } elseif (empty($pass)) {
        header("Location: admin_upload.php?error=Password is required");
        exit();
    } else {
        
        $sql = "SELECT * FROM admin_credentials WHERE username = ? AND password = ?";
        $stmt = mysqli_stmt_init($con);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: admin_upload.php?error=SQL Error");
            exit();
        } else {

            $res=mysqli_stmt_bind_param($stmt, "ss", $username, $pass);
          

            $res12=mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

          
            if (mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);
                if($row['username'] === $username && $row['password'] === $pass){
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['id'] = $row['id'];
                    header("Location: Gallery.php");
                    exit();
                }
              
                
            } else {
                header("Location: admin_upload.php?error=Incorrect User name or password");
                exit();
                $res11=mysqli_num_rows($result);
                print_r($res11);

                
                print_r($res);

            }
        }
    }
} else {
    header("Location: admin_upload.php");
    exit();
 }
?>
