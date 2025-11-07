const params = new URLSearchParams(window.location.search);
const doctorid = params.get("doctorid");
const patientid = params.get("patientid");

let patient;

document.addEventListener("DOMContentLoaded", () => {
  phpDisplayAll(patientid);
  const addButton = document.querySelector("#add");
  addButton.addEventListener("click", () => {
      window.location.href = `prescriptionitem_manager.html?prescriptionid=${prescription.prescriptionid}`;
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

     const infoWrapper = document.createElement("div");
     container.className = "infoWrapper";

     const date = document.createElement("span");
     date.textContent = `Issued on ${prescription.dateprescribed}`;

     const email = document.createElement("span");
     email.textContent = prescription.email;

     const contactnum = document.createElement("span");
     contactnum.textContent = prescription.contactnum;

     const manageButton = document.createElement("button");
     manageButton.textContent = "View";

     manageButton.addEventListener("click", () => {
      window.location.href = `prescriptionitem_manager.html?prescriptionid=${prescription.prescriptionid}`;
     });

     infoWrapper.appendChild(date);
     infoWrapper.appendChild(email);
     infoWrapper.appendChild(contactnum);

     container.appendChild(infoWrapper);
     container.appendChild(manageButton);

     parent.appendChild(container);

  });
}