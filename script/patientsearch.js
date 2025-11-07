document.addEventListener("DOMContentLoaded", () => {
  phpOnLoad(41); //Simulate Doctor Id. 41
});

function phpOnLoad(doctorid){
  fetch("includes/patient_manager.php?action=getPatients&doctorid=" + doctorid)
  .then(response => response.json())
  .then(data => {
      generateSearchResults(data);
  }).catch(error => console.error(error));
}

function phpSearch(doctorid, patientName){
  fetch("includes/patient_manager.php?action=getPatientByName&doctorid=" + doctorid + "&patientname=" + patientName)
  .then(response => response.json())
  .then(data => {
      generateSearchResults(data);
  }).catch(error => console.error(error));
}

function generateSearchResults(patients){
  const parent = document.getElementById("searchresults-container");

  while (parent.children.length > 1) {
    parent.removeChild(parent.lastElementChild); //clear the list
  }

  patients.forEach(patient => {

     const container = document.createElement("div");

     const name = document.createElement("span");
     name.textContent = patient.firstname;

     const contactinfo = document.createElement("span");
     contactinfo.textContent = patient.email;

     const lastactivity = document.createElement("span");
     lastactivity.textContent = patient.dateprescribed;

     container.appendChild(name);
     container.appendChild(contactinfo);
     container.appendChild(lastactivity);

     parent.appendChild(container);
  });
}

const button = document.getElementById("add");
button.addEventListener("click", () => {
   phpSearch(41, "Juan");
});