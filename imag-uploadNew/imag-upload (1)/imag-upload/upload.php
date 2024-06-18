<?php
session_start();

if (isset($_POST['submit']) && isset($_FILES['my_image'])) {
    include "admin_authenticate.php";

    $img_name = $_FILES['my_image']['name'];
    $img_size = $_FILES['my_image']['size'];
    $tmp_name = $_FILES['my_image']['tmp_name'];
    $error = $_FILES['my_image']['error'];

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["my_image"])) {
        $imageFileType = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
        
        
        $new_img_name = uniqid("IMG-", true) . '.' .$imageFileType;

        $target_dir = 'media/';
        $target_file = $target_dir .$new_img_name;

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            exit();
        }

        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            exit();
        }

        if (move_uploaded_file($_FILES["my_image"]["tmp_name"], $target_file)) {
            echo "The file " .basename($_FILES["my_image"]["name"]) . " has been uploaded.";

            
            $sql = "INSERT INTO image_upload(image) VALUES('$new_img_name')";
            if (mysqli_query($con, $sql)) {
                header("Location: media.php");
                exit();
            } else {
                echo "Error: " .mysqli_error($con);
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "No file uploaded.";
    }
} else {
    header("Location: Gallery.php");
    exit();
}
?>
