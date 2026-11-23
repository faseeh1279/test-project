<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Can You Run It')</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Google Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <style>

        body{
            font-family:'Poppins',sans-serif;
            background:#f5f7fb;
        }

        .section-padding{
            padding:90px 0;
        }

        .hero{
            background:linear-gradient(135deg,#0d6efd,#1f2d5a);
            color:#fff;
        }

        .hero-title{
            font-size:3rem;
            font-weight:700;
        }

        .hero-subtitle{
            font-size:1.1rem;
            opacity:.9;
        }

        .shadow-soft{
            box-shadow:0 10px 30px rgba(0,0,0,.08);
        }

        .game-card{
            transition:.3s;
            border:0;
            border-radius:16px;
        }

        .game-card:hover{
            transform:translateY(-8px);
            box-shadow:0 15px 40px rgba(0,0,0,.15);
        }

        footer{
            background:#111827;
            color:#fff;
        }
        .game-card{
    border-radius:20px;
    overflow:hidden;
    transition:.35s ease;
}

.game-card:hover{
    transform:translateY(-10px);
    box-shadow:0 20px 50px rgba(0,0,0,.15);
}

.game-card img{
    height:320px;
    object-fit:cover;
}

.game-card .btn{
    border-radius:10px;
}

.shadow-soft{
    box-shadow:0 10px 35px rgba(0,0,0,.08);
}

.section-padding{
    padding:90px 0;
}

footer{
    background:#111827;
    color:#fff;
}

.footer-link{
    color:#b9c2d0;
    text-decoration:none;
    transition:.3s;
}

.footer-link:hover{
    color:#fff;
    padding-left:5px;
}

footer .form-control{
    border-radius:10px 0 0 10px;
}

footer .btn{
    border-radius:0 10px 10px 0;
}

.game-card{
    border:none;
    border-radius:20px;
    overflow:hidden;
    transition:.35s ease;
    background:#fff;
}

.game-card:hover{
    transform:translateY(-12px);
    box-shadow:0 25px 60px rgba(0,0,0,.18);
}

.game-card img{
    height:340px;
    object-fit:cover;
}

.game-card .badge{
    font-size:.75rem;
}

.game-card .btn{
    border-radius:10px;
}

.icon-box{
    width:90px;
    height:90px;
    border-radius:50%;
    display:flex;
    justify-content:center;
    align-items:center;
    font-size:2rem;
}

.shadow-soft{
    transition:.35s;
}

.shadow-soft:hover{
    transform:translateY(-10px);
    box-shadow:0 20px 50px rgba(0,0,0,.15);
}

.category-card{
    background:#fff;
    border-radius:20px;
    padding:35px 20px;
    text-align:center;
    transition:.35s;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
    height:100%;
}

.category-card:hover{
    transform:translateY(-10px);
    box-shadow:0 20px 50px rgba(0,0,0,.15);
}

.category-icon{
    width:90px;
    height:90px;
    margin:auto;
    border-radius:50%;
    display:flex;
    justify-content:center;
    align-items:center;
    color:#fff;
    font-size:2rem;
}

.feature-card{
    background:#fff;
    border-radius:20px;
    padding:35px;
    height:100%;
    text-align:center;
    transition:.35s;
    box-shadow:0 12px 35px rgba(0,0,0,.08);
}

.feature-card:hover{
    transform:translateY(-10px);
    box-shadow:0 20px 50px rgba(0,0,0,.15);
}

.feature-icon{
    width:90px;
    height:90px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#fff;
    font-size:2.2rem;
    margin:auto;
}

.hardware-item{

    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:16px 0;

    border-bottom:1px solid #ececec;

    transition:.3s;
}

.hardware-item:last-child{

    border-bottom:none;

}

.hardware-item:hover{

    padding-left:12px;

    background:#f8f9fa;

    border-radius:10px;

}

.hardware-item .badge{

    font-size:.9rem;
    padding:8px 12px;

}

.form-control-lg,
.form-select-lg{

    border-radius:12px;

}

.bg-dark .card{

    border-radius:24px;

}

.bg-dark .card-body{

    background:#fff;

    color:#212529;

}

.news-card{

    border-radius:20px;
    overflow:hidden;
    transition:.35s;

}

.news-card:hover{

    transform:translateY(-10px);
    box-shadow:0 20px 50px rgba(0,0,0,.15);

}

.news-card img{

    height:230px;
    object-fit:cover;

}

.news-card h4{

    line-height:1.4;

}

.testimonial-card{

    border-radius:20px;
    transition:.35s;

}

.testimonial-card:hover{

    transform:translateY(-10px);

    box-shadow:0 20px 50px rgba(0,0,0,.15);

}

.testimonial-card img{

    object-fit:cover;

}

.accordion{

    border-radius:20px;

}

.accordion-item{

    border:none;

}

.accordion-button{

    font-weight:600;
    padding:22px;

}

.accordion-button:not(.collapsed){

    background:#0d6efd;
    color:#fff;

}

.accordion-button:focus{

    box-shadow:none;

}

.bg-primary .form-control{

    border-radius:14px;
    border:none;

}

.bg-primary .btn{

    border-radius:14px;

}

.bg-primary{

    background:linear-gradient(135deg,#0d6efd,#3b82f6)!important;

}

.cta-section{

    background:linear-gradient(135deg,#0f172a,#1e293b,#2563eb);
    color:#fff;

}

.cta-section .btn{

    min-width:220px;
    border-radius:14px;

}

.cta-section h2{

    line-height:1.2;

}

#final-callback{
    margin-bottom: -50px;
}

.admin-top-bar {
            background-color: #1e293b;
            border-bottom: 1px solid #334155;
}
    </style>

    @stack('styles')

</head>

<body>

    @include('frontend.partials.admin-bar')

    {{-- @can('is-admin') --}}
    @if(Gate::allows('is-admin'))
        <div class="admin-top-bar py-2 px-3 shadow-sm">
            <div class="container d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center text-white-50 small">
                    <span class="badge bg-danger text-uppercase font-monospace me-2"
                        style="font-size: 0.75rem; letter-spacing: 0.05em;">Admin Mode</span>
                    <span class="d-none d-sm-inline">You are logged in as an administrator.</span>
                </div>
                <a class="btn btn-sm btn-outline-warning rounded-pill py-1 px-3 fw-semibold text-decoration-none"
                    href="{{ route('admin.dashboard.index') }}">
                    <i class="bi bi-speedometer2 me-1"></i> Admin Panel
                </a>
            </div>
        </div>
    @endif
    {{-- @endcan --}}
    {{-- {{ dd(auth()->user()->role) }} --}}
    {{-- {{ Gate::allows('is-admin') ? 'YES' : 'NO' }} --}}
    {{-- {{ auth()->user()->can('is-admin')}} --}}
    @include('frontend.partials.navbar')

    <main>

        @yield('content')

    </main>

    @include('frontend.partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

</body>
</html>