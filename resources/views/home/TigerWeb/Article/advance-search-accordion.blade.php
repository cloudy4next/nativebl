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

        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="accordion" id="accordion_faq">
                            <h2 class="card-title">FAQ</h2>
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
                                         class="accordion-collapse collapse"
                                         aria-labelledby="headingOne"
                                         data-bs-parent="#accordion_faq">
                                        <div class="accordion-body">
                                            {!! $faq->answer !!}
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
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">Article</h2>
                        @forelse ($articleResults as $article)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">{!! $article->title !!}</h5>
                                    <p class="card-text">
                                        @if(strlen(strip_tags($article->content))>200)
                                            {!! Str::limit($article->content, 200) !!}
                                            ... <a
                                                href={{route('article_detail', $article->article_id)}} class="card-link">Read
                                                More</a>
                                        @else
                                            {!! $article->content !!}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @if($loop->index >= 0 && $loop->index != count($articleResults)-1)
                                <hr>
                            @endif
                        @empty
                            <div class="card mb-3">
                                <div class="card-body">
                                    <span class="text-center">No Result</span>
                                </div>
                            </div>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>

    </div>

</x-main-layout>

