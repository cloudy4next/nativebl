<x-main-layout>
    <x-slot:title>
        NativeBL:: Article Advance Search
    </x-slot:title>

    <div class="content">

        <div class="row mb-2">
            <div class="col-12">
                <h1 class="h3 d-inline align-middle">Article Advance Search</h1>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="get" name="filter" class="row g-3">
                            <div class="row">
                                <div class=" mt-3 col-md-12">
                                    <label class="form-label" for="search_text">Search Text </label>
                                    <input class="form-control" name="search_term" type="text"
                                           placeholder="Search Text" value=""></div>
                                <div class="col-lg-12 text-end mt-3">
                                    <button type="submit" class="btn btn-success">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="accordion" id="accordion_faq">
                    <h5 class="card-title">FAQ</h5>
                    @forelse ($faqResults as $faq)
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
                                    {!! Str::limit($faq->answer, 200) !!}
                                    ... <a href="{{ route('faq_detail', $faq->faq_id)}}">Read More</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="accordion-item">
                            <div class="accordion-body">
                                <span class="text-center">No Result</span>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="accordion mt-5" id="accordion_article">
                    <h5 class="card-title">Article</h5>
                    @forelse ($articleResults as $article)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="article_{{$loop->index+1}}">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse_article_{{$loop->index+1}}" aria-expanded="true"
                                        aria-controls="collapse_article_{{$loop->index+1}}">
                                    {!! $article->title !!}
                                </button>
                            </h2>
                            <div id="collapse_article_{{$loop->index+1}}"
                                 class="accordion-collapse collapse {{$loop->index==0?'show':''}}"
                                 aria-labelledby="headingOne"
                                 data-bs-parent="#accordion_article">
                                <div class="accordion-body">
                                    {!!  Str::limit($article->content, 200)  !!}
                                    ... <a href="{{  route('article_detail', $article->article_id)  }}">Read More</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="accordion-item">
                            <div class="accordion-body">
                                <span class="text-center">No Result</span>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</x-main-layout>

