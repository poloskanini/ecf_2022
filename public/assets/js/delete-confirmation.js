document.addEventListener(  
  "DOMContentLoaded", (function() {
	  const MODAL = document.querySelectorAll(".confirm-action");  
	  MODAL.forEach( (function(e) {  
		  e.addEventListener("click", (function(t) {  
			t.preventDefault();  
			const CONFIRM = document.querySelector("#modal-confirm");  
			CONFIRM.textContent="Confirmer";  
			CONFIRM.addEventListener("click",(function() {
				location.replace(e.getAttribute("href"));
			}));
		}));
	}));
}));