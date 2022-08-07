// Class Hovered on navigation li
const list = document.querySelectorAll(".navigation li");

function activeLink() {
  list.forEach((item) => {
    item.classList.remove("hovered");
  });
  this.classList.add("hovered");
}

list.forEach((item) => item.addEventListener("mouseover", activeLink));

// Toggle menu
const toggle = document.querySelector(".toggle");
const navigation = document.querySelector(".navigation");
const main = document.querySelector("main");

toggle.onclick = function() {
  toggle.classList.toggle("active");
  navigation.classList.toggle("active");
  main.classList.toggle("active");
}

// Login-Logout Menu
const profile = document.querySelector('.profile');
const loginMenu = document.querySelector(".menu");

profile.onclick = function() {
  loginMenu.classList.toggle("active");
}
