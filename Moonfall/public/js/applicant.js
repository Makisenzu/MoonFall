document.querySelectorAll('.approve-form').forEach(form => {
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const { isConfirmed } = await Swal.fire({
            title: 'Are you sure?',
            text: "You are about to approve this applicant!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, approve it!',
            cancelButtonText: 'Cancel'
        });

        if (!isConfirmed) return;

        const button = form.querySelector('button[type="submit"]');
        
        try {
            button.disabled = true;
            button.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...`;
            
            const response = await fetch(form.action, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    status: 'Approved'
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                await Swal.fire({
                    title: 'Approved!',
                    text: data.message || 'Applicant has been approved.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
                window.location.reload();
            } else {
                await Swal.fire({
                    title: 'Error!',
                    text: data.message || 'Something went wrong.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        } catch (error) {
            console.error('Error:', error);
            await Swal.fire({
                title: 'Error!',
                text: 'An unexpected error occurred.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        } finally {
            button.disabled = false;
            button.innerHTML = `<i class="bi bi-check-lg"></i> Approve`;
        }
    });
});