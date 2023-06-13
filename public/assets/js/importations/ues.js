const dropArea = document.querySelector("#drag-area"),
    dragText = dropArea.querySelector("#drag-header-text"),
    browseButton = dropArea.querySelector("#browse-button"),
    uploadInput = dropArea.querySelector("#upload-input");

const progressElt = document.getElementById("progress");
const progressComponent = document.getElementById("progress-component");

const importedFileNameElt = document.getElementById("imported-file-name");
const fileNameElt = document.getElementById("file-name");

const ueFormElt = document.getElementById("ue-form");

const errorDiv = document.getElementById("required-error");
const errorTextSpan = document.getElementById("error-text");

const uesResultElt= document.getElementById("ues-result");
const storedUesResultElt= document.getElementById("stored-ues-result");

const importButton = document.getElementById('import-button');
const importLoaderElt = document.getElementById('import-loader');

const summaryContainer = document.getElementById('summary-container');
const importContainer = document.getElementById('import-container');
const storedDataContainer = document.getElementById('stored-data-container');
const summaryWithDataContainer = document.getElementById('has-data');
const summaryWithoutDataContainer = document.getElementById('has-not-data');
const loadingContainer = document.getElementById('loading-container');
const pageLoaderContainer = document.getElementById('page-loader');
const loadingHasFailedContainer = document.getElementById('loading-has-failed');

let uesList = [];
let storedUesList = [];

let file = null;
let progress = 0;

let paginationData = {
    currentPage: 0,
    totalPages: 0,
    totalItems: 0,
    from: 0,
    to: 0,
    itemsPerPage: 0
}

setUesListTableContent();

browseButton.onclick = ()=>{
    uploadInput.click();
}

uploadInput.addEventListener("change", function(event){
    if(isValidFileType(this.files[0]))
    {
        file = this.files[0];
        uploadFileSimulator(file);
    }
});

dropArea.addEventListener("dragover", (event)=>{
    event.preventDefault();
    dropArea.classList.add("active");
    dragText.textContent = "Relâchez pour charger";
});


dropArea.addEventListener("dragleave", ()=>{
    dropArea.classList.remove("active");
    dragText.textContent = "Glissez et déposez votre fichier ici";
});


dropArea.addEventListener("drop", (event)=>{
    event.preventDefault();
    if(isValidFileType(event.dataTransfer.files[0]))
    {
        file = event.dataTransfer.files[0];
        uploadFileSimulator(file);
    }
});

function isValidFileType(file)
{
    let validExtensions = [".csv", "text/csv"];
    let fileType = file?.type;
    let result = false;
    if(validExtensions.includes(fileType)){
        result = true;
    }
    else{
        showErrorToast("Uniquement les fichiers .csv sont permis !");
        result = false;
    }

    dropArea.classList.remove("active");
    dragText.textContent = "Glissez et déposez votre fichier ici";

    return result;
}

function uploadFileSimulator(file) {
    progress = 0;
    setTimeout(() => {
        if (!file) {
            return;
        } else {
            showProgress(0);
            showImportedFileNameElt(file.name);
            const progressInterval = setInterval(() => {
                if (progress === 100) {
                    clearInterval(progressInterval);
                    hideProgress();
                    readFile();
                } else {
                    progress += 5;
                    showProgress(progress);
                }
            }, 200);
        }
    }, 1000);
}

function showProgress(progress)
{
    progressElt.style.width = (450 * progress/100)+"px";
    progressComponent.style.display = "block";
}

function hideProgress(){
    progress = 0;
    progressComponent.style.display = "none";
    hideImportedFileNameElt();
}

function hideImportedFileNameElt(){
    importedFileNameElt.style.display = "none";
}

function showImportedFileNameElt(filename){
    importedFileNameElt.style.display = "block";
    fileNameElt.textContent = filename;
}

function readFile(){

    let fileReader = new FileReader();

    fileReader.onloadend = function(){
        let url = this.result;
        console.log(url);

        const xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function(){
            if(this.readyState === 4 && this.status === 200)
            {
                const result = this.responseText;

                let list = result.split("\n").filter(elt => elt !== '');
                if(list.length > 1)
                {
                    for(let [index, row] of list.entries()){
                        if(index > 0 && row !== ""){
                            const code = row.split(";")[0].replace("\r", "").replace("\t", "");
                            const intitule = row.split(";")[1].replace("\r", "").replace("\t", "");
                            const classe = row.split(";")[2].replace("\r", "").replace("\t", "");
                            addUeToList({
                                code: code,
                                intitule: intitule,
                                code_classe: classe.toUpperCase(),
                            }, (index === (list.length-1)));
                        }
                    }
                    removeFile();
                }
                else{
                    showWarningToast('Le fichier ne contient pas de données !');
                }
                console.log(result);
            }
        }

        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    };

    fileReader.readAsDataURL(file);

}

function removeFile()
{
    file = null;
}

function getFormValueOf(key){
    return ueFormElt.elements.namedItem(key)?.value;
}

function submitUeForm(){
    const classe = getFormValueOf('code_classe');
    const tp_optionnel = getFormValueOf('tp_optionel');
    const ue_optionnelle = getFormValueOf('ue_optionelle');
    const semestre = getFormValueOf('semestre');
    if(ueFormElt.checkValidity() && (classe !== '' && semestre !== '' && tp_optionnel !== '' && ue_optionnelle !== '')){
        addUeToList({
            code: getFormValueOf('code')?.toUpperCase(),
            intitule: getFormValueOf('intitule'),
            code_classe: classe?.toUpperCase(),
            semestre: semestre,
            tp_optionel: Boolean(tp_optionnel),
            ue_optionelle: Boolean(ue_optionnelle),
            credit: getFormValueOf('credit')
        });
        ueFormElt.reset();
    }
    else{
        showErrorToast('Formulaire invalide !');
    }
}

function addUeToList(ueData, shouldRefreshList = true){
    uesList.push({
        id: generateUniqueId(),
        ...ueData
    });
    if(shouldRefreshList){
        setUesListTableContent();
    }
}

function removeUeFromList(ueId, ueCode = ''){
    askConfirmation(`Confirmer-vous le retrait de ${ueCode !== '' ? ('l\'ue' + ueCode) : ' de cette ue'} de la liste à importer ?`)
        .then((confirmationState) =>{
            if(confirmationState){
                uesList = uesList.filter(elt => elt.id !== ueId);
                setUesListTableContent();
            }
        })
}

function setUesListTableContent(){
    uesList.sort((a, b) => a.code.localeCompare(b.code));

    const noData = `<tr><td colspan="9" style="text-align: center; font-style: italic;">Aucune ue ajoutée !</td></tr>`;
    const result = uesList.map((ue, index) =>{
        return `
            <tr>
                <td>${index + 1}</td>
                <td>${ue.code}</td>
                <td>${ue.intitule}</td>
                <td>${ue.code_classe}</td>
                <td>${ue.semestre}</td>
                <td>${ue.credit}</td>
                <td>${ue.ue_optionelle ? 'OUI' : 'NON'}</td>
                <td>${ue.tp_optionel ? 'NON' : 'OUI'}</td>
                <td>
                    <button onclick="removeUeFromList('${ue.id}', '${ue.code}')" class="btn btn-danger btn-sm" title="Retirer l'ue ${ue.code}">
                       <i class="bi bi-trash-fill"></i>
                    </button>
                </td>
            </tr>
        `;
    });

    setImportButtonState()
    uesResultElt.innerHTML = result.length > 0 ? result.join('') : noData;
}

function setImportButtonState(){
    importButton.disabled = uesList.length === 0;
}

function onImport(){
    if(uesList.length > 0){
        showImportLoader();
        const xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function(){
            console.log(this);
            if(this.readyState === 4){
                if(this.status >= 200 && this.status < 300){
                    const result = this.responseText;
                    showSuccessToast('Les ues ont été importées avec succès !');
                    uesList = [];
                    setUesListTableContent();
                    console.log(result);
                }
                else{
                    showErrorToast('Une erreur s\'est produite lors de l\'importation des ues ! Veuillez réessayer !');
                }
                hideImportLoader();
            }
        }

        xmlhttp.open('POST', '/api/ues', true);
        xmlhttp.setRequestHeader("Content-Type", "application/json");
        xmlhttp.send(JSON.stringify({ues: uesList}));
    }
    else{
        showWarningToast('Aucune ue à ajouter !');
        setImportButtonState();
    }
}

function showImportLoader(){
    importLoaderElt.classList.remove('visually-hidden');
    importButton.classList.add('visually-hidden');
}

function hideImportLoader(){
    importLoaderElt.classList.add('visually-hidden');
    importButton.classList.remove('visually-hidden');
}

function showImportContainer(){
    hideLoadingContainer();
    importContainer.classList.remove('visually-hidden');
    summaryContainer.classList.add('visually-hidden');
    storedDataContainer.classList.add('visually-hidden');

}
function showStoredDataContainer(){
    hideLoadingContainer();
    setStoredDataListContent();
    importContainer.classList.add('visually-hidden');
    summaryContainer.classList.add('visually-hidden');
    storedDataContainer.classList.remove('visually-hidden');
}
function showSummaryContainer(){
    hideLoadingContainer();
    importContainer.classList.add('visually-hidden');
    summaryContainer.classList.remove('visually-hidden');
    storedDataContainer.classList.add('visually-hidden');
    if(storedUesList.length > 0){
        summaryWithDataContainer.classList.remove('visually-hidden');
        summaryWithoutDataContainer.classList.add('visually-hidden');
    }
    else {
        summaryWithDataContainer.classList.add('visually-hidden');
        summaryWithoutDataContainer.classList.remove('visually-hidden');
    }
}

function hideLoadingContainer(){
    loadingContainer.classList.add('visually-hidden');
    loadingHasFailedContainer.classList.add('visually-hidden');
    pageLoaderContainer.classList.add('visually-hidden');
}

function showLoadingContainer(hasFailedLoading = false){
    loadingContainer.classList.remove('visually-hidden');
    if(hasFailedLoading) {
        loadingHasFailedContainer.classList.remove('visually-hidden');
        pageLoaderContainer.classList.add('visually-hidden');
    }
    else{
        loadingHasFailedContainer.classList.add('visually-hidden');
        pageLoaderContainer.classList.remove('visually-hidden');
    }
    importContainer.classList.add('visually-hidden');
    summaryContainer.classList.add('visually-hidden');
    storedDataContainer.classList.add('visually-hidden');
}

function makeFirstInitialisation(response){
    paginationData = {
        ...paginationData,
        from: response.from,
        to: response.to,
        currentPage: response.current_page,
        itemsPerPage: response.per_page,
        totalItems: response.total,
        totalPages: response.last_page
    }
    storedUesList = response.data;

    showSummaryContainer();
}

function setStoredDataListContent(){
    storedUesList.sort((a, b) => a.code.localeCompare(b.code));

    const noData = `<tr><td colspan="9" style="text-align: center; font-style: italic;">Aucune ue importée !</td></tr>`;
    const result = storedUesList.map((ue, index) =>{
        return `
            <tr>
                <td>${ue.id}</td>
                <td>${ue.code}</td>
                <td>${ue.intitule}</td>
                <td>${ue.classe?.code}</td>
                <td>${ue.semestre}</td>
                <td>${ue.credit}</td>
                <td>${(ue.ue_optionelle === 1 ) ? 'OUI' : 'NON'}</td>
                <td>${(ue.tp_optionel === 1) ? 'NON' : 'OUI'}</td>
                <td>
                    <button class="btn btn-primary btn-sm" title="Editer l'ue ${ue.code}">
                       <i class="bi bi-pen"></i>
                    </button>
                    <button onclick="deleteStoredUe('${ue.id}', '${ue.code}')" class="btn btn-danger btn-sm" title="Supprimer l'ue ${ue.code}">
                       <i class="bi bi-trash-fill"></i>
                    </button>
                </td>
            </tr>
        `;
    });

    storedUesResultElt.innerHTML = result.length > 0 ? result.join('') : noData;
}

function deleteStoredUe(ueId, ueCode){
    Swal.fire({
        title: 'Demande de confirmation',
        text: 'Confirmez-vous la suppression de l\'ue '+ ueCode + ' ? NB: Cette action est irreversible !',
        showCancelButton: true,
        icon: 'warning',
        cancelButtonText: 'Annuler',
        confirmButtonText: 'Oui, supprimer',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return fetch(`/api/ues/${ueId}`, {method: 'DELETE'})
                .then(response => {
                    if (!response.ok) {
                        throw new Error(response.statusText)
                    }
                    return response.json()
                })
                .catch(error => {
                    Swal.showValidationMessage(
                        `Une erreur s'est produite lors de la suppression de l'ue ${ueCode} ! Veuillez réessayer !`
                    )
                })
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            showSuccessToast(`L'ue ${ueCode} a été supprimée avec succès !`);
            storedUesList = storedUesList.filter(elt => (''+elt.id) !== (''+ueId));
            setStoredDataListContent();
        }
    });
}

