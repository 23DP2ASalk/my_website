@extends('statistics.layout')

@section('title', 'My Statistics')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="text-white"><i class="bi bi-graph-up"></i> My Statistics</h1>
            <a href="{{ route('statistics.create') }}" class="btn btn-light btn-lg">
                <i class="bi bi-plus-circle"></i> Add Statistics
            </a>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('statistics.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Sport Type</label>
                        <select name="sport_type" class="form-select" onchange="this.form.submit()">
                            <option value="all" {{ request('sport_type') == 'all' ? 'selected' : '' }}>All Sports</option>
                            <option value="Basketball" {{ request('sport_type') == 'Basketball' ? 'selected' : '' }}>Basketball</option>
                            <option value="Football" {{ request('sport_type') == 'Football' ? 'selected' : '' }}>Football</option>
                            <option value="Volleyball" {{ request('sport_type') == 'Volleyball' ? 'selected' : '' }}>Volleyball</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search notes..." value="{{ request('search') }}">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="{{ route('statistics.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if($statistics->count() > 0)
    <!-- Statistics Cards -->
    <div class="row">
        @foreach($statistics as $stat)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card stat-card h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            @if($stat->sport_type == 'Basketball')
                                <i class="bi bi-dribbble"></i>
                            @elseif($stat->sport_type == 'Football')
                                <i class="bi bi-trophy"></i>
                            @else
                                <i class="bi bi-volleyball"></i>
                            @endif
                            {{ $stat->sport_type }}
                            <span class="badge bg-light text-dark float-end">
                                {{ $stat->game_date->format('M d, Y') }}
                            </span>
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($stat->sport_type == 'Basketball')
                            <div class="row text-center">
                                <div class="col-4">
                                    <h3 class="text-primary">{{ $stat->points ?? 0 }}</h3>
                                    <small>Points</small>
                                </div>
                                <div class="col-4">
                                    <h3 class="text-primary">{{ $stat->assists ?? 0 }}</h3>
                                    <small>Assists</small>
                                </div>
                                <div class="col-4">
                                    <h3 class="text-primary">{{ $stat->rebounds ?? 0 }}</h3>
                                    <small>Rebounds</small>
                                </div>
                                <div class="col-6 mt-2">
                                    <h4>{{ $stat->steals ?? 0 }}</h4>
                                    <small>Steals</small>
                                </div>
                                <div class="col-6 mt-2">
                                    <h4>{{ $stat->blocks ?? 0 }}</h4>
                                    <small>Blocks</small>
                                </div>
                            </div>
                        @elseif($stat->sport_type == 'Football')
                            <div class="row text-center">
                                <div class="col-4">
                                    <h3 class="text-primary">{{ $stat->goals ?? 0 }}</h3>
                                    <small>Goals</small>
                                </div>
                                <div class="col-4">
                                    <h3 class="text-primary">{{ $stat->assists_football ?? 0 }}</h3>
                                    <small>Assists</small>
                                </div>
                                <div class="col-4">
                                    <h3 class="text-primary">{{ $stat->shots ?? 0 }}</h3>
                                    <small>Shots</small>
                                </div>
                                <div class="col-6 mt-2">
                                    <h4>{{ $stat->passes ?? 0 }}</h4>
                                    <small>Passes</small>
                                </div>
                                <div class="col-6 mt-2">
                                    <h4>{{ $stat->tackles ?? 0 }}</h4>
                                    <small>Tackles</small>
                                </div>
                            </div>
                        @else
                            <div class="row text-center">
                                <div class="col-4">
                                    <h3 class="text-primary">{{ $stat->spikes ?? 0 }}</h3>
                                    <small>Spikes</small>
                                </div>
                                <div class="col-4">
                                    <h3 class="text-primary">{{ $stat->blocks_volleyball ?? 0 }}</h3>
                                    <small>Blocks</small>
                                </div>
                                <div class="col-4">
                                    <h3 class="text-primary">{{ $stat->serves ?? 0 }}</h3>
                                    <small>Serves</small>
                                </div>
                                <div class="col-6 mt-2">
                                    <h4>{{ $stat->digs ?? 0 }}</h4>
                                    <small>Digs</small>
                                </div>
                                <div class="col-6 mt-2">
                                    <h4>{{ $stat->aces ?? 0 }}</h4>
                                    <small>Aces</small>
                                </div>
                            </div>
                        @endif

                        @if($stat->minutes_played)
                            <div class="mt-3">
                                <span class="badge bg-info">
                                    <i class="bi bi-clock"></i> {{ $stat->minutes_played }} min
                                </span>
                            </div>
                        @endif

                        @if($stat->notes)
                            <div class="mt-3">
                                <small class="text-muted">{{ $stat->notes }}</small>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('statistics.edit', $stat) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('statistics.destroy', $stat) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="row">
        <div class="col-12">
            {{ $statistics->links() }}
        </div>
    </div>
@else
    <!-- Empty State -->
    <div class="row">
        <div class="col-12">
            <div class="card text-center py-5">
                <div class="card-body">
                    <i class="bi bi-graph-up" style="font-size: 64px; color: #ccc;"></i>
                    <h3 class="mt-4">No statistics yet</h3>
                    <p class="text-muted">Start tracking your performance!</p>
                    <a href="{{ route('statistics.create') }}" class="btn btn-primary btn-lg mt-3">
                        <i class="bi bi-plus-circle"></i> Add Your First Stats
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
