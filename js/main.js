
function myFunction(password_name) {
var x = document.getElementById(password_name);
if (x.type === "password") {
    x.type = "text";
} else {
    x.type = "password";
}
}