<footer class="mt-5 py-5">
    <div class="row container mx-auto pt-5">
        <div class="footer-one col-lg-3 col-md-6 col-12">
            <img src="{{ asset('Images/team_logo2.png') }}" alt="">
            <p class="pt-3">Transform your body, elevate your mind, and unlock your full potential—every step counts on your fitness journey!</p>
        </div>
        <div class="footer-one col-lg-3 col-md-6 col-12 mb-3">
            <h5 class="pb-2">Featured</h5>
            <ul class="text-uppercase list-unstyled">
                <li><a href="#">PROTEIN</a></li>
                <li><a href="#">SUPPLEMENTS</a></li>
                <li><a href="#">VITAMINS</a></li>
                <li><a href="#">WORKOUTS</a></li>
                <li><a href="#">PRE WORKOUT</a></li>
            </ul>
        </div>
        <div class="footer-one col-lg-3 col-md-6 col-12 mb-3">
            <h5 class="pb-2">Contact Us</h5>
            <div>
                <h6 class="text-uppercase">Address</h6>
                <p>Aston St, Birmingham B4 7ET</p>
            </div>
            <div>
                <h6 class="text-uppercase">Phone</h6>
                <p>0121 204 3000</p>
            </div>
            <div>
                <h6 class="text-uppercase">Email</h6>
                <p>Aston35@info.com</p>
            </div>
        </div>
        <div class="footer-one col-lg-3 col-md-6 col-12 mb-3">
            <h5 class="pb-2">Terms and Conditions</h5>
            <div>
                <ul class="text-uppercase list-unstyled">
                    <li><a href="{{ route('privacy-policy') }}">PRIVACY POLICY</a></li>
                    <li><a href="{{ route('terms-of-service') }}">TERMS OF SERVICE</a></li>
                    <li><a href="{{ route('modern-day-slavery-statement') }}">MODERN DAY SLAVERY STATEMENT</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="copyright mt-5">
        <div class="row container mx-auto">
            <div class="col-lg-3 col-md-6 col-12 mb-4">
                <img src="{{ asset('img/payment.png') }}" alt="">
            </div>
            <div class="col-lg-4 col-md-6 col-12 text-nowrap mb-2">
                <p>Aston35 LTD © {{ date('Y') }}. All Rights Reserved</p>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <a href="https://www.facebook.com/?locale2=en_GB&_rdr"><i class="fab fa-facebook-f"></i></a>
                <a href="https://x.com/i/flow/login"><i class="fab fa-twitter"></i></a>
                <a href="https://uk.linkedin.com/"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
    </div>
</footer>