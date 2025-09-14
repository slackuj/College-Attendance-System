// Event listener for the reset password button from the profile page
document.getElementById('resetPasswordBtn').addEventListener('click', async function() {
    const btn = this;
    const passwordContainer = document.getElementById('password-display-container');
    const passwordText = document.getElementById('new-password-text');

    // Get the email from the form field
    const email = document.getElementById('email').value;

    // Set the desired delay in milliseconds (e.g., 5000 for 5 seconds)
    const DELAY_IN_MILLISECONDS = 3000;

    // 1. Show loading state
    btn.classList.add('loading');
    btn.setAttribute('disabled', 'disabled');

    try {
        // 2. Fetch the new password from the backend API
        const tData = {
            email: email,
            role: 3 // 3 = student
        };

        const response = await fetch('/backend/api/reset_password.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(tData)
        });

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();

        if (data.success) {
            const newPassword = data.password;

            // 3. Add an artificial delay to show a loading state for longer
            await new Promise(resolve => setTimeout(resolve, DELAY_IN_MILLISECONDS));

            // 4. Hide loading state and display success message
            btn.classList.remove('loading');
            btn.removeAttribute('disabled');
            btn.style.display = 'none';

            const modelInfo = document.getElementById('modelInfo');
            let infoTag = `
                <i class="fa-solid fa-circle-check password-reset-container"> Password has been reset</i><br><br>
                Provide this temporary password to the student so they can sign in.
                <br><br>Temporary password <i class="fa-solid fa-circle-info"></i></br>
            `;
            modelInfo.innerHTML = '';
            modelInfo.innerHTML = infoTag;

            // 5. Display the new password
            passwordText.textContent = newPassword;
            passwordContainer.classList.add('show');
        } else {
            // Handle API-specific errors
            throw new Error(data.message || 'Password reset failed.');
        }

    } catch (error) {
        // Handle any errors
        btn.classList.remove('loading');
        btn.removeAttribute('disabled');
        console.error("Password reset failed:", error);
        toastr.remove();
        toastr.options = {
            "positionClass": "toast-bottom-right",
            "closeButton": true,
            "progressBar": true,
            "timeOut": "5000"
        };
        toastr.error('Error!', 'Password Reset Failed');
    }
});

// --- Copy Text to Clipboard Functionality ---

const copyIcon = document.getElementById('passwordCopy');
const newPasswordText = document.getElementById('new-password-text');

if (copyIcon && newPasswordText) {
    copyIcon.addEventListener('click', function() {
        const textToCopy = newPasswordText.textContent;
        navigator.clipboard.writeText(textToCopy)
            .then(() => {
                toastr.remove();
                toastr.options = {
                    "positionClass": "toast-bottom-right",
                    "closeButton": true,
                    "timeOut": "3000"
                };
                toastr.success('Password Copied!');
            })
            .catch(err => {
                console.error('Failed to copy text: ', err);
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
}