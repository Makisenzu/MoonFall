@extends('layouts.userLayout')
@section('content')
<style>
    .legend-badge {
      width: 15px;
      height: 15px;
      display: inline-block;
      border-radius: 3px;
      margin-right: 5px;
    }
    .custom-marker {
      background: transparent;
      border: none;
    }
    #map {
      height: 500px;
      width: 100%;
      z-index: 1;
      position: relative;
    }
    .map-container {
      position: relative;
      height: 500px;
      width: 100%;
      margin-bottom: 15px;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .news-item {
      border-bottom: 1px solid rgba(0,0,0,.125);
      padding: 16px;
    }
    .news-item:last-child {
      border-bottom: none;
    }
    .new-item {
      background-color: rgba(25, 135, 84, 0.1);
      border-left: 4px solid #198754;
      transition: all 0.3s ease;
    }
    .urgency-badge {
      font-size: 0.75rem;
      padding: 0.25rem 0.5rem;
    }
    .pulse {
      animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }
  </style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h5 class="card-title mb-0">Interactive Map</h5>
                </div>
                <input type="hidden" id="newsAudience" value="{{ \App\Models\Volunteer::where('users_id', auth()->id())->exists() ? 'volunteer' : 'civilian' }}">
                <div class="card-body">
                    <div id="allMap" style="height: 400px;" class="w-100 border rounded"></div>
                </div>
                <div class="card-footer bg-light">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex flex-wrap">
                                <div class="me-4 mb-2">
                                    <span class="badge bg-success me-1">&nbsp;</span> Evacuation
                                </div>
                                <div class="me-4 mb-2">
                                    <span class="badge bg-primary me-1">&nbsp;</span> Food Zone
                                </div>
                                <div class="me-4 mb-2">
                                    <span class="badge bg-warning me-1">&nbsp;</span> Hospital Zone
                                </div>
                                <div class="me-4 mb-2">
                                    <span class="badge bg-danger me-1">&nbsp;</span> Danger Zone
                                </div>
                                <div class="me-4 mb-2">
                                    <span class="badge bg-secondary me-1">&nbsp;</span> Police Zone
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="routeButton" class="btn btn-success">Route</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
              <h5 class="mb-0">Emergency Updates</h5>
            </div>
            @foreach ($newsData as $news)
            <div class="card-body p-0">
                <div class="news-container">
                  <div class="news-item">
                    <div class="d-flex justify-content-between align-items-start">
                      <h5 class="mb-1">{{$news->news_name}}</h5>
                      <span class="badge bg-primary">{{ $news->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="mb-2">{{$news->description}}.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        @if($news->urgency == 'High')
                          <span class="badge bg-danger urgency-badge">High Priority</span>
                        @elseif($news->urgency == 'Medium')
                          <span class="badge bg-warning text-dark urgency-badge">Medium Priority</span>
                        @else
                          <span class="badge bg-info text-dark urgency-badge">Low Priority</span>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
            <input type="text" name="LatitudeFrom" id="LatitudeFrom" hidden>
            <input type="text" name="LongitudeFrom" id="LongitudeFrom" hidden>
            <input type="text" name="latitudeInput" id="latitudeInput" hidden>
            <input type="text" name="longitudeInput" id="longitudeInput" hidden>
          </div>
        </div>
      </div>
    </div>
</div>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    window.APP_CONFIG = {
        apiKey: "{{ env('MAPBOX_API_KEY') }}"
    };
</script>
<script>
    window.userRole = @json($audience);
    window.shelters = @json($zoneData);
</script>
<script src="{{ asset('js/userDashboard.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        initMap();
    });
</script>
<style>
    .custom-marker {
        background: transparent;
        border: none;
    }
    #map {
        z-index: 1;
    }
    .new-item {
        background-color: rgba(25, 135, 84, 0.1);
        border-left: 4px solid #198754;
        transition: all 0.3s ease;
    }
    
    .pulse {
        animation: pulse 1.5s infinite;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
    
    .toast-error { background-color: #dc3545; }
    .toast-warning { background-color: #ffc107; color: #212529; }
    .toast-info { background-color: #0dcaf0; }
</style>
@endsection