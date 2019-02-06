<?php
include 'connection.php';
session_start();
$error_msg="";
$web=$_GET['q'];
if (isset($_POST['submit'])) {
  $userid=$_SESSION['log_userid'];
  $bride=$_POST['bride'];
  $groom=$_POST['groom'];
  $weddingweb=strtolower($bride."-and-".$groom);
  $q2="SELECT weddingweb from user where weddingweb='$weddingweb' AND userid<>'$userid' ";
    $row=mysqli_query($con,$q2);
    $rowcount=mysqli_num_rows($row);
    if($rowcount>0){
      $error_msg= "Sorry! Already exists";
    }
    else{
        $q="UPDATE `user` SET `weddingweb`='$weddingweb' WHERE userid='$userid';";
        $q1=mysqli_query($con,$q);
              date_default_timezone_set('Asia/Kolkata');
              $current_date = date('YmdHis');

              $_SESSION['upload_error']='';
              $target_dir = "uploads/";
              $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
              $uploadOk = 1;
              $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
              $target_file = $target_dir .$weddingweb."_".$current_date.".".$imageFileType;
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
              
        header("location:".$weddingweb);
    }
    echo $error_msg;
    echo  $_SESSION['upload_error'];

}


$q2="SELECT weddingweb,userid from user where weddingweb='$web' AND weddingweb<>'none'  ";
    $row=mysqli_query($con,$q2);
    $row_v=mysqli_fetch_array($row);
    $rowcount=mysqli_num_rows($row);
    if($rowcount==0){
      if (!(isset($_SESSION['log_userid']) && $web=='none'))
      header("location:../index.php");
    }
  if (!(isset($_SESSION['log_userid']) && $web=='none')){
  $bride=preg_split("/-/", $web)[0];
  $groom=preg_split("/-/", $web)[2];
  }
  else{
    $bride="bride";
    $groom="groom";
  }
?>
<!DOCTYPE html>
<html>
<head>
	<title>bookmyshaadi</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="../css/web.css">
    <link rel="shortcut icon" href="https://yt3.ggpht.com/a-/AN66SAw4QGUPwNSyNtMtjqjgewrknVZ2hLSg0WJILg=s900-mo-c-c0xffffffff-rj-k-no" type="image/png">
	<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
	<link href='https://fonts.googleapis.com/css?family=Great Vibes' rel='stylesheet'>

</head>
<body>
  <?php
  if (isset($_SESSION['log_userid']))
   if ($_SESSION['log_userid']==$row_v['userid'] || $web='none') { ?>
  <div id="setform">
    <form method="POST" enctype="multipart/form-data">

    <input type="text" name="bride" value="<?php echo ucwords($bride); ?>" placeholder="Enter Bride's Name">
    <input type="text" name="groom" value="<?php echo ucwords($groom); ?>" placeholder="Enter Grooms's Name">
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" name="submit" value="Build">
  </form>
  </div>
<?php } ?>
  <div id="nav-bg">
    <div id="home-search">
      <div class="home-search-container">
        <center><?php echo ucwords($bride."<br>weds<br>"); echo ucwords($groom); ?></center>
      </div>
    </div>
  </div>
  <center><div id="welcome">Welcome To Our Wedding</div></center>
  <center>
    <?php
                $dir = scandir("uploads/");
                $url='';
                foreach ($dir as $value) {
                      if(preg_match("/".$web."_/", $value)){
                        $url=$value;
                 } } ?>
                <div  style="background: url('../uploads/<?php echo $url; ?>');background-size: cover; background-position: center center;" id="img-frame" ></div>

    </center>
  <div id="main">
    
    <div>
      
    </div>

  </div>

  <footer>
    <div>
      <a href="#" ><span>Blog</span></a>
      <a href="#" ><span>Advertise</span></a>
      <a href="#" ><span>Sponsor</span></a>
      <a href="#" ><span>Contact Us</span></a>
      <a href="#" ><span>Disclosure Policy</span></a>
    </div>
    <hr>
    <div id="rights"> 
      <select>
        <option>HOME</option>
        <option>TRAVEL</option>
        <option>MUSIC</option>
        <option>FOOD</option>
        <option>TECHNO</option>
        <option>DIY</option>
        <option>FASHION</option>
        <option>HEALTH</option>
        <option>SUBMIT</option>
      </select><br><br>
      <input type="email" placeholder="Enter Email.." /><br>
      <div class="sub-main">
      <button class="button-two"><span>Subscribe</span></button>
    </div>
    </div>
    <hr>
    <div>
      <div class="fb-page" 
  data-href="https://www.facebook.com/facebook"
  data-width="380" 
  data-hide-cover="false"
  data-show-facepile="false"></div>
    </div>
  </footer>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v3.2';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

</script>
</body>
</html>