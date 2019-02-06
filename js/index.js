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




function submit_form() {
    console.log(document.forms["signForm"]["sign_email"].value);
    var phoneno = /^\+\d{12}$/;
    var email = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    var password=/^[A-Za-z]\w{7,14}$/;
    if (document.forms["signForm"]["sign_email"].value == "") {
        document.getElementById('sign_error_msg').innerHTML="Name must be filled out";
        
    }
    else if(!document.forms["signForm"]["sign_email"].value.match(email)) {
        document.getElementById('sign_error_msg').innerHTML="invalid email";
    }
    else if(!document.forms["signForm"]["sign_pass"].value.match(password)) {
        alert("password must be between 7 to 16 characters which contain only characters, numeric digits, underscore and first character must be a letter");
    }
    else if(!document.forms["signForm"]["sign_phone"].value.match(phoneno)) {
        alert("invalid phone");
    }
    else {
        console.log('valid ');
        document.forms['signForm'].submit();
    }
}