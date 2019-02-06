<?php
include 'connection.php';
session_start();  
$vendor=$_GET['q'];
$error_msg="";
$msg_verify="";

if(isset($_POST['otp_verify']) || isset($_GET['token'])){
  if(isset($_POST['otp_verify'])){
    $otp =$_POST['otp'];
    $email  =$_SESSION['log_email'];
    $q="SELECT otp,isphone from user where email='$email'; ";
    $q1=mysqli_query($con,$q);
    $row=mysqli_fetch_array($q1);
    if($row['isphone']==1) {
      $msg_verify="Already Verified";
    }
    else if($row['otp'] == $otp){
      $q="UPDATE user SET isphone=1 where email='$email'; ";
      $q1=mysqli_query($con,$q);
      header("location:index.php");
    }
    else{
      $msg_verify="Wrong OTP";
    }
  }
  else if(isset($_GET['token']) && isset($_GET['email']) ){
    $token =$_GET['token'];
    $email  =$_GET['email'];
    $q="SELECT token,isemail from user where email='$email'; ";
    $q1=mysqli_query($con,$q);
    $row=mysqli_fetch_array($q1);
    if($row['isemail']==1) {
      $msg_verify="Already Verified";
    }
    else if($row['token'] == $token){
      $q="UPDATE user SET isemail=1 where email='$email'; ";
      $q1=mysqli_query($con,$q);
      header("location:index.php");
    }
    else{
      $msg_verify="Error Occured";
    }
  }
}

if(isset($_POST['log_email']) || isset($_POST['sign_email'])){


  if(isset($_POST['log_email'])){
    $log_email =$_POST['log_email'];
    $log_pass  =$_POST['log_pass'];
    $q="SELECT name,password,userid from user where email='$log_email'; ";
    $q1=mysqli_query($con,$q);
    $row=mysqli_fetch_array($q1);
    if($row['password'] == $log_pass){
      $_SESSION['log_userid'] =$row['userid'];
      $_SESSION['log_name'] =$row['name'];
      $_SESSION['log_client'] ="user";
      header("location:".$vendor);
    }
    else{
      $error_msg="incorrect email or password";
    }
  }
  else if(isset($_POST['sign_email'])){
    echo "string";
    $sign_name    =$_POST['sign_name'];
    $sign_pass    =$_POST['sign_pass'];
    $sign_email   =$_POST['sign_email'];
    $sign_phone   =$_POST['sign_phone'];
    $sign_location=$_POST['sign_location'];
    $sign_gender  =$_POST['sign_gender'];
    $q2="SELECT email from user where email='$sign_email' ";
    $row=mysqli_query($con,$q2);
    $rowcount=mysqli_num_rows($row);
    if($rowcount>0){
      $error_msg= "email already exists";
    }
    else{
      $q="INSERT INTO `user` (`name`, `password`, `email`, `phone`, `location`,`gender`) VALUES ('$sign_name', '$sign_pass', '$sign_email', '$sign_phone', '$sign_location' , '$sign_gender');";
      $q1=mysqli_query($con,$q);
      if($q1){
        $_SESSION['log_email'] =$sign_email;
        $_SESSION['log_name'] =$sign_name;
        $_SESSION['log_client'] ="user";
        $q_id="SELECT userid from user where email='$sign_email'; ";
        $q1_id=mysqli_query($con,$q_id);
        $row_id=mysqli_fetch_array($q1);
        $_SESSION['log_userid'] =$row_id['userid'];
        require 'src/PHPMailer.php';
        require 'src/SMTP.php';

          $mail = new PHPMailer\PHPMailer\PHPMailer;
          $mail->IsSMTP(); // enable SMTP
          $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
          $mail->SMTPAuth = true; // authentication enabled
          $mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for Gmail
          $mail->Host = "smtp.gmail.com";
          $mail->Port = 587; // or 587
          $mail->IsHTML(true);
          $mail->Username = "envyproductions15@gmail.com";
          $mail->Password = "privacy.envy15";
          $mail->SetFrom("envyproductions15@gmail.com","BookMyShaadi");
          $mail->Subject = "Please Verify Your Email";
          $_SESSION['verify_link']="http://localhost/builder-vending/index.php?email=$sign_email&token=$token&client=user";
          $mail->Body ="<center><h2>BookMyShaadi<div><a style='align:centre;' href='http://localhost/builder-vending/index.php?email=$sign_email&token=$token&client=user'>Verify Email</a></div><center>";
          $mail->AddAddress($sign_email,$sign_name);

           if(!$mail->Send()) {
              echo "Mailer Error: " . $mail->ErrorInfo;
           } 
        require('src/textlocal.class.php');

        $textlocal = new Textlocal(false,false, 'TB2xriedCro-naHHvyJSKDxSWZCC9gKDx1kFPG8EHf');
        $numbers = array($sign_phone);
        $sender = 'TXTLCL';
        $message = "Your Verification code for BookMyShaadi is - ".$otp;

        try {
            $result = $textlocal->sendSms($numbers, $message, $sender);
            print_r($result);
        } catch (Exception $e) {
            //die('Error: ' . $e->getMessage());
        }
        header("location:".$vendor);
      }
    }
  }
}
    $q="SELECT * from vendor where vendorid='$vendor'; ";
    $q1=mysqli_query($con,$q);
    $row=mysqli_fetch_array($q1);

?>
<!DOCTYPE html>
<html>
<head>
	<title>BookMyShaadi</title>
	<link rel="stylesheet" type="text/css" href="../css/vendor.css">
	<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
	<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
</head>
<body>


  <?php 
    if (isset($_SESSION['log_email'])) {
    $email=$_SESSION['log_email'];
    $q_ver="SELECT isemail,isphone from user where email='$email'; ";
    $q1_ver=mysqli_query($con,$q_ver);
    $row_ver=mysqli_fetch_array($q1_ver);
    if($row_ver['isemail']==0 || $row_ver['isphone']==0){

  ?>
  <div id="verification">
    <div>
    <?php  if($row_ver['isemail']==0){ 
      echo "We have sent a verification link on ".$_SESSION['log_email']; 
      } else {
        echo "Email Verified"; 
      }
      ?>
    </div>
    <div>
      <?php  if($row_ver['isphone']==0){ echo $msg_verify." "; ?>
      We have sent an OTP on your Phone number
        <form method="POST">
          <input type="text" name="otp" placeholder="Please Enter the OTP">
          <input type="submit" name="otp_verify" value="Verify">
        </form> 
      <?php  } else {
        echo "Phone Number Verified"; 
      }
      ?>
  </div>
  </div><?php } } ?>

	<nav>
		<img id="logo" src="../images/logo.png"  height="45px" width="300px" align="left" alt="logo">
    <?php if(!isset($_SESSION['log_name'])){ ?>
    <div id="vendor-link">
      <a href="../vendors">Are you a vendor?</a>
    </div>
		<span >	
  		<a onclick="document.getElementById('id02').style.display='block'" style="width:auto;" >Register </a>
  		<a onclick="document.getElementById('id01').style.display='block'" style="width:auto;">Login</a></span>
      <?php } else { ?>
    <div id="vendor-link">
      <a style="cursor:default;">Welcome <?php echo $_SESSION['log_name']; ?></a>
    </div>
        <span id="logout" > 
      <a  href="../logout.php" style="width:auto;" >Logout </a></span>
       <?php } ?> 
	</nav>

     <div class="container-profile">
        
        <div class="profile">
            <div class="profile-pic-container" >
            <div class="profile-pic" style="background: url('../uploads/<?php echo $row['displaypicture']; ?>');background-size: cover; background-position: center center;"></div></div>
            <div class="profile-info">
                <div class="contact"><a href="tel:<?php echo $row['phone'];?>"><?php echo $row['phone'];?></a></div>
                <div class="name"><?php echo $row['name'] ?></div>
                <div class="name-tag"><?php echo $row['address']."<br>".$row['city']; ?></div>
                
            </div>
            
        </div>
        <center><div class="desc"><?php echo $row['description']; ?></div></center>
        <div class="profile-popularity">
                <div class="field"><span class="pop-tag">Posts</span><span class="pop-num">6</span></div>
                <div class="field"><span class="pop-tag">Tagging</span><span class="pop-num">271</span></div>
                <div class="field"><span class="pop-tag">Tagged</span><span class="pop-num">118</span></div>
            </div>
        <div class="images-profile">
            <?php
                $dir = scandir("uploads/");
                foreach ($dir as $value) {
                     if(preg_match("/-".$row['vendorid']."./", $value)){ 
                      if(!preg_match("/displaypicture/", $value)){  ?>
                        <div onclick="view_image(this.style.background)" style="background: url('../uploads/<?php echo $value; ?>');background-size: cover; background-position: center center;" class="img"></div>
                <?php } } } ?>
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
        <center>
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
        BookMyShaadi
      <div class="sub-main">
      All Rights Reserved
    </div>
    </center>
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


<div id="id03" class="modal-vendor" onclick="document.getElementById('id03').style.display='none'">   
      <div class="modal-vendor-content animate" >
        
        <div class="imgcontainer">
            <span onclick="document.getElementById('id03').style.display='none'" class="close" title="Close Modal">&times;</span>
        </div>
        
      <div class="container" id="full-image">
        </div>
      </div>
  </div>
  <div id="id01" class="modal1">   
      <form name="logForm"  class="modal-content animate" method="POST" >
        
        <div class="imgcontainer">
            <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
        </div>
        <img src="../images/frame2.jpg">
      <div class="container">
        <div class="group">      
                <input type="text" name="log_email" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Email</label>
              </div>
            <div class="group">      
                <input type="password" name="log_pass" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Password</label>
              </div>
              <div id="log_error_msg" class="error_msg"><?php if($error_msg=="incorrect email or password") echo $error_msg; ?></div>
            <input type="submit" name="login" value="login" >
        </div>
      </form>
  </div>

  <div id="id02" class="modal1">
      <form name="signForm"  onsubmit="event.preventDefault(); submit_form();" class="modal-content animate" method="POST" >
        <div class="imgcontainer">
            <span onclick="document.getElementById('id02').style.display='none'" class="close" title="Close Modal">&times;</span>
        </div>
        <img src="../images/frame2.jpg">
        <div class="container">
            <div class="group">      
                <input type="text" name="sign_name"  required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Name</label>
              </div>

            <div class="group">      
                <input type="text" name="sign_email" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Email</label>
              </div>

            <div class="group">      
                <input type="password" name="sign_pass" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Password</label>
              </div>

            <div class="group">      
                <input type="text" name="sign_phone" value="+91" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Phone</label>
              </div>
              <div class="group">      
                <input list="datalist" name="sign_location">
                  <datalist id="datalist">
                    <option value="Delhi">
                    <option value="Mumbai">
                    <option value="Noida">
                  </datalist>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Location</label>
              </div>
            <div class="gender">      
                <p>I am</p>
                <div><input type="radio" name="sign_gender" value="male" required><span>Male</span></div>
                <div><input type="radio" name="sign_gender" value="female" required><span>Female</span></div>
                <div><input type="radio" name="sign_gender" value="other" required><span>Others</span></div>
              </div>
              <div id="sign_error_msg" class="error_msg"><?php if($error_msg=="email already exists") echo $error_msg; ?></div>
          <input type="submit" name="signup" value="Sign Up">
        </div>
    </form>
  </div>

  <script src="../js/vendor.js" ></script>

</body>
</html>