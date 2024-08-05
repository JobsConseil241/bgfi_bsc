var loader=document.getElementById("preloader");
var myVar;

function loading() {
myVar = setTimeout(showPage, 1000);
}
function showPage() {
document.getElementById("preloader").style.display = "none";
}