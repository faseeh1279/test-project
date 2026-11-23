<footer class="mt-5">

    <div class="container py-5">

        <div class="row g-5">

            {{-- About --}}
            <div class="col-lg-4">

                <h4 class="fw-bold mb-3">
                    <i class="bi bi-controller text-primary me-2"></i>
                    CanYouRunIt
                </h4>

                <p class="text-light-emphasis">
                    Discover whether your PC can run the latest games.
                    Compare your hardware with thousands of games and
                    find the perfect titles for your system.
                </p>

            </div>

            {{-- Quick Links --}}
            <div class="col-lg-2 col-md-6">

                <h5 class="mb-3">
                    Quick Links
                </h5>

                <ul class="list-unstyled">

                    <li class="mb-2">
                        <a href="{{ route('index') }}" class="footer-link">
                            Home
                        </a>
                    </li>

                    {{-- <li class="mb-2">
                        <a href="{{ route('games.index') }}" class="footer-link">
                            Games
                        </a>
                    </li>

                    <li class="mb-2">
                        <a href="{{ route('categories.index') }}" class="footer-link">
                            Categories
                        </a>
                    </li>

                    <li class="mb-2">
                        <a href="{{ route('news.index') }}" class="footer-link">
                            News
                        </a>
                    </li>

                    <li class="mb-2">
                        <a href="{{ route('community.index') }}" class="footer-link">
                            Community
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('community.guidelines') }}" class="footer-link">
                            Community Guidelines
                        </a>
                    </li> --}}

                </ul>

            </div>

            {{-- Support --}}
            <div class="col-lg-2 col-md-6">

                <h5 class="mb-3">
                    Support
                </h5>

                <ul class="list-unstyled">

                    {{-- <li class="mb-2">
                        <a href="{{ route('contact') }}" class="footer-link">
                            Contact
                        </a>
                    </li>

                    <li class="mb-2">
                        <a href="{{ route('faq') }}" class="footer-link">
                            FAQ
                        </a>
                    </li>

                    <li class="mb-2">
                        <a href="{{ route('privacy') }}" class="footer-link">
                            Privacy Policy
                        </a>
                    </li>

                    <li class="mb-2">
                        <a href="{{ route('terms') }}" class="footer-link">
                            Terms
                        </a>
                    </li> --}}

                </ul>

            </div>

            {{-- Contact --}}
            <div class="col-lg-4">

                <h5 class="mb-3">
                    Get in touch
                </h5>

                <p class="text-light-emphasis">
                    Have a question or spotted an issue with a game listing? Send us a message.
                </p>

                {{-- <a href="{{ route('contact') }}" class="btn btn-primary">Contact the team</a> --}}

            </div>

        </div>

        <hr class="border-secondary my-5">

        <div class="d-flex justify-content-between flex-wrap">

            <p class="mb-0 text-light-emphasis">

                © {{ date('Y') }} CanYouRunIt.
                All Rights Reserved.

            </p>

            <p class="mb-0 text-light-emphasis">

                Built with ❤️ using Laravel

            </p>`

        </div>

    </div>

</footer>