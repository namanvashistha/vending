var log_error=document.getElementById("log_error_msg").innerHTML ;
var sign_error = document.getElementById("sign_error_msg").innerHTML;

if (log_error=="incorrect email or password") {
	var log_pop = document.getElementById('id01');
	log_pop.style.display="block";
}   

if (sign_error=="email already exists") {
	var sign_pop = document.getElementById('id02');
	sign_pop.style.display="block";
}

$("#slideshow > div:gt(0)").hide();

setInterval(function() { 
  $('#slideshow > div:first')
    .fadeOut(2000)
    .next()
    .fadeIn(3000)
    .end()
    .appendTo('#slideshow');
},  5000);