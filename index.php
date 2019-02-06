<?php
include 'connection.php';
session_start();	
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
    $client=$_GET['client'];
    $email  =$_GET['email'];
    $q="SELECT token,isemail from `$client` where email='$email'; ";
    $q1=mysqli_query($con,$q);
    $row=mysqli_fetch_array($q1);
    if($row['isemail']==1) {
      $msg_verify="Already Verified";
      if ($client=="vendor") {
        header("location:vendors");
      }
    }
    else if($row['token'] == $token){
      $q="UPDATE `$client` SET isemail=1 where email='$email'; ";
      $q1=mysqli_query($con,$q);
      if ($client=="vendor") {
        header("location:vendors");
      } else {
        header("location:index.php");
      }
    }
    else{
      $msg_verify="Error Occured";
      if ($client=="vendor") {
        header("location:vendors");
      }
    }
  }
}

if(isset($_POST['log_email']) || isset($_POST['sign_email'])){

	if(isset($_POST['log_email'])){
		$log_email =$_POST['log_email'];
		$log_pass  =$_POST['log_pass'];
    $q="SELECT name,password,userid,email,location from user where email='$log_email'; ";
		$q1=mysqli_query($con,$q);
		$row=mysqli_fetch_array($q1);
		if($row['password'] == $log_pass){
			$_SESSION['log_userid'] =$row['userid'];
			$_SESSION['log_name'] =$row['name'];
      $_SESSION['log_email'] =$row['email'];
      $_SESSION['log_location'] =$row['location'];
			$_SESSION['log_client'] ="user";
      header("location:index.php");
		}
		else{
			$error_msg="incorrect email or password";
		}
	}
	else if(isset($_POST['sign_email'])){
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
      $token = 'qwertzuiopasdfghjklyxcvbnmQWERTZUIOPASDFGHJKLYXCVBNM0123456789*';
      $token = str_shuffle($token);
      $token = substr($token, 0, 15);
      $otp = '0123456789012345678901234567890123456789';
      $otp = str_shuffle($otp);
      $otp = substr($otp, 0, 6);
			$q="INSERT INTO `user` (`name`, `password`, `email`, `phone`, `location`,`gender`,`token`,`otp`) VALUES ('$sign_name', '$sign_pass', '$sign_email', '$sign_phone', '$sign_location' , '$sign_gender' ,'$token','$otp');";
			$q1=mysqli_query($con,$q);
			if($q1){
				$_SESSION['log_email'] =$sign_email;
				$_SESSION['log_name'] =$sign_name;
        $_SESSION['log_location'] =$sign_location;
        $q="SELECT userid from user where email='$sign_email'; ";
        $q1=mysqli_query($con,$q);
        $row=mysqli_fetch_array($q1);
        $_SESSION['log_userid'] =$row['userid'];
				$_SESSION['log_client'] ="user";
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
          $mail->Username = "email";
          $mail->Password = "pass";
          $mail->SetFrom("email","BookMyShaadi");
          $mail->Subject = "Please Verify Your Email";
          $_SESSION['verify_link']="http://localhost/builder-vending/index.php?email=$sign_email&token=$token&client=user";
          $mail->Body ="<center><h2>BookMyShaadi<div><a style='align:centre;' href='http://localhost/builder-vending/index.php?email=$sign_email&token=$token&client=user'>Verify Email</a></div><center>";
          $mail->AddAddress($sign_email,$sign_name);

           if(!$mail->Send()) {
              echo "Mailer Error: " . $mail->ErrorInfo;
           } 
        require('src/textlocal.class.php');

        $textlocal = new Textlocal(false,false, '');
        $numbers = array(substr($sign_phone, 1)); 
        $sender = 'TXTLCL';
        $message = " Hi ".$sign_name.". Your Verification code for BookMyShaadi is - ".$otp;

        try {
            $result = $textlocal->sendSms($numbers, $message, $sender);
            print_r($result);
        } catch (Exception $e) {
            //die('Error: ' . $e->getMessage());
        }
        
        header("location:index.php");
			}
		}
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>BookMyShaadi</title>
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
  <link href='https://fonts.googleapis.com/css?family=Great Vibes' rel='stylesheet'>
	<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>

</head>
<body>

  <?php 
    if (isset($_SESSION['log_email'])) {
    $email=$_SESSION['log_email'];
    $q="SELECT isemail,isphone,weddingweb from user where email='$email'; ";
    $q1=mysqli_query($con,$q);
    $row=mysqli_fetch_array($q1);
    if($row['isemail']==0 || $row['isphone']==0){

  ?>
  <div id="verification">
    <div>
    <?php  if($row['isemail']==0){ 
      echo "We have sent a verification link on ".$_SESSION['log_email']; 
      } else {
        echo "Email Verified"; 
      }
      ?>
    </div>
    <div>
      <?php  if($row['isphone']==0){ ?>
      We have sent an OTP on your Phone number
      <?php echo $msg_verify; ?>
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
		<img id="logo" src="images/logo.png"  height="45px" width="300px" align="left" alt="logo">
    <?php if(!isset($_SESSION['log_name'])){ ?>
    <div id="vendor-link">
      <a href="vendors  ">Are you a vendor?</a>
    </div>
		<span >	
  		<a onclick="document.getElementById('id02').style.display='block'" style="width:auto;" >Register </a>
  		<a onclick="document.getElementById('id01').style.display='block'" style="width:auto;">Login</a></span>
      <?php } else { ?>
        <div id="vendor-link">
      <a style="cursor:default;">Welcome <?php echo $_SESSION['log_name']; ?></a>
    </div>
        <span id="logout" > 
      <a  href="logout.php" style="width:auto;" >Logout </a></span>
       <?php } ?> 
	</nav>
  <?php if(!isset($_SESSION['log_name'])){ ?>
	<div id="front">
		<div id="slideshow">
   			<div>
          <div class="slide" style="background-image: url('https://i.imgur.com/liNt6Aj.jpg');  background-size: cover; background-position: center center;"></div>
   			</div>
   			<div>
          <div class="slide" style="background-image: url('https://i.imgur.com/Eex78Q3.jpg');  background-size: cover; background-position: center center;"></div>
   			</div>
		</div>
  <div id="slide-position"></div> 
		
	</div><?php } else { ?>
  <div id="search-space">
    <div id="home-search">
      <form class="home-search-container">
        <input list="datalist" type="search" class="category-search home-search-bar" placeholder="Search for">
        <input list="datalist" type="Search" class="location-search home-search-bar" value="<?php if (isset($_SESSION['log_location'])) echo $_SESSION['log_location']; ?>" placeholder="Locate">
        <datalist id="datalist">
        <option value="Delhi">
        <option value="Mumbai">
        <option value="Noida">
      </datalist>
      </form>
        <div class="search-search home-search-icon" >Search</div>
    </div>
  </div>
    <div id="vendors">
      <div id="filters">
        <div id="filter-applied">
        
        </div>
        <div class="filter-box">
            
        </div>
        <div class="filter-box">
          <div class="weddingweb">
          <center>
          <?php  if (isset($_SESSION['log_email'])) { ?>
          <a href="web/<?php echo $row['weddingweb']; ?>">Wedding website</a>
        <?php } ?></center>
          </div>
        </div>
        <div class="filter-box">
            <div class="advertise">
              <center>Ad</center>
            </div>
        </div>
      </div>

		<div id="ven-cards">
			<center><img id="loading" src="https://mir-s3-cdn-cf.behance.net/project_modules/disp/04de2e31234507.564a1d23645bf.gif"></center>
    </div>


  <?php } ?>
	</div>
  <?php if (isset($_SESSION['log_userid'])) { ?>
  <div id="prev-next">
    <center>
    <button class="button prev">
          <span>prev</span>
    </button>
    <button class="button next">
        <span>next</span>
    </button>
  </center>
  </div>
  <?php  } ?>
  <br><br><br>
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

	<div id="id01" class="modal">		
  		<form name="logForm"  class="modal-content animate" method="POST" >
  			
    		<div class="imgcontainer">
      			<span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
    		</div>
    		<img src="images/frame2.jpg">
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

	<div id="id02" class="modal">
  		<form name="signForm"  onsubmit="event.preventDefault(); submit_form();" class="modal-content animate" method="POST" >
    		<div class="imgcontainer">
      			<span onclick="document.getElementById('id02').style.display='none'" class="close" title="Close Modal">&times;</span>
    		</div>
    		<img src="images/frame2.jpg">
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
                <input list="datalist" name="sign_location" required>
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
  <?php if(!isset($_SESSION['log_name'])){ ?>
  <div id="search">
     <center><div id="main-welcome">Discover everything you need to plan your big day</div>
    </div></option></center>
  <?php } ?> 
	<script src="js/index.js" ></script>
  <script>

    var lim=<?php echo (isset($_SESSION['lim']))? $_SESSION['lim']:0; ?>;
      $(document).ready(function(){
        var location = "<?php if (isset($_SESSION['log_location'])) echo $_SESSION['log_location']; ?>";
          var category = $('.category-search').val();
          if($.trim(location) !='' || $.trim(category) !=''){
            $.ajax({
                url:"fetch.php",
                method:"GET",
                data:{l:location,c:category,lim:lim},
                dataType:"text",
                success:function(response){
                  $('#ven-cards').html(response);
                  $('.category-search').val()=category;
                  $('.location-search').val()=location;
                  console.log(location+category);
                }
              });
          }
        $('.search-search').click(function(){
          var location = $('.location-search').val();
          var category = $('.category-search').val();
          if($.trim(location) !='' || $.trim(category) !=''){
            $.ajax({
                url:"fetch.php",
                method:"GET",
                data:{l:location,c:category,low:low_lim,hig:hig_lim},
                dataType:"text",
                success:function(response){
                  $('#ven-cards').html(response);
                }
              });
          }
       });
        $('.next').click(function(){
          var location = $('.location-search').val();
          var category = $('.category-search').val();
          lim=lim+9;
          if($.trim(location) !='' || $.trim(category) !=''){
            $.ajax({
                url:"fetch.php",
                method:"GET",
                data:{l:location,c:category,lim:lim},
                dataType:"text",
                success:function(response){
                  $('#ven-cards').html(response);
                }
              });
          }
       });
        $('.prev').click(function(){
          var location = $('.location-search').val();
          var category = $('.category-search').val();
          if(lim>=9){
            lim=lim-9;
          }
          
          if($.trim(location) !='' || $.trim(category) !=''){
            $.ajax({
                url:"fetch.php",
                method:"GET",
                data:{l:location,c:category,lim:lim},
                dataType:"text",
                success:function(response){
                  $('#ven-cards').html(response);
                }
              });
          }
       });

    
});
  </script>
</body>
</html>