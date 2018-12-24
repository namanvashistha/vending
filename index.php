<?php
include 'connection.php';
session_start();	
$error_msg="";
if(isset($_POST['log_email']) || isset($_POST['sign_email'])){


	if(isset($_POST['log_email'])){
		$log_email =$_POST['log_email'];
		$log_pass  =$_POST['log_pass'];
    $q="SELECT name,password from user where email='$log_email'; ";
		$q1=mysqli_query($con,$q);
		$row=mysqli_fetch_array($q1);
		if($row['password'] == $log_pass){
			$_SESSION['log_email'] =$log_email;
			$_SESSION['log_name'] =$row['name'];
			$_SESSION['log_client'] ="user";
      header("location:index.php");
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
        include_once "PHPMailer/PHPMailer.php";

                $mail = new PHPMailer();
                $mail->setFrom('hello@codingpassiveincome.com');
                $mail->addAddress($email, $name);
                $mail->Subject = "Please verify email!";
                $mail->isHTML(true);
                $mail->Body = "
                    Please click on the link below:<br><br>
                    
                    <a href='http://codingpassiveincome.com/PHPEmailConfirmation/confirm.php?email=$email&token=$token'>Click Here</a>
                ";

                if ($mail->send())
                    $msg = "You have been registered! Please verify your email!";
                else
                    $msg = "Something wrong happened! Please try again!";
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
	<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>

</head>
<body>
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
	<div>
  <?php if(!isset($_SESSION['log_name'])){ ?>
	<div id="front">
		<div id="slideshow">
   			<div>
     			<img class="slide" src="http://localhost/builder-vending/images/s1.jpg">
   			</div>
   			<div>
     			<img class="slide" src="http://localhost/builder-vending/images/s2.jpg">
   			</div>
   			<div>
     			<img class="slide" src="http://localhost/builder-vending/images/s3.jpg">
   			</div>	
		</div> 
		<div id="search">
			<form class="search-container">
    		<input type="text" class="search-bar" placeholder="Search for">
    		<input type="text" class="search-bar" placeholder="Locate">
  			</form>
  			<div onclick="con()" class="search-icon" >Search</div>
		</div>
	</div><?php } else { ?>
  <div id="search-space">
    <div id="home-search">
      <form class="home-search-container">
        <input list="datalist" type="search" class="home-search-bar" placeholder="Search for">
        <input list="datalist" type="Search" class="home-search-bar" placeholder="Locate">
        <datalist id="datalist">
        <option value="Delhi">
        <option value="Mumbai">
        <option value="Noida">
      </datalist>
      </form>
        <div onclick="con()" class="home-search-icon" >Search</div>
    </div>
  </div>
    <div id="vendors">
      <div id="filters">
        <div id="filter-applied">
          red;
        </div>
        <div class="filter-box">
            <label>
      datalist<br>
      <input list="datalist">
      <datalist id="datalist">
        <option value="Delhi">
        <option value="Mumbai">
        <option value="Noida">
      </datalist>
      <option value="Delhi">
        <option value="Mumbai">Mumbai
        <option value="Noida">
    </label>

        </div>
        <div class="filter-box">
            category
        </div>
        <div class="filter-box">
          type of service
        </div>
      </div>
		<div id="ven-cards">
			<ul class="cards">
      <?php
      $q="SELECT * FROM `restaurants`; ";
      $q1=mysqli_query($con,$q);

      while($row=mysqli_fetch_array($q1)){ ?>
        <li class="cards__item">
          <a class="vendor-info-link"  href="vendor/<?php echo $row['name'];  ?>" >
            <div class="card">
              <div style="background-image: url(images/s<?php  echo mt_rand(1,3);?>.jpg);" class="card__image"></div>
                <div class="card__content">
                  <div class="card__title"><?php echo $row['name'];  ?></div><span><b><?php  echo ($row['status']=="Online")?"<font color='green'>Online</font>":"<b>Offline</b>";  ?></b></span>
                    <p class="card__text"><?php echo $row['address']."<br>".$row['description'];  ?></p>
                </div>
            </div>
          </a>
        </li>
      <?php } ?>
      </ul>
    </div>

  <?php } ?>
	</div>
	<footer>footer</footer>
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
  		<form name="signForm"  onsubmit="event.preventDefault(); submit_form();"  onsubmit="return validateForm()" class="modal-content animate" method="POST" >
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
                <input type="text" name="sign_phone" required>
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
  
	<script src="js/index.js" ></script>
</body>
</html>



