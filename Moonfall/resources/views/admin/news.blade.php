@extends('layouts.adminLayout')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">News Management</h3>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewsModal">
        <i class="bi bi-plus-circle me-1"></i> Add News
    </button>
</div>
<div id="alertsContainer"></div>
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
                    @if ($newsData)
                        @foreach ($newsData as $news)
                        <tr>
                            <td>{{$news->id}}</td>
                            <td>{{$news->news_name}}</td>
                            <td>{{$news->description}}</td>
                            <td>
                                <span class="badge bg-danger">{{$news->urgency}}</span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-primary edit-news" data-id="{{ $news->id }}" title="Edit">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                <form class="d-inline" action="{{ route('adminDeleteNews', $news->id) }}" id="deleteForm" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger delete-news" data-id="{{ $news->id }}" title="Delete">
                                        <i class="bi bi-trash"></i> DELETE
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addNewsModal" tabindex="-1" aria-labelledby="addNewsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="addNewsForm" method="POST" action="{{ route('adminStoreNews') }}">
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
                    <div class="mb-3">
                        <label for="urgency" class="form-label">Audience</label>
                        <select class="form-select" id="audience" name="audience" required>
                            <option value="">Select Audience</option>
                            <option value="Volunteer">Volunteer</option>
                            <option value="Civilian">Civilian</option>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection

@section('scripts')
    <script src="{{ asset('js/news.js') }}"></script>
@endsection