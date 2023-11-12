<x-main-layout>
    <div class="content">
        <div class="mb-3">
            <h1 class="h3 d-inline align-middle">{{$articleDetails['title']}} </h1>
        </div>
        <div class="row">
            <div class="col-md-12 col-xl-12s">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><b>Article Category: </b>{{$articleDetails['articleCategory']['title']}} </h5>
                        <div class="card-body">
                            <div class="row g-0 mt-1">
                                <div class="col-md-7 col-xl-7s">
                                    <div class="col-12"><b>Service Manager :</b> {{$articleDetails['service_manager']}} </div>
                                    <div class="col-12"><b>Call Disposition Code :</b> {{$articleDetails['call_disposition_code']}} </div>
                                    <div class="col-12"><b>Complaint Path :</b> {{$articleDetails['complaint_path']}} </div>
                                    <div class="col-12"><b>Start Date : </b>{{ ($articleDetails['start_date'] != null)?date("F j, Y", strtotime($articleDetails['start_date'])):'' }} </div>
                                    <div class="col-12"><b>End Date :</b> {{ ($articleDetails['end_date'] != null)?date("F j, Y", strtotime($articleDetails['end_date'])):'' }} </div>
                            </div>
                            <div class="col-md-5 col-xl-5s">
                                @if($articleDetails['image'] != 'NULL' && $articleDetails['image'] != '')
                                <a href="{{env('APP_URL')}}/{{$articleDetails['image']}}" target="_blank"><img src=" {{env('APP_URL')}}/{{$articleDetails['image']}} " width="100%" alt="Image" />
                                </a>
                                @endif
                                <br>
                                <div class="col-12" style="font-size: 17px; font-weight: bold;">
                                    {!! html_entity_decode($articleDetails['link_redirection']) !!}
                                </div>
                            </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-12">
                                    {!! html_entity_decode($articleDetails['content']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <div class="accordion" id="accordion_faq">
                            <h5 class="card-title">FAQ</h5>
                            @if($articleDetails['articleFaq'] != null)
                                @php
                                    $counter = 0;
                                @endphp
                                @forelse ($articleDetails['articleFaq'] as $faq)
                                @php
                                    $counter++;
                                @endphp
                                @php
                                if($counter > 5){
                                    break;
                                }
                                @endphp
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="faq_{{$loop->index+1}}">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#collapse_faq_{{$loop->index+1}}" aria-expanded="true"
                                                    aria-controls="collapse_faq_{{$loop->index+1}}">
                                                {!! $faq->question !!}
                                            </button>
                                        </h2>
                                        <div id="collapse_faq_{{$loop->index+1}}"
                                             class="accordion-collapse collapse {{$loop->index==0?'show':''}}"
                                             aria-labelledby="headingOne"
                                             data-bs-parent="#accordion_faq">
                                            <div class="accordion-body">
                                                {!! html_entity_decode($faq->answer) !!}
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="accordion-item">
                                        <div class="accordion-body">
                                            <span class="text-center">No FAQ</span>
                                        </div>
                                    </div>
                                @endforelse
                            @endif
                        </div>
                        @if (count($articleDetails->articleFaq) > 5)
                            @include('home.TigerWeb.Article.faq_modal', ['faqs' => $articleDetails->articleFaq])
                        @endif
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><b>Article Review: </b></h5>
                        <div class="card-body">
                            <form action="{{ route('article_review_submit') }}" method="post">
                                @csrf

                                <textarea class="form-control" rows="4" name="review_comments"></textarea>

                                <br>
                                <input type="hidden" name="article_id" value="{{$articleDetails['id']}}">
                                <input type="hidden" name="review_status" value="NEED CORRECTION">
                                <input class="btn btn-primary" type="submit">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main-layout>

