@extends('statistics.layout')

@section('title', 'Add Statistics')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="text-white"><i class="bi bi-plus-circle"></i> Add Statistics</h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('statistics.store') }}" method="POST">
                    @csrf

                    <!-- Sport Type -->
                    <div class="mb-3">
                        <label class="form-label">Sport Type <span class="text-danger">*</span></label>
                        <select name="sport_type" id="sport_type" class="form-select @error('sport_type') is-invalid @enderror" required>
                            <option value="">Select Sport</option>
                            <option value="Basketball" {{ old('sport_type') == 'Basketball' ? 'selected' : '' }}>Basketball</option>
                            <option value="Football" {{ old('sport_type') == 'Football' ? 'selected' : '' }}>Football</option>
                            <option value="Volleyball" {{ old('sport_type') == 'Volleyball' ? 'selected' : '' }}>Volleyball</option>
                        </select>
                        @error('sport_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Game Date -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Game Date <span class="text-danger">*</span></label>
                            <input type="date" name="game_date" class="form-control @error('game_date') is-invalid @enderror" value="{{ old('game_date', date('Y-m-d')) }}" required>
                            @error('game_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Minutes Played</label>
                            <input type="number" name="minutes_played" class="form-control @error('minutes_played') is-invalid @enderror" value="{{ old('minutes_played') }}" min="0">
                            @error('minutes_played')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <!-- Basketball Stats -->
                    <div id="basketball_stats" class="sport-stats" style="display: none;">
                        <h5 class="mb-3"><i class="bi bi-dribbble"></i> Basketball Statistics</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Points</label>
                                <input type="number" name="points" class="form-control" value="{{ old('points') }}" min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Assists</label>
                                <input type="number" name="assists" class="form-control" value="{{ old('assists') }}" min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Rebounds</label>
                                <input type="number" name="rebounds" class="form-control" value="{{ old('rebounds') }}" min="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Steals</label>
                                <input type="number" name="steals" class="form-control" value="{{ old('steals') }}" min="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Blocks</label>
                                <input type="number" name="blocks" class="form-control" value="{{ old('blocks') }}" min="0">
                            </div>
                        </div>
                    </div>

                    <!-- Football Stats -->
                    <div id="football_stats" class="sport-stats" style="display: none;">
                        <h5 class="mb-3"><i class="bi bi-trophy"></i> Football Statistics</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Goals</label>
                                <input type="number" name="goals" class="form-control" value="{{ old('goals') }}" min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Assists</label>
                                <input type="number" name="assists_football" class="form-control" value="{{ old('assists_football') }}" min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Shots</label>
                                <input type="number" name="shots" class="form-control" value="{{ old('shots') }}" min="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Passes</label>
                                <input type="number" name="passes" class="form-control" value="{{ old('passes') }}" min="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tackles</label>
                                <input type="number" name="tackles" class="form-control" value="{{ old('tackles') }}" min="0">
                            </div>
                        </div>
                    </div>

                    <!-- Volleyball Stats -->
                    <div id="volleyball_stats" class="sport-stats" style="display: none;">
                        <h5 class="mb-3"><i class="bi bi-volleyball"></i> Volleyball Statistics</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Spikes</label>
                                <input type="number" name="spikes" class="form-control" value="{{ old('spikes') }}" min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Blocks</label>
                                <input type="number" name="blocks_volleyball" class="form-control" value="{{ old('blocks_volleyball') }}" min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Serves</label>
                                <input type="number" name="serves" class="form-control" value="{{ old('serves') }}" min="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Digs</label>
                                <input type="number" name="digs" class="form-control" value="{{ old('digs') }}" min="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Aces</label>
                                <input type="number" name="aces" class="form-control" value="{{ old('aces') }}" min="0">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Notes -->
                    <div class="mb-3">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Any additional notes about the game...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Save Statistics
                        </button>
                        <a href="{{ route('statistics.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-info-circle"></i> Quick Info</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="bi bi-check-circle text-success"></i> Fill in your game statistics</li>
                    <li class="mb-2"><i class="bi bi-check-circle text-success"></i> Track your progress over time</li>
                    <li class="mb-2"><i class="bi bi-check-circle text-success"></i> Compare your performance</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('sport_type').addEventListener('change', function() {
        // Hide all stat sections
        document.querySelectorAll('.sport-stats').forEach(el => el.style.display = 'none');
        
        // Show selected sport stats
        const selectedSport = this.value.toLowerCase();
        if (selectedSport) {
            const statsDiv = document.getElementById(selectedSport + '_stats');
            if (statsDiv) {
                statsDiv.style.display = 'block';
            }
        }
    });

    // Trigger on page load if sport type is already selected
    window.addEventListener('load', function() {
        const sportSelect = document.getElementById('sport_type');
        if (sportSelect.value) {
            sportSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush
