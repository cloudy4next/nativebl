import Swal from 'sweetalert2'

window.swal = Swal;

// var popupConfirmList = [].slice.call(document.querySelectorAll('[data-bl-popup="confirm"]'))
// var popupList = popupConfirmList.map(function (popupTriggerEl) {
//   return new bootstrap.Tooltip(popupTriggerEl)
// })

window.gridDeleteConfirm = function(el)
{
    Swal.fire({
        icon: 'warning',
        text: 'Do you want to delete this item?',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        confirmButtonColor: '#e3342f',
    }).then((result) => {
         if(result.isConfirmed){
           document.location = el.getAttribute('href');
         }
    });
   return false;
}
