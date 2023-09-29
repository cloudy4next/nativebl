<x-main-layout>
    <div class="content">
        <div class="mb-3">
            <h1 class="h3 d-inline align-middle">{{$articleDetails[0]['title']}} </h1>
        </div>
        <div class="row">
            <div class="col-md-9 col-xl-9s">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><b>Article Category: </b>{{$articleDetails[0]['articleCategory']['title']}} </h5>
                        <div class="card-body">
                            <div class="row g-0 mt-1">
                                <div class="col-12"><b>Complaint Path :</b> {{$articleDetails[0]['complaint_path']}} </div>
                                <div class="col-12"><b>Start Date : </b>{{ date("F j, Y", strtotime($articleDetails[0]['start_date'])) }} </div>
                                <div class="col-12"><b>End Date :</b> {{$articleDetails[0]['end_date']}} </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-12">
                                    {!! html_entity_decode($articleDetails[0]['content']) !!}
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-xl-3s">
            <div class="card mb-3">

                <div class="card">
                    <div class="card-body pb-0">
                      <h5 class="card-title">{{$articleDetails[0]['title']}}  <span>| History</span></h5>
                      @if ($articleDetails->count())
                        @foreach ($articleDetails as $parent)

                        @if ($parent->parentTree != null)

                              <div class="news">
                                <div class="post-item clearfix">
                                  <h4><a href="#">{{ $parent->parentTree->title }}</a></h4>
                                  <p>{!! html_entity_decode(substr($parent->parentTree->content, 0, 150).'...') !!}...</p>
                                </div>               

                              </div><!-- End sidebar recent posts-->
                            @endif
                          @endforeach
                      @endif

                    </div>
                  </div>

        </div>
    </div>
    </div>
    </div>
</x-main-layout>

