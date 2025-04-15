document.addEventListener('DOMContentLoaded', function() {

    initTooltips();
    setupFormListeners();
    setupButtonListeners();
    setupFilterListeners();
    
});

function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

$(document).ready(function() {
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    $(document).on('click', '.delete-news', function(e) {
        e.preventDefault();
        
        const form = $(this).closest('form');
        const newsId = $(this).data('id');
        const newsName = $(this).closest('tr').find('td:nth-child(2)').text();
        
        Swal.fire({
            title: 'Are you sure?',
            html: `You're about to delete the news article: <strong>${newsName}</strong><br><span class="text-danger">This action cannot be undone.</span>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    url: form.attr('action'),
                    type: 'POST', 
                    data: form.serialize(),
                    dataType: 'json'
                }).then(response => {
                    return response;
                }).catch(error => {
                    Swal.showValidationMessage(
                        error.responseJSON?.message || 'Something went wrong'
                    );
                });
            }
        }).then((result) => {
            if (result.isConfirmed && result.value?.success) {
                form.closest('tr').fadeOut(300, function() {
                    $(this).remove();
                });
                
                Swal.fire(
                    'Deleted!',
                    result.value.message,
                    'success'
                );
            }
        });
    });

    $('#newsCreateForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = {
            news_name: $('#news_name').val(),
            description: $('#description').val(),
            urgency: $('#urgency').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    addNewsRow(response.news);
                    $('#newsCreateForm')[0].reset();
                    
                    Swal.fire({
                        title: "Success!",
                        text: "News added successfully!",
                        icon: "success"
                    });
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                var errorMessages = '';
                
                for (var field in errors) {
                    errorMessages += errors[field][0] + '\n';
                }
                
                Swal.fire({
                    title: "Error!",
                    text: errorMessages,
                    icon: "error"
                });
            }
        });
    });
    
    function addNewsRow(news) {
        var newRow = `
            <tr>
                <td>${news.id}</td>
                <td>${news.news_name}</td>
                <td>${news.description}</td>
                <td><span class="badge bg-danger">${news.urgency}</span></td>
                <td class="text-center">
                    <button class="btn btn-sm btn-primary edit-news" data-id="${news.id}" title="Edit">
                        <i class="bi bi-pencil"></i> Edit
                    </button>
                    <form class="d-inline" action="{{ route('adminDeleteNews', $news->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger delete-news" data-id="${news.id}" title="Delete">
                            <i class="bi bi-trash"></i> DELETE
                        </button>
                    </form>
                </td>
            </tr>
        `;
        
        $('#newsTableBody').append(newRow);
    }
});

$(document).ready(function() {
    $('#newsCreateForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = {
            news_name: $('#news_name').val(),
            description: $('#description').val(),
            urgency: $('#urgency').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: "Added successfully!",
                        icon: "success",
                        draggable: false
                      });
                    addNewsRow(response.news);
                    
                    $('#newsCreateForm')[0].reset();
                    
                    alert('News added successfully!');
                }
            },
            error: function(xhr, status, error) {
                var errors = xhr.responseJSON.errors;
                var errorMessages = '';
                
                for (var field in errors) {
                    errorMessages += errors[field][0] + '\n';
                }
                
                alert(errorMessages);
            }
        });
    });
    
    function addNewsRow(news) {
        var newRow = `
            <tr>
                <td>${news.id}</td>
                <td>${news.news_name}</td>
                <td>${news.description}</td>
                <td><span class="badge bg-danger">${news.urgency}</span></td>
                <td class="text-center">
                    <button class="btn btn-sm btn-primary edit-news" data-id="${news.id}" title="Edit">
                        <i class="bi bi-pencil"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-danger delete-news" data-id="${news.id}" title="Delete">
                        <i class="bi bi-trash"></i> DELETE
                    </button>
                </td>
            </tr>
        `;
        
        $('#newsTableBody').append(newRow);
    }
});

function setupFormListeners() {
    document.getElementById('addNewsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const modal = bootstrap.Modal.getInstance(document.getElementById('addNewsModal'));
        modal.hide();

        showAlert('News article successfully added!', 'success');
        this.reset();
    });
    document.getElementById('editNewsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const modal = bootstrap.Modal.getInstance(document.getElementById('editNewsModal'));
        modal.hide();
        showAlert('News article successfully updated!', 'success');
    });
}
function setupButtonListeners() {
    setupEditNewsButtons();
    setupViewNewsButtons();
    setupDeleteNewsButtons();
    setupEditFromViewButton();
    setupConfirmDeleteButton();
}
function setupEditNewsButtons() {
    document.querySelectorAll('.edit-news').forEach(button => {
        button.addEventListener('click', function() {
            const newsId = this.getAttribute('data-id');
            document.getElementById('edit_news_id').value = newsId;
            const row = this.closest('tr');
            const title = row.cells[1].textContent;
            const description = row.cells[2].textContent;
            const urgencyText = row.cells[3].querySelector('.badge').textContent;
            
            document.getElementById('edit_news_name').value = title;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_urgency').value = urgencyText;
            
            const editModal = new bootstrap.Modal(document.getElementById('editNewsModal'));
            editModal.show();
        });
    });
}

function setupViewNewsButtons() {
    document.querySelectorAll('.view-news').forEach(button => {
        button.addEventListener('click', function() {
            const newsId = this.getAttribute('data-id');
            const row = this.closest('tr');
            const title = row.cells[1].textContent;
            const description = row.cells[2].textContent;
            const urgencyText = row.cells[3].querySelector('.badge').textContent;
            const urgencyClass = row.cells[3].querySelector('.badge').classList[1];
            
            document.getElementById('view_news_name').textContent = title;
            document.getElementById('view_description').textContent = description;

            const badgeEl = document.getElementById('view_urgency_badge');
            badgeEl.textContent = urgencyText;
            badgeEl.className = '';
            badgeEl.classList.add('badge', urgencyClass);

            document.getElementById('view_created_at').textContent = '04/03/2025';

            document.getElementById('editFromViewBtn').setAttribute('data-id', newsId);
            
            const viewModal = new bootstrap.Modal(document.getElementById('viewNewsModal'));
            viewModal.show();
        });
    });
}

function setupEditFromViewButton() {
    document.getElementById('editFromViewBtn').addEventListener('click', function() {
        const newsId = this.getAttribute('data-id');
        const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewNewsModal'));
        viewModal.hide();

        document.querySelector(`.edit-news[data-id="${newsId}"]`).click();
    });
}

function setupDeleteNewsButtons() {
    document.querySelectorAll('.delete-news').forEach(button => {
        button.addEventListener('click', function() {
            const newsId = this.getAttribute('data-id');
            const newsTitle = this.closest('tr').cells[1].textContent;
            
            document.getElementById('delete_news_id').value = newsId;
            document.getElementById('delete_news_name').textContent = newsTitle;
            
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteNewsModal'));
            deleteModal.show();
        });
    });
}
function setupConfirmDeleteButton() {
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        const newsId = document.getElementById('delete_news_id').value;
        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteNewsModal'));
        modal.hide();

        showAlert('News article successfully deleted!', 'danger');
        
        document.querySelector(`button.delete-news[data-id="${newsId}"]`).closest('tr').remove();
    });
}
function setupFilterListeners() {
    document.getElementById('searchBtn').addEventListener('click', function() {
        const searchTerm = document.getElementById('searchNews').value.toLowerCase();
        filterTable(searchTerm);
    });
    document.getElementById('searchNews').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('searchBtn').click();
        }
    });
    document.getElementById('urgencyFilter').addEventListener('change', function() {
        const urgency = this.value;
        filterTableByUrgency(urgency);
    });
    document.getElementById('resetFilters').addEventListener('click', function() {
        document.getElementById('searchNews').value = '';
        document.getElementById('urgencyFilter').value = '';

        const rows = document.getElementById('newsTableBody').querySelectorAll('tr');
        rows.forEach(row => {
            row.style.display = '';
        });
    });
}

/**
 * @param {string} term
 */
function filterTable(term) {
    const rows = document.getElementById('newsTableBody').querySelectorAll('tr');
    
    rows.forEach(row => {
        const title = row.cells[1].textContent.toLowerCase();
        const description = row.cells[2].textContent.toLowerCase();
        
        if (title.includes(term) || description.includes(term)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

/**
 * @param {string} urgency - Urgency level
 */
function filterTableByUrgency(urgency) {
    if (!urgency) {
        const rows = document.getElementById('newsTableBody').querySelectorAll('tr');
        rows.forEach(row => {
            row.style.display = '';
        });
        return;
    }
    
    const rows = document.getElementById('newsTableBody').querySelectorAll('tr');
    
    rows.forEach(row => {
        const rowUrgency = row.cells[3].querySelector('.badge').textContent;
        
        if (rowUrgency === urgency) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

/**
 * @param {string} message
 * @param {string} type
 */
function showAlert(message, type) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    document.getElementById('alertsContainer').innerHTML = alertHtml;
    setTimeout(() => {
        const alertElement = document.querySelector('.alert');
        if (alertElement) {
            const alert = bootstrap.Alert.getInstance(alertElement);
            if (alert) {
                alert.close();
            } else {
                alertElement.remove();
            }
        }
    }, 5000);
}