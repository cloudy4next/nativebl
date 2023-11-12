<div class="py-2">
    <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#staticBackdropLive">See More FAQ</button>
</div>

<style>
    .modal{
        --bs-modal-width:95% !important;
        --bs-modal-height:95% !important;
    }
    </style>

<div class="modal fade" id="staticBackdropLive" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLiveLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLiveLabel">FAQ</h1>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="bd-example-snippet bd-code-snippet">
                    <div class="bd-example">
                        {{-- accordion style start --}}
                        <div class="accordion" id="accordion_faq">
                            @foreach($articleDetails->articleFaq->skip(5) as $faq)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faq_{{$loop->index+6}}">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapse_faq_{{$loop->index+6}}" aria-expanded="true"
                                                aria-controls="collapse_faq_{{$loop->index+6}}">
                                            {!! $faq->question !!}
                                        </button>
                                    </h2>
                                    <div id="collapse_faq_{{$loop->index+6}}"
                                         class="accordion-collapse collapse {{$loop->index==0?'show':''}}"
                                         aria-labelledby="headingOne"
                                         data-bs-parent="#accordion_faq">
                                        <div class="accordion-body">
                                            {!! html_entity_decode($faq->answer) !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        {{-- accordion style end --}}
{{--                        <div class="carousel slide" id="carouselExampleCaptions" data-bs-ride="carousel">--}}
{{--                            <div class="carousel-indicators">--}}
{{--                                @for($counter = 0; ($counter < count($faqs) - 5); $counter++)--}}
{{--                                    @if($counter == 0)--}}
{{--                                    <button class="active" type="button" data-bs-target="#carouselExampleCaptions"--}}
{{--                                        data-bs-slide-to="0" aria-current="true" aria-label="Slide 1"></button>--}}
{{--                                    @else--}}
{{--                                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"--}}
{{--                                        aria-label="Slide 2"></button>--}}
{{--                                    @endif--}}
{{--                                @endfor--}}
{{--                            </div>--}}
{{--                            <div class="carousel-inner">--}}
{{--                                @php $faqs = $faqs->skip(5);  @endphp--}}
{{--                                @foreach($faqs as $key => $faq)--}}
{{--                                    @if($key == 5)--}}
{{--                                    <div class="carousel-item active">--}}
{{--                                        <svg class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800"--}}
{{--                                            height="900" xmlns="http://www.w3.org/2000/svg" role="img"--}}
{{--                                            aria-label="Placeholder: First slide" preserveaspectratio="xMidYMid slice"--}}
{{--                                            focusable="false">--}}
{{--                                            <title>{{ $faq->question }}</title>--}}
{{--                                            <rect width="100%" height="100%" fill="#777"></rect>--}}
{{--                                            <text x="50%" y="50%" fill="#555" dy=".3em">{{ $faq->question }}</text>--}}
{{--                                        </svg>--}}
{{--                                        <div class="carousel-caption d-none d-md-block">--}}
{{--                                            <h5>{{ $faq->question }}</h5>--}}
{{--                                            <p>{!! html_entity_decode($faq->answer) !!}</p>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    @else--}}
{{--                                    <div class="carousel-item">--}}
{{--                                        <svg class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800"--}}
{{--                                            height="900" xmlns="http://www.w3.org/2000/svg" role="img"--}}
{{--                                            aria-label="Placeholder: First slide" preserveaspectratio="xMidYMid slice"--}}
{{--                                            focusable="false">--}}
{{--                                            <title>{{ $faq->question }}</title>--}}
{{--                                            <rect width="100%" height="100%" fill="#777"></rect>--}}
{{--                                            <text x="50%" y="50%" fill="#555" dy=".3em">{{ $faq->question }}</text>--}}
{{--                                        </svg>--}}
{{--                                        <div class="carousel-caption d-none d-md-block">--}}
{{--                                            <h5>{{ $faq->question }}</h5>--}}
{{--                                            <p>{!! html_entity_decode($faq->answer) !!}</p>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    @endif--}}
{{--                                @endforeach--}}
{{--                            </div>--}}
{{--                            <button class="carousel-control-prev" type="button"--}}
{{--                                data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">--}}
{{--                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>--}}
{{--                                <span class="visually-hidden">Previous</span>--}}
{{--                            </button>--}}
{{--                            <button class="carousel-control-next" type="button"--}}
{{--                                data-bs-target="#carouselExampleCaptions" data-bs-slide="next">--}}
{{--                                <span class="carousel-control-next-icon" aria-hidden="true"></span>--}}
{{--                                <span class="visually-hidden">Next</span>--}}
{{--                            </button>--}}
{{--                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">&nbsp;
        </div>
    </div>
</div>
