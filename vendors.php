<?php
include 'connection.php';
session_start();	
$error_msg="";
$msg_verify="";

if(isset($_POST['otp_verify']) || isset($_GET['token'])){
  if(isset($_POST['otp_verify'])){
    $otp =$_POST['otp'];
    $email  =$_SESSION['log_email'];
    $q="SELECT otp,isphone from vendor where email='$email'; ";
    $q1=mysqli_query($con,$q);
    $row=mysqli_fetch_array($q1);
    if($row['isphone']==1) {
      $msg_verify="Already Verified";
    }
    else if($row['otp'] == $otp){
      $q="UPDATE vendor SET isphone=1 where email='$email'; ";
      $q1=mysqli_query($con,$q);
      header("location:vendors"); 
    }
    else{
      $msg_verify="Wrong OTP";
    }
  }
  else if(isset($_GET['token']) && isset($_GET['email']) ){
    $token =$_GET['token'];
    $email  =$_GET['email'];
    $q="SELECT token,isemail from vendor where email='$email'; ";
    $q1=mysqli_query($con,$q);
    $row=mysqli_fetch_array($q1);
    if($row['isemail']==1) {
      $msg_verify="Already Verified";
    }
    else if($row['token'] == $token){
      $q="UPDATE vendor SET isemail=1 where email='$email'; ";
      $q1=mysqli_query($con,$q);
      header("location:vendors"); 
    }
    else{
      $msg_verify="Error Occured";
    }
  }
}


if(isset($_POST['log_email']) || isset($_POST['sign_email']) || isset($_POST['update_email']) ){
	

	if(isset($_POST['log_email'])){
		$log_email =$_POST['log_email'];
		$log_pass  =$_POST['log_pass'];
		$q="SELECT name,password,vendorid from vendor where email='$log_email'; ";
		$q1=mysqli_query($con,$q);
		$row=mysqli_fetch_array($q1);
		if($row['password'] == $log_pass){
			$_SESSION['log_email'] =$log_email;
      $_SESSION['log_vendorid'] =$row['vendorid'];
			$_SESSION['log_name'] =$row['name'];
			$_SESSION['log_client'] ="vendor";
			header("location:vendors");
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
		$sign_desc    =$_POST['sign_desc'];
    $sign_address =$_POST['sign_address'];
    $sign_city    =$_POST['sign_city'];
    $sign_postal  =$_POST['sign_postal'];
    $sign_category=$_POST['sign_category'];
		$q2="SELECT email from vendor where email='$sign_email' ";
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
			$q="INSERT INTO `vendor` (`name`, `password`, `email`, `phone`,`description`,`category`,`city`,`postal`, `address`,`token`,`otp`) VALUES ('$sign_name', '$sign_pass', '$sign_email', '$sign_phone','$sign_desc','$sign_category','$sign_city','$sign_postal', '$sign_address','$token','$otp');";
			$q1=mysqli_query($con,$q);
			if($q1){
				$_SESSION['log_email'] =$sign_email;
        $_SESSION['log_name'] =$sign_name;
        $_SESSION['log_client'] ="vendor";
        $q="SELECT vendorid from vendor where email='$sign_email'; ";
        $q1=mysqli_query($con,$q);
        $row=mysqli_fetch_array($q1);
        $_SESSION['log_vendorid'] =$row['vendorid'];
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
          $mail->Body ="<center><h2>BookMyShaadi<div><a style='align:centre;' href='http://localhost/builder-vending/index.php?email=$sign_email&token=$token&client=vendor'>Verify Email</a></div><center>";
          $mail->AddAddress($sign_email,$sign_name);

           if(!$mail->Send()) {
              echo "Mailer Error: " . $mail->ErrorInfo;
           } 
        require('src/textlocal.class.php');

        $textlocal = new Textlocal(false,false, 'TB2xriedCro-naHHvyJSKDxSWZCC9gKDx1kFPG8EHf');
        $numbers = array(substr($sign_phone, 1)); 
        $sender = 'TXTLCL';
        $message = " Hi ".$sign_name.". Your Verification code for BookMyShaadi is - ".$otp;

        try {
            $result = $textlocal->sendSms($numbers, $message, $sender);
            print_r($result);
        } catch (Exception $e) {
            //die('Error: ' . $e->getMessage());
        }
				header("location:vendors");	
			}
		}
	}
  else if(isset($_POST['update_email'])){
    $update_name    =$_POST['update_name'];
    $update_pass    =$_POST['update_pass'];
    $update_email   =$_POST['update_email'];
    $update_phone   =$_POST['update_phone'];
    $update_desc    =$_POST['update_desc'];
    $update_address =$_POST['update_address'];
    $update_city    =$_POST['update_city'];
    $update_postal  =$_POST['update_postal'];
    $update_category=$_POST['update_category'];
    $log_vendorid=$_SESSION['log_vendorid'];
    $q2="SELECT email from vendor where email='$update_email' AND vendorid<>'$log_vendorid'";
    $row=mysqli_query($con,$q2);
    $rowcount=mysqli_num_rows($row);
    if($rowcount>0){
      $error_msg= "email already exists";
    }
    else{
      $old_email=$_SESSION['log_email'];
      $q="SELECT phone,otp,token from vendor where email='$old_email'; ";
      $q1=mysqli_query($con,$q);
      $row=mysqli_fetch_array($q1);
      $old_phone=$row['phone'];
      $otp=$row['otp'];
      $token=$row['token'];
      $q="UPDATE `vendor` SET `name`='$update_name', `password`='$update_pass', `email`='$update_email',`phone`='$update_phone',`description`='$update_desc',`category`='$update_category',`city`='$update_city',`postal`='$update_postal', `address`='$update_address' WHERE vendorid='$log_vendorid';";
      $q1=mysqli_query($con,$q);
      if($q1){
        if ($_SESSION['log_email'] !=$update_email) {
          $q="UPDATE `vendor` SET `isemail`='0' WHERE vendorid='$log_vendorid';";
          $q1=mysqli_query($con,$q);
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
          $_SESSION['verify_link']="http://localhost/builder-vending/index.php?email=$update_email&token=$token&client=user";
          $mail->Body ="<center><h2>BookMyShaadi<div><a style='align:centre;' href='http://localhost/builder-vending/index.php?email=$update_email&token=$token&client=vendor'>Verify Email</a></div><center>";
          $mail->AddAddress($update_email,$update_name);

           if(!$mail->Send()) {
              echo "Mailer Error: " . $mail->ErrorInfo;
           }
        }
        
        if ($old_phone !=$update_phone) {
          echo "emailchanged".$old_phone."yyyyyy".$update_phone;
          $q="UPDATE `vendor` SET `isphone`='0' WHERE vendorid='$log_vendorid';";
          $q1=mysqli_query($con,$q);
            require('src/textlocal.class.php');
              $textlocal = new Textlocal(false,false, 'TB2xriedCro-naHHvyJSKDxSWZCC9gKDx1kFPG8EHf');
              $numbers = array(substr($update_phone, 1)); 
              $sender = 'TXTLCL';
              $message = " Hi ".$update_name.". Your Verification code for BookMyShaadi is - ".$otp;

              try {
                  $result = $textlocal->sendSms($numbers, $message, $sender);
                  print_r($result);
              } catch (Exception $e) {
                  //die('Error: ' . $e->getMessage());
              }
        }


        $_SESSION['log_email'] =$update_email;
        $_SESSION['log_name'] =$update_name;
        $_SESSION['log_client'] ="vendor";
        header("location:vendors");  
      }
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>BookMyShaadi</title>
	<link rel="stylesheet" type="text/css" href="css/vendors.css">
  <link rel="shortcut icon" href="images/logo.png" type="image/png">
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
    $q="SELECT isemail,isphone from vendor where email='$email'; ";
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
		<a href="index.php"><img id="logo" src="images/logo.png"  height="45px" width="300px" align="left" alt="logo"></a>
    <?php if(isset($_SESSION['log_name'])){ ?>
        <div id="vendor-link">
      <a style="cursor:default;">Welcome <?php echo $_SESSION['log_name']; ?></a>
    </div>
        <span id="logout" > 
      <a  href="logout.php" style="width:auto;" >Logout </a></span>
       <?php } ?> 
</nav>

<center><h2>FOR VENDORS</h2></center>
<div id="modal-form" >
<?php if(!isset($_SESSION['log_name'])){ ?>	
<div id="id01" class="modal">		
  		<form class="modal-content" method="POST" >
    		<img src="images/s3.jpg">
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
	      		<button type="submit" name="login" value="login">Login</button>
    		</div>
  		</form>
	</div>
  <center>
	<div id="id02" class="modal">
  		<form onsubmit="event.preventDefault(); sign_submit_form();" name="signForm" class="modal-content animate" method="POST" >
    		<div class="container">
      		  <div class="group">      
                <input type="text" name="sign_name" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Name of the Business</label>
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
                <input type="text" name="sign_city" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>City/Town</label>
              </div>
              <div class="group">      
                <input type="text" name="sign_postal" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Postal Code</label>
              </div>
              <div class="group">      
                <input type="text" name="sign_address" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Address</label>
              </div>
              <div class="group">      
                <input type="text" name="sign_desc" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Describe </label>
              </div>

              <div id="category">      
                <div class="cat-list" >
                  <u><b>Venue</b></u>
                  <div><input type="radio" name="sign_category" value="Farmhouse" required><span>Farmhouse</span></div>
                  <div><input type="radio" name="sign_category" value="Hotels"><span>Hotels</span></div>
                  <div><input type="radio" name="sign_category" value="Banquet Halls"><span>Banquet Halls</span></div>
                </div>
                <div class="cat-list">
                  <u><b>Vendors</b></u>
                  <div><input type="radio" name="sign_category" value="Catering"><span>Catering</span></div>
                  <div><input type="radio" name="sign_category" value="Wedding Invitations"><span>Wedding Invitations</span></div>
                  <div><input type="radio" name="sign_category" value="Wedding Gifts"><span>Wedding Gifts</span></div>
                  <div><input type="radio" name="sign_category" value="Wedding Photography"><span>Wedding Photography</span></div>
                  <div><input type="radio" name="sign_category" value="Wedding Music"><span>Wedding Music</span></div>
                  <div><input type="radio" name="sign_category" value="Wedding Transportation"><span>Wedding Transportation</span></div>
                  <div><input type="radio" name="sign_category" value="Tent House"><span>Tent House</span></div>
                  <div><input type="radio" name="sign_category" value="Wedding Entertainment"><span>Wedding Entertainment</span></div>
                  <div><input type="radio" name="sign_category" value="Florists"><span>Florists</span></div>
                  <div><input type="radio" name="sign_category" value="Wedding Planners"><span>Wedding Planners</span></div>
                  <div><input type="radio" name="sign_category" value="Wedding Videography"><span>Wedding Videography</span></div>
                  <div><input type="radio" name="sign_category" value="Honeymoon"><span>Honeymoon</span></div>
                  <div><input type="radio" name="sign_category" value="Wedding Decoration"><span>Wedding Decoration</span></div>
                  <div><input type="radio" name="sign_category" value="Wedding Cakes"><span>Wedding Cakes</span></div>
                  <div><input type="radio" name="sign_category" value="Wedding DJ"><span>Wedding DJ</span></div>
                  <div><input type="radio" name="sign_category" value="Pandits"><span>Pandits</span></div>
                  <div><input type="radio" name="sign_category" value="Photobooth"><span>Photobooth</span></div>
                  <div><input type="radio" name="sign_category" value="Astrologers"><span>Astrologers</span></div>
                  <div><input type="radio" name="sign_category" value="Party Lounge"><span>Party Lounge</span></div>
                  <div><input type="radio" name="sign_category" value="Wedding Choreography"><span>Wedding Choreography</span></div>                
                </div>
                <div class="cat-list">
                  <u><b>Brides</b></u>
                  <div><input type="radio" name="sign_category" value="Mehndi Design"><span>Mehndi Design</span></div>
                  <div><input type="radio" name="sign_category" value="Bridal Makeup"><span>Bridal Makeup</span></div>
                  <div><input type="radio" name="sign_category" value="Makeup Salon"><span>Makeup Salon</span></div>
                  <div><input type="radio" name="sign_category" value="Bridal Jewellery"><span>Bridal Jewellery</span></div>
                  <div><input type="radio" name="sign_category" value="Bridal Lehenga"><span>Bridal Lehenga</span></div>
                  <div><input type="radio" name="sign_category" value="Trousseau Packing"><span>Trousseau Packing</span></div>
                </div>
                <div class="cat-list">
                  <u><b>Grooms</b></u>
                  <div><input type="radio" name="sign_category" value="Sherwani"><span>Sherwani</span></div>
                </div>
              </div>

              <div id="sign_error_msg" class="error_msg"><?php if($error_msg=="email already exists") echo $error_msg; ?></div>
			    <button type="submit" name="signup" value="Sign Up">Sign Up</button>
    		</div>
		</form>
	</div>
</center>
<?php } else { 
  $log_vendorid=$_SESSION['log_vendorid'];
  $q="SELECT * from vendor  WHERE vendorid='$log_vendorid';";
    $q1=mysqli_query($con,$q);
    $row=mysqli_fetch_array($q1);



  ?>
  <center>
    <div id="displaypicture" style="background: url('uploads/<?php echo $row['displaypicture']; ?>');background-size: cover; background-position: center center;">
    </div>
    <form action="upload.php" method="post" enctype="multipart/form-data">
    <input type="text" name="displaypicture" hidden>
    <input type="file" name="fileToUpload" id="fileToUpload"><br>
    <input type="submit" value="Upload Image" name="submit">
  </form>
  <div id="id03" class="modal">
     <form name="updateForm" onsubmit="event.preventDefault(); submit_form();" name="signForm" class="modal-content animate" method="POST" >
        <div class="container">
            <div class="group">      
                <input type="text" name="update_name" value="<?php echo $row['name']; ?>" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Name of the Business</label>
              </div>

            <div class="group">      
                <input type="text" name="update_email"  value="<?php echo $row['email']; ?>" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Email</label>
              </div>

            <div class="group">      
                <input type="password" name="update_pass"  value="<?php echo $row['password']; ?>" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Password</label>
              </div>

            <div class="group">      
                <input type="text" name="update_phone"  value="<?php echo $row['phone']; ?>" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Phone</label>
              </div>
            <div class="group">      
                <input type="text" name="update_city"  value="<?php echo $row['city']; ?>" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>City/Town</label>
              </div>
              <div class="group">      
                <input type="text" name="update_postal"  value="<?php echo $row['postal']; ?>" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Postal Code</label>
              </div>
              <div class="group">      
                <input type="text" name="update_address"  value="<?php echo $row['address']; ?>" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Address</label>
              </div>
              <div class="group">      
                <input type="text" name="update_desc"  value="<?php echo $row['description']; ?>" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Describe </label>
              </div>

              <div id="category">      
                <div class="cat-list" >
                  <u><b>Venue</b></u>
                  <div><input type="radio" name="update_category" value="Farmhouse"><span>Farmhouse</span></div>
                  <div><input type="radio" name="update_category" value="Hotels"><span>Hotels</span></div>
                  <div><input type="radio" name="update_category" value="Banquet Halls"><span>Banquet Halls</span></div>
                </div>
                <div class="cat-list">
                  <u><b>Vendors</b></u>
                  <div><input type="radio" name="update_category" value="Catering"><span>Catering</span></div>
                  <div><input type="radio" name="update_category" value="Wedding Invitations"><span>Wedding Invitations</span></div>
                  <div><input type="radio" name="update_category" value="Wedding Gifts"><span>Wedding Gifts</span></div>
                  <div><input type="radio" name="update_category" value="Wedding Photography"><span>Wedding Photography</span></div>
                  <div><input type="radio" name="update_category" value="Wedding Music"><span>Wedding Music</span></div>
                  <div><input type="radio" name="update_category" value="Wedding Transportation"><span>Wedding Transportation</span></div>
                  <div><input type="radio" name="update_category" value="Tent House"><span>Tent House</span></div>
                  <div><input type="radio" name="update_category" value="Wedding Entertainment"><span>Wedding Entertainment</span></div>
                  <div><input type="radio" name="update_category" value="Florists"><span>Florists</span></div>
                  <div><input type="radio" name="update_category" value="Wedding Planners"><span>Wedding Planners</span></div>
                  <div><input type="radio" name="update_category" value="Wedding Videography"><span>Wedding Videography</span></div>
                  <div><input type="radio" name="update_category" value="Honeymoon"><span>Honeymoon</span></div>
                  <div><input type="radio" name="update_category" value="Wedding Decoration"><span>Wedding Decoration</span></div>
                  <div><input type="radio" name="update_category" value="Wedding Cakes"><span>Wedding Cakes</span></div>
                  <div><input type="radio" name="update_category" value="Wedding DJ"><span>Wedding DJ</span></div>
                  <div><input type="radio" name="update_category" value="Pandits"><span>Pandits</span></div>
                  <div><input type="radio" name="update_category" value="Photobooth"><span>Photobooth</span></div>
                  <div><input type="radio" name="update_category" value="Astrologers"><span>Astrologers</span></div>
                  <div><input type="radio" name="update_category" value="Party Lounge"><span>Party Lounge</span></div>
                  <div><input type="radio" name="update_category" value="Wedding Choreography"><span>Wedding Choreography</span></div>                
                </div>
                <div class="cat-list">
                  <u><b>Brides</b></u>
                  <div><input type="radio" name="update_category" value="Mehndi Deupdate"><span>Mehndi Deupdate</span></div>
                  <div><input type="radio" name="update_category" value="Bridal Makeup"><span>Bridal Makeup</span></div>
                  <div><input type="radio" name="update_category" value="Makeup Salon"><span>Makeup Salon</span></div>
                  <div><input type="radio" name="update_category" value="Bridal Jewellery"><span>Bridal Jewellery</span></div>
                  <div><input type="radio" name="update_category" value="Bridal Lehenga"><span>Bridal Lehenga</span></div>
                  <div><input type="radio" name="update_category" value="Trousseau Packing"><span>Trousseau Packing</span></div>
                </div>
                <div class="cat-list">
                  <u><b>Grooms</b></u>
                  <div><input type="radio" name="update_category" value="Sherwani"><span>Sherwani</span></div>
                </div>
              </div>

              <div id="update_error_msg" class="error_msg"><?php if($error_msg=="email already exists") echo $error_msg; ?></div>
          <button type="submit" name="update" value="update">update</button>
        </div>
    </form>

  </div></center>

<?php } ?>
</div>

	<?php if(isset($_SESSION['log_name'])){ 
        	if(isset($_SESSION['upload_error'])){
        		echo "<b>".$_SESSION['upload_error']."<b>";
        		$_SESSION['upload_error']='';
        	}
	?>

	<form action="upload.php" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
	</form>
	

	<div class="images-profile">
            <?php
                $dir = scandir("uploads/");
                foreach ($dir as $value) {
                    if(preg_match("/-".$_SESSION['log_vendorid']."./", $value)){
                      if(!preg_match("/displaypicture/", $value)){  ?>
                        <div onclick="view_image(this.style.background)" style="background: url('uploads/<?php echo $value; ?>');background-size: cover; background-position: center center;" class="img"></div>
                <?php } } } ?>
        </div>
      <?php }  ?>
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
<script src="js/vendors.js">
</script>
<script type="text/javascript">
  <?php if (isset($_SESSION['log_vendorid'])) { ?>
  document.querySelector('input[value="<?php echo $row['category']; ?>').checked = true;
<?php } ?>
</script>
</body>
</html>