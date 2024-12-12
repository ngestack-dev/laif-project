<footer class="footer footer_type_2">
    <div class="footer-middle container d-flex justify-content-center align-items-center">
        <div class="footer-column footer-store-info col-12 mb-4 mb-lg-0 text-center">
            <div class="logo">
                <a href="{{ route('home.index') }}">
                    <img src="{{ asset('assets/images/logo/PNG/Master Logo Laif Essentials-02.png') }}" alt="Laif"
                        class="logo__image d-block mx-auto" width="" height="" />
                </a>
            </div>
            @if ($about)
                <p class="footer-address">{{ $about->address }}</p>
                <p class="m-0"><strong class="fw-medium">{{ $about->email_laif }}</strong></p>
                <ul
                    class="social-links list-unstyled d-flex flex-wrap justify-content-center align-items-center m-auto">
                    <li>
                        <a href="https://wa.me/{{ $about->phone_laif }}" class="footer__social-link d-block">
                            <svg class="svg-icon svg-icon_whatsapp" width="28" height="26" viewBox="0 0 16 16"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M13.6 2.4C12.1 0.9 10.1 0 8 0C3.6 0 0 3.6 0 8C0 9.6 0.5 11.2 1.3 12.5L0 16L3.5 14.7C4.8 15.5 6.4 16 8 16C12.4 16 16 12.4 16 8C16 5.9 15.1 3.9 13.6 2.4ZM8 14.4C6.6 14.4 5.2 14 4 13.2L3.7 13.1L2 13.7L2.6 12L2.5 11.7C1.8 10.5 1.6 9.3 1.6 8C1.6 4.4 4.4 1.6 8 1.6C9.7 1.6 11.3 2.3 12.4 3.6C13.5 4.8 14.2 6.4 14.2 8C14.2 11.6 11.4 14.4 8 14.4ZM11.2 9.8C10.8 9.6 10.4 9.5 10 9.6C9.8 9.7 9.6 9.6 9.3 9.5C9.1 9.4 8.5 9.1 8.1 8.6C7.7 8.2 7.6 7.7 7.5 7.5C7.4 7.3 7.4 7.1 7.5 6.9C7.5 6.7 7.4 6.5 7.4 6.4C7.3 6.3 7.1 6.2 6.8 6.1C6.5 6 6.3 6 6.1 6C5.9 6 5.7 6.1 5.6 6.2C5.4 6.3 5.1 6.6 5.2 7.3C5.3 7.9 5.6 8.6 6.1 9.3C6.7 10.1 7.3 10.7 8.1 11.1C8.9 11.5 9.7 11.7 10.4 11.7C10.8 11.7 11.2 11.7 11.5 11.6C11.9 11.5 12.2 11.3 12.3 11.2C12.4 11.1 12.4 11 12.3 10.9C12.3 10.8 12.2 10.7 12.1 10.6C11.8 10.3 11.5 10.1 11.2 9.8Z" />
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.instagram.com/{{ $about->instagram }}" class="footer__social-link d-block">
                            <svg class="svg-icon svg-icon_instagram" width="28" height="26" viewBox="0 0 14 13"
                                xmlns="http://www.w3.org/2000/svg">
                                <use href="#icon_instagram" />
                            </svg>
                        </a>
                    </li>
                </ul>
            @endif
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container d-md-flex align-items-center">
            <span class="footer-copyright m-auto">©2024 Laif</span>
            {{-- <div class="footer-settings d-md-flex align-items-center">
                <a href="privacy-policy.html">Privacy Policy</a> &nbsp;|&nbsp; <a href="terms-conditions.html">Terms
                    &amp;
                    Conditions</a>
            </div> --}}
        </div>
    </div>
</footer>
