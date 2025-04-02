document.addEventListener('DOMContentLoaded', function() {

    const toggleSidebar = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('main');
    
    if (toggleSidebar && sidebar) {
        toggleSidebar.addEventListener('click', function() {
            if (window.innerWidth < 768) {
                sidebar.classList.toggle('show');
            } else {
                sidebar.classList.toggle('collapse');
                mainContent.classList.toggle('col-lg-12');
                mainContent.classList.toggle('col-lg-10');
            }
        });
    }

    const currentLocation = window.location.pathname;
    const navLinks = document.querySelectorAll('.sidebar .nav-link');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');

        if (href && currentLocation.includes(href)) {
            link.classList.add('active');
        }
    });
    
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
    
    const deleteButtons = document.querySelectorAll('[data-confirm-delete]');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const message = this.dataset.confirmMessage || 'This item will be deleted permanently. Are you sure?';
            
            Swal.fire({
                title: 'Are you sure?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (this.tagName === 'BUTTON' && this.form) {
                        this.form.submit();
                    } 
                    else if (this.tagName === 'A') {
                        window.location.href = this.href;
                    }
                    
                }
            });
        });
    });
    
    window.addEventListener('resize', function() {
        if (window.innerWidth < 768) {
            sidebar.classList.remove('show');
        }
    });
    const datePickers = document.querySelectorAll('.datepicker');
    if (typeof flatpickr !== 'undefined') {
        datePickers.forEach(picker => {
            flatpickr(picker);
        });
    }
});