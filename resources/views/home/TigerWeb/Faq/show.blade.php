<x-main-layout>
    <x-slot:title>
        NativeBL:: FAQ view
    </x-slot:title>

    <div class="content">

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
                                    {!! html_entity_decode($faq->answer) !!}
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

