document.addEventListener("DOMContentLoaded", () => {
  phpDisplayAll(41); //Simulate Doctor Id. 41
});

function phpDisplayAll(doctorid){
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

     const manageButton = document.createElement("button");
     manageButton.textContent = "View Prescriptions";

     manageButton.addEventListener("click", () => {
      window.location.href = `prescription_manager.html?patientid=${patient.userid}`;
     });

     container.appendChild(name);
     container.appendChild(contactinfo);
     container.appendChild(lastactivity);
     container.appendChild(manageButton);

     parent.appendChild(container);

  });
}

const input = document.querySelector("input[name='search']");
input.addEventListener("keydown", function (event) {
    if (event.key === "Enter") {
      let val = input.value.trim();
      if(val.length == 0){
        phpDisplayAll(41);
      }else{        
        phpSearch(41, val);
      }
    }
});