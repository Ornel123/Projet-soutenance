let ues = [];
let classesToSelect = [];
let allClasses = [];
const filieresSelect = document.getElementById('filiere');
const classesSelect = document.getElementById('classe');

filieresSelect.addEventListener('change', function (event) {
    classesToSelect = allClasses.filter(elt => (parseInt(elt.filiere_id) === parseInt(event.target.value)));
    let result = '';
    classesSelect.value = null;
    classesToSelect.forEach((classe) => {
        result += `<option value='${classe.id}'>${classe.code}</option>`;
    });

    classesSelect.innerHTML = result;
});

function CalculMoyenne() {
    //showImportLoader();
    let classe_id = 0;
    let filliere_id = 0;
    const filliereSelector = document.getElementById('filiere');
    const classSelector = document.getElementById('classe');
    filliere_id = filliereSelector.selectedOptions[0].innerHTML;
    classe_id = classSelector.selectedOptions[0].value;
    console.log(filliere_id);
    console.log(classe_id);
    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        console.log(this);
        if (this.readyState === 4) {
            if (this.status >= 200 && this.status < 300) {
                const result = this.responseText;
                //showSuccessToast('Les notes ont été importées avec succès !');
                //notesList = [];
                //setNotesListTableContent();
                console.log(JSON.parse(result));
                let arr = JSON.parse(result);
                let v = 1;
                document.getElementById("calculmoyenne-result").innerHTML = " ";
                arr.forEach(element => {
                    document.getElementById("calculmoyenne-result").innerHTML += "<tr><td>" + v + "</td><td>" + element.matricule + "</td><td>" + element.noms + "</td><td>" + element.moyenne1.toFixed(2) + "</td><td>" + element.moyenne2.toFixed(2) + "</td><td>" +( element.moyenne1 + element.moyenne2).toFixed(2) + "</td><td>" + (( element.moyenne1 + element.moyenne2)/10).toFixed(2) + "</td><td>" + (( element.moyenne1 + element.moyenne2)/2).toFixed(2) + "</td><td>" + element.mention + "</td></tr>";
                    v++;
                });
            }
            else {
                showErrorToast('Une erreur s\'est produite lors de l\'importation des notes ! Veuillez réessayer !');
                const result = this.responseText;
                console.log(result);
            }
            //hideImportLoader();
        }
    }

    xmlhttp.open('POST', '/api/CalculMoyenne', true);
    xmlhttp.setRequestHeader("Content-Type", "application/json");
    xmlhttp.send(JSON.stringify({ classe_id: classe_id, filliere_id: filliere_id }));
    console.log({ classe_id: classe_id, filliere_id: filliere_id });
}