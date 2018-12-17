<?php
include 'connection.php';
session_start();	
$error_msg="";
if(isset($_POST['login']) || isset($_POST['signup'])){
	session_start();

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
		<span >	
  		<a onclick="document.getElementById('id02').style.display='block'" style="width:auto;" >Register </a>
  		<a onclick="document.getElementById('id01').style.display='block'" style="width:auto;">Login</a></span>
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
    <div id="home-search">
      <form class="home-search-container">
        <input type="text" class="home-search-bar" placeholder="Search for">
      </form>
        <div onclick="con()" class="home-search-icon" >Search</div>
    </div>
    <div id="vendors">
      <div id="filters">
        filters
      </div>
		<div id="ven-cards">
			<ul class="cards">
      <?php
      $q="SELECT * FROM `restaurants`; ";
      $q1=mysqli_query($con,$q);

      while($row=mysqli_fetch_array($q1)){ ?>
        <li class="cards__item">
          <a onclick="document.getElementById('id01').style.display='block'">
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
  		<form class="modal-content animate" method="POST" >
  			
    		<div class="imgcontainer">
      			<span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
    		</div>
    		<img src="images/frame2.jpg">
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
    		<div class="imgcontainer">
      			<span onclick="document.getElementById('id02').style.display='none'" class="close" title="Close Modal">&times;</span>
    		</div>
    		<img src="images/frame2.jpg">
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
	<script src="js/index.js" ></script>
</body>
</html>