<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
$current_date = date('YmdHis');

$_SESSION['upload_error']='';
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$target_file = $target_dir .$current_date."-".$_SESSION['log_vendorid'].".".$imageFileType;
if (isset($_POST['displaypicture'])) {
    $target_file = "displaypicture_".$current_date."-".$_SESSION['log_vendorid'].".".$imageFileType;
    include 'connection.php';
    $log_vendorid=$_SESSION['log_vendorid'];
    $q="UPDATE `vendor` SET `displaypicture`='$target_file' WHERE vendorid='$log_vendorid';";
    $q1=mysqli_query($con,$q);
    $target_file =$target_dir.$target_file;
}
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        $_SESSION['upload_error'].="File is not an image.<br>";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    $_SESSION['upload_error'].= "file already exists.<br>";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 5000000) {
    $_SESSION['upload_error'].= "your file is too large.<br>";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    $_SESSION['upload_error'].= "only JPG, JPEG, PNG & GIF files are allowed.<br>";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    $_SESSION['upload_error'].= "Sorry, your file was not uploaded.<br>";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $_SESSION['upload_error']= "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        $_SESSION['upload_error'].= "Sorry, there was an error uploading your file.<br>";
    }
    
}
header("location:vendors");
?>