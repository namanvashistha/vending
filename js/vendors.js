function view_image(url){
	console.log(url);
	document.getElementById('id03').style.display='block';
	document.getElementById('full-image').style.background=url;
}

(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v3.2';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));


function sign_submit_form() {
    var phoneno = /^\+\d{12}$/;
    var email = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    var password=/^[A-Za-z]\w{7,14}$/;
    var postal=/^\d{6}$/;
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
    else if(!document.forms["signForm"]["sign_postal"].value.match(postal)) {
        alert("invalid postal code");
    }
    else {
        document.forms['signForm'].submit();
    }
}
function update_submit_form() {    var phoneno = /^\+\d{12}$/;
    var email = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    var password=/^[A-Za-z]\w{7,14}$/;
    var postal=/^\d{6}$/;
    if (document.forms["updateForm"]["update_email"].value == "") {
        document.getElementById('update_error_msg').innerHTML="Name must be filled out";
        
    }
    else if(!document.forms["updateForm"]["update_email"].value.match(email)) {
        document.getElementById('update_error_msg').innerHTML="invalid email";
    }
    else if(!document.forms["updateForm"]["update_pass"].value.match(password)) {
        alert("password must be between 7 to 16 characters which contain only characters, numeric digits, underscore and first character must be a letter");
    }
    else if(!document.forms["updateForm"]["update_phone"].value.match(phoneno)) {
        alert("invalid phone");
    }
    else if(!document.forms["updateForm"]["update_postal"].value.match(postal)) {
        alert("invalid postal code");
    }
    else {
        document.forms['updateForm'].submit();
    }
}