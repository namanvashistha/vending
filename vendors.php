<?php
include 'connection.php';
session_start();	
$error_msg="";
if(isset($_POST['login']) || isset($_POST['signup'])){
	

	if(isset($_POST['login'])){
		$log_email =$_POST['log_email'];
		$log_pass  =$_POST['log_pass'];
		$q="SELECT name,password from users where email='$log_email'; ";
		$q1=mysqli_query($con,$q);
		$row=mysqli_fetch_array($q1);
		if($row['password'] == $log_pass){
			$_SESSION['log_email'] =$log_email;
			$_SESSION['log_name'] =$row['name'];
			$_SESSION['log_client'] ="user";
			$q_ip="INSERT INTO `stats` (`ip_address`, `coordinates`,`city`,`client`,`status`) VALUES ('$ipaddress','','','$log_email','login');";
    		mysqli_query($con,$q_ip);
			header("location:home.php");
		}
		else{
			$error_msg="incorrect email or password";
		}
	}
	else if(isset($_POST['signup'])){
		$sign_name    =$_POST['sign_name'];
		$sign_pass    =$_POST['sign_pass'];
		$sign_email   =$_POST['sign_email'];
		$sign_phone   =$_POST['sign_phone'];
		$sign_address =$_POST['sign_address'];
		$q2="SELECT email from users where email='$sign_email' ";
		$row=mysqli_query($con,$q2);
		$rowcount=mysqli_num_rows($row);
		if($rowcount>0){
			$error_msg= "email already exists";
		}
		else{
			$q="INSERT INTO `users` (`name`, `password`, `email`, `phone`, `address`) VALUES ('$sign_name', '$sign_pass', '$sign_email', '$sign_phone', '$sign_address');";
			$q1=mysqli_query($con,$q);
			if($q1){
				$_SESSION['log_email'] =$sign_email;
				$_SESSION['log_name'] =$sign_name;
				$_SESSION['log_client'] ="user";
				$q_ip="INSERT INTO `stats` (`ip_address`, `coordinates`,`city`,`client`,`status`) VALUES ('$ipaddress','','','$log_email','signup');";
    			mysqli_query($con,$q_ip);
				header("location:home.php");	
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
	<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
	<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
</head>
<body>
<nav>
		<a href="index.php"><img id="logo" src="images/logo.png"  height="45px" width="300px" align="left" alt="logo"></a>
</nav>

<center><h2>FOR VENDORS</h2></center>
	
<div id="id01" class="modal">		
  		<form class="modal-content" method="POST" >
    		<img src="images/s3.jpg">
			<div class="container">
			  <div class="group">      
                <input type="text" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Email</label>
              </div>
        	  <div class="group">      
                <input type="password" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Password</label>
              </div>
              <div id="log_error_msg" class="error_msg"><?php if($error_msg=="incorrect email or password") echo $error_msg; ?></div>
	      		<button type="submit" name="login" value="login">Login</button>
    		</div>
  		</form>
	</div>

	<div id="id02" class="modal">
  		<form class="modal-content animate" method="POST" >
    		<div class="container">
      		  <div class="group">      
                <input type="text" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Email</label>
              </div>

      		  <div class="group">      
                <input type="text" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Email</label>
              </div>

      		  <div class="group">      
                <input type="text" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Email</label>
              </div>

      		  <div class="group">      
                <input type="text" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Email</label>
              </div>
      		  <div class="group">      
                <input type="text" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Email</label>
              </div>
              <div id="sign_error_msg" class="error_msg"><?php if($error_msg=="email already exists") echo $error_msg; ?></div>
			    <button type="submit" name="signup" value="Sign Up">Sign Up</button>
    		</div>
		</form>

	</div>
<center>
	<div class="containerform form__wrapper"> 
  
  <h2>Edit Client Profile</h2>
  
  <form id="myForm">

    <div class="form-row">

      <div class="form-group col-md-6">

        <label for="firstName">First Name</label>
        <input name="firstName" type="text" class="form-control" id="firstName" placeholder="First name">

      </div><!-- /form-group -->

      <div class="form-group col-md-6">

        <label for="lastName">Last Name</label>
        <input name="lastName" type="text" class="form-control" id="lastName" placeholder="Last name">

      </div><!-- /form-group -->

    </div><!-- /form-row -->

    <div class="form-group">

      <label for="company">Company</label>
      <input name="company" type="text" class="form-control" id="company" placeholder="Company">

    </div><!-- /form-group -->
    
    <div class="form-group">

      <label for="street">Street</label>
      <input name="street" type="text" class="form-control" id="street" placeholder="Street">

    </div><!-- /form-group -->
 
    <div class="form-row">

      <div class="form-group col-md-6">

        <label for="city">City</label>
        <input name="city" type="text" class="form-control" id="city" placeholder="City">

      </div><!-- /form-group -->

      <div class="form-group col-md-4">

        <label for="state">State</label>
        <input name="state" type="text" class="form-control" id="state" placeholder="State">

      </div><!-- /form-group -->

      <div class="form-group col-md-2">

        <label for="zipCode">Zip Code</label>
        <input name="zipCode" type="text" class="form-control" id="zipCode" placeholder="Zip code">

      </div><!-- /form-group -->

    </div><!-- /form-row -->

    <div class="form-row">

      <div class="form-group col-md-6">

        <label for="homeNumber">Home Number</label>
        <input name="homeNumber" type="tel" class="form-control" id="homeNumber" placeholder="Home number">

      </div><!-- /form-group -->

      <div class="form-group col-md-6">

        <label for="faxNumber">Fax Number</label>
        <input name="faxNumber" type="tel" class="form-control" id="faxNumber" placeholder="Fax number">

      </div><!-- /form-group -->

    </div><!-- /form-row -->

    <div class="form-row form__comments">

      <div id="form__comments-by" class="form-group col-md-6"></div>
      <div id="form__comments-txt" class="form-group col-md-6"></div>

    </div><!-- /form-row -->

    <div class="form__btns">

      <button id="cancel" class="btn btn-outline-primary">Cancel</button>
      <button id="save" class="btn btn-primary">Save</button>

    </div><!-- /form__btns -->

  </form>

</div><!-- /container -->  


</center>



	<?php 
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
	

	<div id="img-grid">
		<?php 
		$dir = scandir("uploads/");
		foreach ($dir as $value) {
			if($value != "." && $value != ".."){
		?>
		<div><img src="uploads/<?php echo $value; ?>"></div>
		<?php } } ?>		 
		</div>
<footer>footer</footer>
<script src="js/vendor.js"></script>
</body>
</html>