const params = new URLSearchParams(window.location.search);
const prescriptionid = params.get("prescriptionid");

document.addEventListener("DOMContentLoaded", () => {
  phpDisplayAll(prescriptionid);
});

document.getElementById("exit").addEventListener("click", () => {
  const popup = document.getElementsByClassName("view-wrapper");
  popup[0].style.visibility = "hidden";
})

function phpDisplayAll(prescriptionid){
  fetch("includes/prescriptionitem_manager.php?action=getPrescriptionItems&prescriptionid=" + prescriptionid)
  .then(response => response.json())
  .then(data => {
      generateSearchResults(data);
  }).catch(error => console.error(error));
}

function searchItem(prescriptionid, prescriptionname){
  fetch("includes/prescriptionitem_manager.php?action=getPrescriptionItemsByName&prescriptionid=" + prescriptionid + "&prescriptionname=" + prescriptionname)
  .then(response => response.json())
  .then(data => {
      generateSearchResults(data);
  }).catch(error => console.error(error));
}

function generateSearchResults(pitems){
  const parent = document.getElementById("searchresults-container");

  while (parent.children.length > 1) {
    parent.removeChild(parent.lastElementChild); //clear the list
  }

  pitems.forEach(pitem => {

     const container = document.createElement("div");
     container.className = "card";
     if(pitem.quantity == 0){
     	container.style.backgroundColor = "#F9E1E1";
     }

     const medicinename = document.createElement("span");
     medicinename.textContent = pitem.name;

     const brand = document.createElement("span");
     brand.textContent = pitem.brand;

     const quantity = document.createElement("span");
     quantity.textContent = pitem.quantity;

     const dosage = document.createElement("span");
     dosage.textContent = pitem.dosage;

     const manageButton = document.createElement("button");
     manageButton.textContent = "View";
     manageButton.setAttribute("id", "manageButton");

     manageButton.addEventListener("click", function(event) {
        document.getElementById('name-value').textContent = pitem.name;
        document.getElementById('brand-value').textContent = pitem.brand;
        document.getElementById('qty-value').textContent = pitem.quantity;
        document.getElementById('dosage-value').textContent = pitem.dosage;
        document.getElementById('frequency-value').textContent = pitem.frequency + " time(s) a day";
        document.getElementById('substitutions-value').textContent = (pitem.substitutions == 1) ? "Allowed" : "Not Allowed";
        document.getElementById('description-value').textContent = pitem.description;

        const popup = document.getElementsByClassName("view-wrapper");
	    let rect = event.target.getBoundingClientRect();


	    popup[0].style.left = (rect.left * .7) + window.scrollX + "px";
	    popup[0].style.top = (rect.bottom * .9) + window.scrollY + "px";

	    popup[0].style.visibility = "visible";
     });

     container.appendChild(medicinename);
     container.appendChild(brand);
     container.appendChild(quantity);
     container.appendChild(dosage);
	 container.appendChild(manageButton);

     parent.appendChild(container);

  });
}

const input = document.querySelector("input[name='search']");
input.addEventListener("keydown", function (event) {
    if (event.key === "Enter") {
      let val = input.value.trim();
      if(val.length == 0){
        phpDisplayAll(prescriptionid);
      }else{        
        searchItem(prescriptionid, val);
      }
    }
});