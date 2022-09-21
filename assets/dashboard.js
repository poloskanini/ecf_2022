// Class Hovered on navigation li
const list = document.querySelectorAll(".navigation li");

function activeLink() {
  list.forEach((item) => {
    item.classList.remove("hovered");
  });
  this.classList.add("hovered");
}

list.forEach((item) => item.addEventListener("mouseover", activeLink));

//OnClick Function pour ajouter la closse hovered à mon lien cliqué
function activatedLink() {
  list.forEach((item) => {
    item.classList.add("hovered");
  })
}
list.forEach((item) => item.addEventListener("onclick", activatedLink));


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

// Modale
const modaleContainer = document.querySelector(".modale-container");
const modaleTriggers = document.querySelectorAll(".modale-trigger");

modaleTriggers.forEach(trigger => trigger.addEventListener("click", toggleModale));

function toggleModale() {
  modaleContainer.classList.toggle("active");
}
