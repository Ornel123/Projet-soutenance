const dropArea = document.querySelector("#drag-area"),
    dragText = dropArea.querySelector("#drag-header-text"),
    browseButton = dropArea.querySelector("#browse-button"),
    uploadInput = dropArea.querySelector("#upload-input");

const progressElt = document.getElementById("progress");
const progressComponent = document.getElementById("progress-component");

const importedFileNameElt = document.getElementById("imported-file-name");
const fileNameElt = document.getElementById("file-name");

const noteFormElt = document.getElementById("note-form");

const errorDiv = document.getElementById("required-error");
const errorTextSpan = document.getElementById("error-text");

const notesResultElt= document.getElementById("notes-result");
const storedNotesResultElt= document.getElementById("stored-notes-result");

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



let notesList = [];
let storedNotesList = [];

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



setNotesListTableContent();

browseButton.onclick = ()=>{
    uploadInput.click();
}

uploadInput.addEventListener("change", function(event){
    if(isValidFileType(this.files[0]))
    {
        if(getSelectUeCode() !== '') {
            file = this.files[0];
            uploadFileSimulator(file);
        }
        else{
            showErrorToast('Veuillez d\'abord choisir l\'unité d\'enseignement concernée !');
        }
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
        if(getSelectUeCode() !== ''){
            file = event.dataTransfer.files[0];
            uploadFileSimulator(file);
        }
        else{
            showErrorToast('Veuillez d\'abord choisir l\'unité d\'enseignement concernée !');
        }
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
                            let noteIndex = 3;
                            let cc = null;
                            let tp = null;
                            let sn = null;
                            const matricule_etudiant = row.split(";")[1].replace("\r", "").replace("\t", "");
                            const noms_etudiants = row.split(";")[2].replace("\r", "").replace("\t", "");
                            
                            if(hasSelectedCC()) {
                                cc = row.split(";")[noteIndex]?.replace("\r", "").replace("\t", "") ?? null;
                                ++noteIndex;
                            }
                            
                            if(hasSelectedTP()) {
                                tp = row.split(";")[noteIndex]?.replace("\r", "").replace("\t", "") ?? null;
                                ++noteIndex;
                            }
                            
                            if(hasSelectedSN()) {
                                sn = row.split(";")[noteIndex]?.replace("\r", "").replace("\t", "") ?? null;
                                ++noteIndex;
                            }

                            addNoteToList({
                                matricule_etudiant: matricule_etudiant.toUpperCase(),
                                noms_etudiant: noms_etudiants,
                                cc: cc ? parseInt(cc) : null,
                                tp: tp ? parseInt(tp) : null,
                                sn: sn ? parseInt(sn) : null,
                                code_ue: getSelectUeCode()
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
    return noteFormElt.elements.namedItem(key)?.value;
}

function hasSelectedCC() {
    return document.getElementById('cc')?.checked;
}

function hasSelectedTP() {
    return document.getElementById('tp')?.checked;
}

function hasSelectedSN() {
    return document.getElementById('sn')?.checked;
}

function getSelectedNoteType(){
    let value = 'cc';
    if(document.getElementById('cc')?.checked){
        value = 'cc';
    }
    else if(document.getElementById('tp')?.checked){
        value = 'tp';
    }
    else if(document.getElementById('sn')?.checked){
        value = 'sn';
    }

    return value;
}

function getSelectUeCode(){
    return document.getElementById('code_ue').value;
}

function submitNoteForm(){
    const ue = getSelectUeCode();
    let note = getFormValueOf('note');
    note = note === '-1' ? null : parseFloat(note);
    if(noteFormElt.checkValidity() && (ue !== '')){
        addNoteToList({
            matricule_etudiant: getFormValueOf('matricule_etudiant')?.toUpperCase(),
            noms_etudiant: '-',
            code_ue: ue?.toUpperCase(),
            cc: getSelectedNoteType() === 'cc' ? note : null,
            tp: getSelectedNoteType() === 'tp' ? note : null,
            sn: getSelectedNoteType() === 'sn' ? note : null,
        });
        noteFormElt.reset();
    }
    else{
        showErrorToast('Formulaire invalide !');
    }
}

function addNoteToList(noteData, shouldRefreshContent){
    let data = {...noteData}
    const previousStudentNote = notesList.find(elt => (elt.matricule_etudiant === noteData.matricule_etudiant && elt.noms_etudiant === noteData.noms_etudiant));
    if(previousStudentNote) {
        data = {
            ...previousStudentNote,
            cc: noteData.cc ?? previousStudentNote.cc,
            tp: noteData.tp ?? previousStudentNote.tp,
            sn: noteData.sn ?? previousStudentNote.sn
        }

        for (let i = 0; i < notesList.length; i++) {
            if(notesList[i].id === data.id){
                notesList[i] = {
                    ...data
                }
                break;
            }
        }
    }
    else {
        notesList.push({
            id: generateUniqueId(),
            ...data
        });
    }

    if(shouldRefreshContent) {
        setNotesListTableContent();
    }
    
}

function removeNoteFromList(noteId){
    askConfirmation(`Confirmer-vous le retrait de cette note de la liste à importer ?`)
        .then((confirmationState) =>{
            if(confirmationState){
                notesList = notesList.filter(elt => elt.id !== noteId);
                setNotesListTableContent();
            }
        })
}

function setNotesListTableContent(){
    notesList.sort((a, b) => a.noms_etudiant.localeCompare(b.noms_etudiant));

    const noData = `<tr><td colspan="8" style="text-align: center; font-style: italic;">Aucune note ajoutée !</td></tr>`;
    const result = notesList.map((note, index) =>{
        return `
            <tr>
                <td>${index + 1}</td>
                <td>${note.matricule_etudiant}</td>
                <td>${note.noms_etudiant}</td>
                <td>${note.code_ue}</td>
                <td>${note.cc ?? '-'}</td>
                <td>${note.tp ?? '-'}</td>
                <td>${note.sn ?? '-'}</td>
                <td>
                    <button onclick="removeNoteFromList('${note.id}')" class="btn btn-danger btn-sm" title="Retirer la note">
                       <i class="bi bi-trash-fill"></i>
                    </button>
                </td>
            </tr>
        `;
    });

    setImportButtonState()
    notesResultElt.innerHTML = result.length > 0 ? result.join('') : noData;
}

function setImportButtonState(){
    importButton.disabled = notesList.length === 0;
}

function onImport(){
    if(notesList.length > 0){
        showImportLoader();
        const xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function(){
            console.log(this);
            if(this.readyState === 4){
                if(this.status >= 200 && this.status < 300){
                    const result = this.responseText;
                    showSuccessToast('Les notes ont été importées avec succès !');
                    notesList = [];
                    setNotesListTableContent();
                    console.log(result);
                }
                else{
                    showErrorToast('Une erreur s\'est produite lors de l\'importation des notes ! Veuillez réessayer !');
                }
                hideImportLoader();
            }
        }

        xmlhttp.open('POST', '/api/notes', true);
        xmlhttp.setRequestHeader("Content-Type", "application/json");
        xmlhttp.send(JSON.stringify({notes: notesList}));
    }
    else{
        showWarningToast('Aucune note à ajouter !');
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
    if(storedNotesList.length > 0){
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
    storedNotesList = response.data;

    showSummaryContainer();
}

function setStoredDataListContent(){
    storedNotesList.sort((a, b) => a.etudiant?.noms?.localeCompare(b.etudiant?.noms));

    const noData = `<tr><td colspan="8" style="text-align: center; font-style: italic;">Aucune note importée !</td></tr>`;
    const result = storedNotesList.map((note, index) =>{
        return `
            <tr>
                <td>${note.id}</td>
                <td>${note.etudiant?.matricule}</td>
                <td>${note.etudiant?.noms}</td>
                <td>${note.ue?.code}</td>
                <td>${note.cc ?? '-'}</td>
                <td>${note.tp ?? '-'}</td>
                <td>${note.sn ?? '-'}</td>
                <td>
                    <button class="btn btn-primary btn-sm" title="Editer la note">
                       <i class="bi bi-pen"></i>
                    </button>
                    <button onclick="deleteStoredNote('${note.id}')" class="btn btn-danger btn-sm" title="Supprimer la note">
                       <i class="bi bi-trash-fill"></i>
                    </button>
                </td>
            </tr>
        `;
    });

    storedNotesResultElt.innerHTML = result.length > 0 ? result.join('') : noData;
}

function deleteStoredNote(noteId){ 
    Swal.fire({
        title: 'Demande de confirmation',
        text: 'Confirmez-vous la suppression de cette note ? NB: Cette action est irreversible !',
        showCancelButton: true,
        icon: 'warning',
        cancelButtonText: 'Annuler',
        confirmButtonText: 'Oui, supprimer',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return fetch(`/api/notes/${noteId}`, {method: 'DELETE'})
                .then(response => {
                    if (!response.ok) {
                        throw new Error(response.statusText)
                    }
                    return response.json()
                })
                .catch(error => {
                    Swal.showValidationMessage(
                        `Une erreur s'est produite lors de la suppression de la note ! Veuillez réessayer !`
                    )
                })
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            showSuccessToast(`La note a été supprimée avec succès !`);
            storedNotesList = storedNotesList.filter(elt => (''+elt.id) !== (''+noteId));
            setStoredDataListContent();
        }
    });
}

