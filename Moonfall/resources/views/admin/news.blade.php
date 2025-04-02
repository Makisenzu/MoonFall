@extends('layouts.adminLayout')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">News Management</h3>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewsModal">
        <i class="bi bi-plus-circle me-1"></i> Add News
    </button>
</div>
<div id="alertsContainer"></div>
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-2">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" id="searchNews" placeholder="Search news...">
                    <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-4">
                <select class="form-select" id="urgencyFilter">
                    <option value="">All Urgency Levels</option>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                    <option value="Critical">Critical</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100" id="resetFilters">
                    Reset Filters
                </button>
            </div>
        </div>
    </div>
</div>
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">News Articles</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th width="25%">News Name</th>
                        <th width="40%">Description</th>
                        <th width="15%">Urgency</th>
                        <th width="15%" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="newsTableBody">
                    <tr>
                        <td>1</td>
                        <td>Flash Flood Warning</td>
                        <td>Flash flood warning in the eastern district due to heavy rainfall...</td>
                        <td>
                            <span class="badge bg-danger">Critical</span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-info view-news" data-id="1" title="View">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-primary edit-news" data-id="1" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-news" data-id="1" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Road Closure Update</td>
                        <td>Highway 101 will be closed for maintenance between 10 PM and 5 AM...</td>
                        <td>
                            <span class="badge bg-warning text-dark">Medium</span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-info view-news" data-id="2" title="View">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-primary edit-news" data-id="2" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-news" data-id="2" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Community Event Announcement</td>
                        <td>Annual community festival scheduled for this weekend at Central Park...</td>
                        <td>
                            <span class="badge bg-success">Low</span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-info view-news" data-id="3" title="View">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-primary edit-news" data-id="3" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-news" data-id="3" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        <nav aria-label="News pagination">
            <ul class="pagination justify-content-center mb-0">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<div class="modal fade" id="addNewsModal" tabindex="-1" aria-labelledby="addNewsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="addNewsForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addNewsModalLabel">Add News Article</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="news_name" class="form-label">News Title</label>
                        <input type="text" class="form-control" id="news_name" name="news_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="urgency" class="form-label">Urgency Level</label>
                        <select class="form-select" id="urgency" name="urgency" required>
                            <option value="">Select Urgency Level</option>
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                            <option value="Critical">Critical</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save News</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editNewsModal" tabindex="-1" aria-labelledby="editNewsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editNewsForm">
                @csrf
                <input type="hidden" id="edit_news_id" name="news_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="editNewsModalLabel">Edit News Article</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_news_name" class="form-label">News Title</label>
                        <input type="text" class="form-control" id="edit_news_name" name="news_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_urgency" class="form-label">Urgency Level</label>
                        <select class="form-select" id="edit_urgency" name="urgency" required>
                            <option value="">Select Urgency Level</option>
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                            <option value="Critical">Critical</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update News</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="viewNewsModal" tabindex="-1" aria-labelledby="viewNewsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewNewsModalLabel">News Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 id="view_news_name" class="mb-0">News Title</h5>
                        <span id="view_urgency_badge" class="badge bg-danger">Critical</span>
                    </div>
                    <div class="card-body">
                        <p id="view_description" class="mb-0">News description will appear here...</p>
                    </div>
                    <div class="card-footer text-muted">
                        <small>Created: <span id="view_created_at">01/04/2025</span></small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary edit-from-view" id="editFromViewBtn">Edit</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteNewsModal" tabindex="-1" aria-labelledby="deleteNewsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteNewsModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the news article: "<span id="delete_news_name"></span>"?</p>
                <p class="text-danger mb-0">This action cannot be undone.</p>
                <input type="hidden" id="delete_news_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script src="{{ asset('js/news.js') }}"></script>
@endsection