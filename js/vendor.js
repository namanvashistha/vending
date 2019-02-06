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

function view_image(url){
	document.getElementById('id03').style.display='block';
	document.getElementById('full-image').style.background=url;
}

function submit_form() {
  if (document.forms["signForm"]["sign_email"].value == "naman") {
        document.getElementById('sign_error_msg').innerHTML="Name must be filled out";
    }
    else {
        
        document.forms['signForm'].submit();
    }
}

(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v3.2';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));