function showToast(){
    Toastify({
        text: "This is a toast",
        duration: 3000,
        close: true,
        gravity: "bottom",
        position: "center",
        className: 'bg-danger',
        stopOnFocus: true,
    }).showToast();
}

function showErrorToast(message){
    Toastify({
        text: "<div class='d-inline' style='font-size: 0.9em;'><i style='margin-right: 10px;' class='bi bi-x-octagon-fill'></i>"+message+"</div>",
        duration: 5000,
        close: true,
        gravity: "bottom",
        position: "center",
        className: 'bg-danger',
        stopOnFocus: true,
        escapeMarkup: false,
        style: {
            background: "red"
        }
    }).showToast();
}

function showSuccessToast(message){
    Toastify({
        text: "<div class='d-inline' style='font-size: 0.9em;'><i style='margin-right: 10px;' class='bi bi-check-circle-fill'></i>"+message+"</div>",
        duration: 5000,
        close: true,
        gravity: "bottom",
        position: "center",
        className: 'bg-success',
        stopOnFocus: true,
        escapeMarkup: false,
        style: {
            background: "green"
        }
    }).showToast();
}
function showWarningToast(message){
    Toastify({
        text: "<div class='d-inline' style='font-size: 0.9em;'><i style='margin-right: 10px;' class='bi bi-exclamation-octagon-fill'></i>"+message+"</div>",
        duration: 5000,
        close: true,
        gravity: "bottom",
        position: "center",
        className: 'bg-warning',
        stopOnFocus: true,
        escapeMarkup: false,
        style: {
            background: "yellow"
        }
    }).showToast();
}

function showAlert(){
    Swal.fire({
        title: 'Error!',
        text: 'Do you want to continue',
        icon: 'error',
        confirmButtonText: 'Cool'
    })
}

function askConfirmation(message){
    return new Promise((resolve, reject) =>{
        Swal.fire({
            title: 'Demande de confirmation',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            // confirmButtonColor: '#3085d6',
            // cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, Continuer!',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            resolve(result.isConfirmed);
        })
            .catch((err) =>{
                reject(err);
            });
    });
}

function generateUniqueId(){
    return Math.random().toString(16).slice(2)
}
