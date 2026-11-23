@auth
    @if(auth()->user()->is_admin ?? false)

        <div class="bg-dark text-light py-2 border-bottom">
            <div class="container">

                <div class="d-flex justify-content-between align-items-center flex-wrap">

                    <div class="d-flex align-items-center">

                        <i class="bi bi-shield-lock-fill text-warning me-2"></i>

                        <span class="fw-semibold">
                            Logged in as Administrator
                        </span>

                    </div>

                    <div class="mt-2 mt-md-0">

                        <a href="{{ route('admin.dashboard.index') }}"
                           class="btn btn-warning btn-sm me-2">

                            <i class="bi bi-speedometer2 me-1"></i>

                            Dashboard

                        </a>

                        <form action="{{ route('logout') }}"
                              method="POST"
                              class="d-inline">

                            @csrf

                            <button type="submit"
                                    class="btn btn-outline-light btn-sm">

                                <i class="bi bi-box-arrow-right me-1"></i>

                                Logout

                            </button>

                        </form>

                    </div>

                </div>

            </div>
        </div>

    @endif
@endauth