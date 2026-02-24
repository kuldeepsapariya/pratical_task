import './bootstrap';
import Swal from 'sweetalert2';

// Make SweetAlert2 available globally
window.Swal = Swal;

// Delete confirmation function
window.confirmDelete = function(form, title = 'Are you sure?', text = 'You won\'t be able to revert this!') {
    Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
    return false;
};
