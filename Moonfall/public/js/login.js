function showCreate() {
    document.getElementById('loginForm').style.opacity = '0';
    setTimeout(() => {
        document.getElementById('loginForm').style.display = 'none';
        document.getElementById('registerForm').style.display = 'block';
        setTimeout(() => {
            document.getElementById('registerForm').style.opacity = '1';
        }, 50);
    }, 300);
}

function showLogin() {
    document.getElementById('registerForm').style.opacity = '0';
    setTimeout(() => {
        document.getElementById('registerForm').style.display = 'none';
        document.getElementById('loginForm').style.display = 'block';
        setTimeout(() => {
            document.getElementById('loginForm').style.opacity = '1';
        }, 50);
    }, 300);
}

document.getElementById('registrationForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const form = event.target;
    const messageDiv = document.getElementById('message');
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;

    // Clear previous messages and errors
    messageDiv.innerHTML = '';
    messageDiv.className = '';
    document.querySelectorAll('.error-message').forEach(el => el.remove());
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

    // Set loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Creating...';

    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;

        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Account Created!',
                text: data.message,
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                showLogin()
            });
        } else {
            messageDiv.innerHTML = '<div class="alert alert-danger">' + (data.message || 'An error occurred. Please try again.') + '</div>';
            if (data.errors) {
                for (const field in data.errors) {
                    const inputField = form.querySelector(`[name="${field}"]`);
                    if (inputField) {
                        inputField.classList.add('is-invalid');
                        const formGroup = inputField.closest('.form-floating, .mb-4, .d-flex.flex-wrap');
                        if (formGroup) {
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'invalid-feedback';
                            errorDiv.textContent = data.errors[field][0];
                            formGroup.appendChild(errorDiv);
                        }
                    }
                }
            }
        }
    })
    .catch(error => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
        
        console.error('Error:', error);
        messageDiv.innerHTML = '<div class="alert alert-danger">An error occurred. Please try again.</div>';
    });
});