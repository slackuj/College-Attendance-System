refresh = document.getElementById('refresh');

refresh.addEventListener('click', () => {
   window.location.reload();
});

const copyIcon2 = document.getElementById('copyEmail');
const emailText = document.getElementById('teacher-email').textContent;

copyIcon2.addEventListener('click', function() {
        navigator.clipboard.writeText(emailText)
            .then(() => {
                toastr.remove();
                toastr.options = {
                    "positionClass": "toast-bottom-right",
                    "closeButton": true,
                    "timeOut": "3000"
                };
                toastr.success('Email Copied!');
            })
            .catch(err => {
                console.error('Failed to copy email: ', err);
                toastr.remove();
                toastr.options = {
                    "positionClass": "toast-bottom-right",
                    "closeButton": true,
                    "progressBar": true,
                    "timeOut": "5000"
                };
                toastr.error('Error!');
            });
});