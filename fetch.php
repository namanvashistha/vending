<?php
	include 'connection.php';
	session_start();	
  $lim =$_GET['lim'];
	$location=$_GET['l'];
	$category=$_GET['c'];
  $_SESSION['lim']=$lim;
    $q="SELECT * FROM `vendor` where ";
   if($location!=''){
    	$q.="city like '%$location%' AND ";
    }
    else{
    	$q.="1 AND ";
    }
    if ($category!='') {
    	$q.="category like '$category%'";
    }
    else{
    	$q.="1";
    }
    $row_count=mysqli_query($con,$q);
    $rowcount=mysqli_num_rows($row_count);
    if ($rowcount<=$lim) {
      $lim=$rowcount-3;
    }
    $q.=" LIMIT 9 OFFSET $lim";
    $q1_fetch=mysqli_query($con,$q);
?>


	<ul class="cards">
      <?php
      while($row_fetch=mysqli_fetch_array($q1_fetch)){ ?>
        <li class="cards__item">
          <a class="vendor-info-link"  href="vendor/<?php echo $row_fetch['vendorid'];  ?>" >
            <div class="card">
              <div style="background-image: url(uploads/<?php echo $row_fetch['displaypicture'];  ?>);" class="card__image"></div>
                <div class="card__content">
                  <div class="card__title"><?php echo $row_fetch['name'];  ?></div><span><b><?php  echo $row_fetch['city'];  ?></b></span>
                    <p class="card__text"><?php echo $row_fetch['address']."<br>".$row_fetch['description'];  ?></p>
                </div>
            </div>
          </a>
        </li>
      <?php } ?>
      </ul>