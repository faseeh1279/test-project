@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid">


    <!-- Statistics Cards -->
    <div class="row g-4">

        <!-- Total Games -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary text-white rounded-circle p-3 me-3">
                        <i class="bi bi-controller fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Total Games</h6>
                        <h3 class="mb-0">1,250</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success text-white rounded-circle p-3 me-3">
                        <i class="bi bi-people fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Total Users</h6>
                        <h3 class="mb-0">15,400</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning text-white rounded-circle p-3 me-3">
                        <i class="bi bi-chat-square-text fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Reviews</h6>
                        <h3 class="mb-0">8,650</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Favorites -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-danger text-white rounded-circle p-3 me-3">
                        <i class="bi bi-heart fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Favorites</h6>
                        <h3 class="mb-0">24,300</h3>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Second Row -->
    <div class="row g-4 mt-2">

        <!-- Categories -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Categories</h6>
                    <h2>45</h2>
                    <small class="text-success">+5 this month</small>
                </div>
            </div>
        </div>

        <!-- Developers -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Developers</h6>
                    <h2>320</h2>
                    <small>Registered studios</small>
                </div>
            </div>
        </div>

        <!-- Compatibility Checks -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Compatibility Checks</h6>
                    <h2>52,600</h2>
                    <small>User system checks</small>
                </div>
            </div>
        </div>

        <!-- News -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">News Articles</h6>
                    <h2>680</h2>
                    <small>Published articles</small>
                </div>
            </div>
        </div>

    </div>

    <!-- Quick Actions -->
    <div class="d-flex flex-wrap gap-2 my-4">
        @if (Route::has('admin.games.create'))
        <a href="{{ route('admin.games.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Add Game
        </a>
        @endif

        <a href="#" class="btn btn-success">
            <i class="bi bi-tags me-2"></i>Add Category
        </a>

        <a href="#" class="btn btn-info text-white">
            <i class="bi bi-newspaper me-2"></i>Create News
        </a>

        <a href="#" class="btn btn-warning">
            <i class="bi bi-cpu me-2"></i>Add Hardware
        </a>

        <a href="#" class="btn btn-dark">
            <i class="bi bi-people me-2"></i>Manage Users
        </a>
    </div>

    <!-- Latest Games -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Latest Games</h5>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Game</th>
                        <th>Category</th>
                        <th>Release</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>GTA VI</td>
                        <td>Action</td>
                        <td>2026</td>
                        <td><span class="badge bg-success">Published</span></td>
                    </tr>

                    <tr>
                        <td>Elden Ring</td>
                        <td>RPG</td>
                        <td>2022</td>
                        <td><span class="badge bg-success">Published</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pending Reviews -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Pending Reviews</h5>
        </div>

        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between">
                    GTA VI
                    <span class="badge bg-warning">Pending</span>
                </li>

                <li class="list-group-item d-flex justify-content-between">
                    Cyberpunk 2077
                    <span class="badge bg-warning">Pending</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Popular Tags -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Popular Tags</h5>
        </div>

        <div class="card-body">
            <span class="badge bg-primary me-2 mb-2">Action</span>
            <span class="badge bg-success me-2 mb-2">Adventure</span>
            <span class="badge bg-danger me-2 mb-2">RPG</span>
            <span class="badge bg-warning text-dark me-2 mb-2">Multiplayer</span>
            <span class="badge bg-dark me-2 mb-2">Strategy</span>
            <span class="badge bg-info me-2 mb-2">Open World</span>
        </div>
    </div>

    <!-- Tables Section -->
    <div class="row mt-4 g-4">

        <!-- Latest Reviews -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Latest Reviews</h5>
                </div>

                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">GTA VI received 5 stars</li>
                        <li class="list-group-item">Cyberpunk 2077 reviewed</li>
                        <li class="list-group-item">Elden Ring reviewed</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Popular Games -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Most Popular Games</h5>
                </div>

                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>GTA V</span>
                        <span>15,000 views</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Minecraft</span>
                        <span>12,400 views</span>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span>Elden Ring</span>
                        <span>9,800 views</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection