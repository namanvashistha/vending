<?php
include 'connection.php';
session_start();

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

	<nav>
		<img id="logo" src="../images/logo.png"  height="45px" width="300px" align="left" alt="logo">
    <?php if(!isset($_SESSION['log_name'])){ ?>
    <div id="vendor-link">
      <a href="vendor.php">Are you a vendor?</a>
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
<div>body<a onclick="document.getElementById('id03').style.display='block'" style="" >Register </a></div>

<footer>footer</footer>


<div id="id03" class="modal-vendor">   
      <div class="modal-vendor-content animate" >
        
        <div class="imgcontainer">
            <span onclick="document.getElementById('id03').style.display='none'" class="close" title="Close Modal">&times;</span>
        </div>
        
      <div class="container">
        </div>
      </div>
  </div>
  <script src="../js/vendor_info.js" ></script>
<script>
function showvendorinfo(str) {
  document.getElementById('id03').style.display='block';
  $(document).ready(function(){
    str="fetch.php?vendor="+str;
    $('.container').load("README.md").fadeIn("slow");
      $.ajax({
                    url:"fetch.php",
                    method:"GET",
                    dataType:"text",
                    success:function(data){
                       $('.container').load(str).fadeIn("slow");
                }
            });
    });
}
  /*setInterval(function(){
    $('#msg-box').load("fetch_msg.php").fadeIn("slow");
  },1000);
});*/
  </script>
</body>
</html>