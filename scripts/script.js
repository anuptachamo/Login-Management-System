
const container = document.querySelector(".container"),
  signUp = document.querySelector(".signup-link"),
  login = document.querySelector(".login-link");

  
// JS code to to switch between signup and login form
signUp.addEventListener("click", () => {
    container.classList.add("active");
  });
  login.addEventListener("click", () => {
    container.classList.remove("active");
  });

// ---------------------------------------------------------------------------------------------------

//Check password policies when entering value on password field
var x = document.getElementById("passField");
x.addEventListener("keyup", function () {
  validate(x.value);
});

//appearing of password policies box when clicked on Password field
x.addEventListener("focus", function () {
  RemoveClass("password-policies", "hide");
});
//disappearing of password policies box when clicked elsewhere
x.addEventListener("blur", function () {
  AddClass("password-policies", "hide");
});

function validate(pswd) {
  if (pswd.length >= 8) {
    valid("length");
  } else {
    invalid("length");
  }

  if (pswd.match(/[0-9]/)) {
    valid("number");
  } else {
    invalid("number");
  }

  if (pswd.match(/[A-Z]/)) {
    valid("upperCase"); 
  } else {
    invalid("upperCase");
  }

  if (pswd.match(/[a-z]/)) {
    valid("lowerCase"); 
  } else {
    invalid("lowerCase");
  }

  if (pswd.match(/[!\@\#\$\%\^\&\*\(\)\_\-\+\=\?\>\<\.\,]/)) {
    valid("specialCharacter");
  } else {
    invalid("specialCharacter");
  }
}

// JS functions to change icons and color of password policies
function valid(id) {
  AddClass(id, "valid");
  RemoveClass(id, "invalid");
  AddClassOnIcon(id, "fa-check");
  RemoveClassOnIcon(id, "fa-times");
}
function invalid(id) {
  AddClass(id, "invalid");
  RemoveClass(id, "valid");
  AddClassOnIcon(id, "fa-times");
  RemoveClassOnIcon(id, "fa-check");
}

function AddClass(id, cl) {
  document.getElementById(id).classList.add(cl);
}
function RemoveClass(id, cl) {
  document.getElementById(id).classList.remove(cl);
}
function AddClassOnIcon(id, cl) {
  document.getElementById(id).firstElementChild.classList.add(cl);
}
function RemoveClassOnIcon(id, cl) {
  document.getElementById(id).firstElementChild.classList.remove(cl);
}
// ---------------------------------------------------------------------------------------------------


// Toggle for Registration's Password field
document.querySelector("form .pw-display-toggle-btn").addEventListener("click",function(){
  let el = document.querySelector("form .pw-display-toggle-btn");
  if(el.classList.contains("active")){
    document.querySelector("form #passField").setAttribute("type","password");
    el.classList.remove("active");
  } else {
    document.querySelector("form #passField").setAttribute("type","text");
    el.classList.add("active");
  }
});

// Toggle for Registration's Confirm Password field
document.querySelector("form .pw-display-toggle-btn-cpass").addEventListener("click",function(){
  let el1 = document.querySelector("form .pw-display-toggle-btn-cpass");
  if(el1.classList.contains("active")){
    document.querySelector("form #confPassword").setAttribute("type","password");
    el1.classList.remove("active");
  } else {
    document.querySelector("form #confPassword").setAttribute("type","text");
    el1.classList.add("active");
  }
});

// Toggle for Login's Password field
document.querySelector("form .pw-display-toggle-btn-loginpass").addEventListener("click",function(){
  let el2 = document.querySelector("form .pw-display-toggle-btn-loginpass");
  if(el2.classList.contains("active")){
    document.querySelector("form #loginPassword").setAttribute("type","password");
    el2.classList.remove("active");
  } else {
    document.querySelector("form #loginPassword").setAttribute("type","text");
    el2.classList.add("active");
  }
});

// ---------------------------------------------------------------------------------------------------

// Password strength indicator
function getPasswordStrength(password){
  let s = 0;
  if(password.length >= 8){
    s++;
  }
  if(/[!\@\#\$\%\^\&\*\(\)\_\-\+\=\?\>\<\.\,]/.test(password)){
    s++;
  }
  if(/[A-Z]/.test(password)){
    s++;
  }
  if(/[0-9]/.test(password)){
    s++;
  }
  if(/[^A-Za-z0-9]/.test(password)){
    s++;
  }
  return s;
}

document.querySelector("form #passField").addEventListener("focus",function(){
  document.querySelector("form .pw-strength").style.display = "block";
});

document.querySelector("form #passField").addEventListener("keyup",function(e){
  let password = e.target.value;
  let strength = getPasswordStrength(password);
  let passwordStrengthSpans = document.querySelectorAll("form .pw-strength span");
  strength = Math.max(strength,1);
  passwordStrengthSpans[1].style.width = strength*20 + "%";
  if(strength < 2){
    passwordStrengthSpans[0].innerText = "Weak";
    passwordStrengthSpans[0].style.color = "#111";
    passwordStrengthSpans[1].style.background = "#d13636";
  } else if(strength >= 2 && strength <= 4){
    passwordStrengthSpans[0].innerText = "Medium";
    passwordStrengthSpans[0].style.color = "#111";
    passwordStrengthSpans[1].style.background = "#e6da44";
  } else {
    passwordStrengthSpans[0].innerText = "Strong";
    passwordStrengthSpans[0].style.color = "#fff";
    passwordStrengthSpans[1].style.background = "#20a820";
  }
});

// close password strength indicator when clicked elsewhere
document.querySelector("form #passField").addEventListener("blur",function(){
  document.querySelector("form .pw-strength").style.display = "none";
});
