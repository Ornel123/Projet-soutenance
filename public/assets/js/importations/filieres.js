const dropArea = document.querySelector("#drag-area"),
    dragText = dropArea.querySelector("#drag-header-text"),
    browseButton = dropArea.querySelector("#browse-button"),
    uploadInput = dropArea.querySelector("#upload-input");

const progressElt = document.getElementById("progress");
const progressComponent = document.getElementById("progress-component");

const importedFileNameElt = document.getElementById("imported-file-name");
const fileNameElt = document.getElementById("file-name");

const sectorCodeInput = document.getElementById("code");
const sectorFormElt = document.getElementById("sector-form");
const sectorEntitledInput = document.getElementById("intitule");

const errorDiv = document.getElementById("required-error");
const errorTextSpan = document.getElementById("error-text");

const sectorsResultElt= document.getElementById("sectors-result");
const storedSectorsResultElt= document.getElementById("stored-sectors-result");

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

let sectorsList = [];
let storedSectorsList = [];

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

setSectorsListTableContent();

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
                            addSectorToList({
                                code: code,
                                intitule: intitule
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
    return sectorFormElt.elements.namedItem(key)?.value;
}

function submitSectorForm(){
    if(sectorFormElt.checkValidity()){
        addSectorToList({
            code: getFormValueOf('code')?.toUpperCase(),
            intitule: getFormValueOf('intitule')
        });
        sectorFormElt.reset();
    }
    else{
        showErrorToast('Formulaire invalide !');
    }
}

function addSectorToList(sectorData, shouldRefreshList = true){
    sectorsList.push({
        id: generateUniqueId(),
        ...sectorData
    });
    if(shouldRefreshList){
        setSectorsListTableContent();
    }
}

function removeSectorFromList(sectorId, sectorCode = ''){
    askConfirmation(`Confirmer-vous le retrait de ${sectorCode !== '' ? ('la filière' + sectorCode) : ' de cette filière'} de la liste à importer ?`)
        .then((confirmationState) =>{
            if(confirmationState){
                sectorsList = sectorsList.filter(elt => elt.id !== sectorId);
                setSectorsListTableContent();
            }
        })
}

function setSectorsListTableContent(){
    sectorsList.sort((a, b) => a.code.localeCompare(b.code));

    const noData = `<tr><td colspan="4" style="text-align: center; font-style: italic;">Aucune filière ajoutée !</td></tr>`;
    const result = sectorsList.map((sector, index) =>{
        return `
            <tr>
                <td>${index + 1}</td>
                <td>${sector.code}</td>
                <td>${sector.intitule}</td>
                <td>
                    <button onclick="removeSectorFromList('${sector.id}', '${sector.code}')" class="btn btn-danger btn-sm" title="Retirer la filière ${sector.code}">
                       <i class="bi bi-trash-fill"></i>
                    </button>
                </td>
            </tr>
        `;
    });

    setImportButtonState()
    sectorsResultElt.innerHTML = result.length > 0 ? result.join('') : noData;
}

function setImportButtonState(){
    importButton.disabled = sectorsList.length === 0;
}

function onImport(){
    if(sectorsList.length > 0){
        showImportLoader();
        const xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function(){
            console.log(this);
            if(this.readyState === 4){
                if(this.status >= 200 && this.status < 300){
                    const result = this.responseText;
                    showSuccessToast('Les filières ont été importées avec succès !');
                    sectorsList = [];
                    setSectorsListTableContent();
                    console.log(result);
                }
                else{
                    showErrorToast('Une erreur s\'est produite lors de l\'importation des filières ! Veuillez réessayer !');
                }
                hideImportLoader();
            }
        }

        xmlhttp.open('POST', '/api/filieres', true);
        xmlhttp.setRequestHeader("Content-Type", "application/json");
        xmlhttp.send(JSON.stringify({filieres: sectorsList}));
    }
    else{
        showWarningToast('Aucune filière à ajouter !');
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
    if(storedSectorsList.length > 0){
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
    storedSectorsList = response.data;

    showSummaryContainer();
}

function setStoredDataListContent(){
    storedSectorsList.sort((a, b) => a.code.localeCompare(b.code));

    const noData = `<tr><td colspan="4" style="text-align: center; font-style: italic;">Aucune filière importée !</td></tr>`;
    const result = storedSectorsList.map((sector, index) =>{
        return `
            <tr>
                <td>${sector.id}</td>
                <td>${sector.code}</td>
                <td>${sector.intitule}</td>
                <td>${sector.nombre_classes}</td>
                <td>
                    <button class="btn btn-primary btn-sm" title="Editer la filière ${sector.code}">
                       <i class="bi bi-pen"></i>
                    </button>
                    <button onclick="deleteStoredSector('${sector.id}', '${sector.code}')" class="btn btn-danger btn-sm" title="Supprimer la filière ${sector.code}">
                       <i class="bi bi-trash-fill"></i>
                    </button>
                </td>
            </tr>
        `;
    });

    storedSectorsResultElt.innerHTML = result.length > 0 ? result.join('') : noData;
}

function deleteStoredSector(sectorId, sectorCode){
    Swal.fire({
        title: 'Demande de confirmation',
        text: 'Confirmez-vous la suppression de la filière '+ sectorCode + ' ? NB: Cette action est irreversible !',
        showCancelButton: true,
        icon: 'warning',
        cancelButtonText: 'Annuler',
        confirmButtonText: 'Oui, supprimer',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return fetch(`/api/filieres/${sectorId}`, {method: 'DELETE'})
                .then(response => {
                    if (!response.ok) {
                        throw new Error(response.statusText)
                    }
                    return response.json()
                })
                .catch(error => {
                    Swal.showValidationMessage(
                        `Une erreur s'est produite lors de la suppression de la filière ${sectorCode} ! Veuillez réessayer !`
                    )
                })
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            showSuccessToast(`La filière ${sectorCode} a été supprimée avec succès !`);
            storedSectorsList = storedSectorsList.filter(elt => (''+elt.id) !== (''+sectorId));
            setStoredDataListContent();
        }
    });
}

