const params = new URLSearchParams(window.location.search);
const doctorid = params.get("doctorid");
const patientid = params.get("patientid");

document.addEventListener("DOMContentLoaded", () => {
  phpDisplayAll(patientid);
  const addButton = document.querySelector("#add");
  addButton.addEventListener("click", () => {
      window.location.href = `prescriptionitem_manager.html?prescriptionid=${prescription.prescriptionid}&patientid=${patientid}`;
  });
});

function phpDisplayAll(patientid){
  fetch("includes/prescription_manager.php?action=getPrescriptions&patientid=" + patientid)
  .then(response => response.json())
  .then(data => {
      generateSearchResults(data);
  }).catch(error => console.error(error));
}

function generateSearchResults(prescriptions){
  const parent = document.getElementById("searchresults-container");

  while (parent.children.length > 1) {
    parent.removeChild(parent.lastElementChild); //clear the list
  }

  prescriptions.forEach(prescription => {
     const container = document.createElement("div");
     container.className = "card";
     container.style.display = "flex";
     container.style.flexDirection = "row"
     container.style.justifyContent = "space-between";

     const infoWrapper = document.createElement("div");
     infoWrapper.style.display = "flex";
     infoWrapper.style.flexDirection = "column"

     const date = document.createElement("span");
     date.textContent = `Issued on ${prescription.dateprescribed}`;
     date.style.fontWeight = "750";

     const email = document.createElement("span");
     email.textContent = prescription.email;
     email.style.fontSize = "13px";

     const contactnum = document.createElement("span");
     contactnum.textContent = "+" + prescription.contactnum;
     contactnum.style.fontSize = "13px";

     const manageButton = document.createElement("button");
     manageButton.textContent = "View";
     manageButton.setAttribute("id", "manageButton");

     manageButton.addEventListener("click", () => {
      window.location.href = `prescriptionitem_manager.html?patientid&=${prescription.patientid}prescriptionid=${prescription.prescriptionid}`;
     });

     infoWrapper.appendChild(date);
     infoWrapper.appendChild(email);
     infoWrapper.appendChild(contactnum);

     container.appendChild(infoWrapper);
     container.appendChild(manageButton);

     parent.appendChild(container);

  });
}