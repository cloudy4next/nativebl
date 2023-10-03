<footer class="footer">
    <div class="container-fluid">
        <div class="row text-muted">
            <div class="col-6 text-start">
                @if (session()->has('applicationID') == 4)
                <p class="mb-0">
                    <a class="text-muted" href="/all-campaign">
                        <strong>NativeBL</strong>
                    </a> &copy;<i>2023 TOFFEE. All Rights Reserved.</i>
                </p>
            @else
                <p class="mb-0">
                    <a class="text-muted" href="index.html">
                        <strong>NativeBL</strong>
                    </a> &copy;<i>Banglalink Digital Communication Ltd.</i>
                </p>
            @endif

            </div>
            {{-- <div class="col-6 text-end">
                <ul class="list-inline">
                    <li class="list-inline-item">
                        <a class="text-muted" href="#">Support</a>
                    </li>
                    <li class="list-inline-item">
                        <a class="text-muted" href="#">Help Center</a>
                    </li>
                    <li class="list-inline-item">
                        <a class="text-muted" href="#">Privacy</a>
                    </li>
                    <li class="list-inline-item">
                        <a class="text-muted" href="#">Terms</a>
                    </li>
                </ul>
            </div> --}}
        </div>
    </div>
</footer>
