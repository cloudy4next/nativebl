<div class="news"><!-- start right side history-->
    <div class="post-item clearfix">
        <h4><a href="{{ route('article_detail', $items['id']) }}">{{ $items['title'] }}</a></h4>
        <!-- <p>{!! html_entity_decode(substr(trim($items['content']), 0, 150).'...') !!}...</p> -->
        <p><b>Updated by: </b>{{ $items['updatedByUser']->user_name }}</p>
        <p><b>Updated at: </b>{{ date("F j, Y", strtotime($items['updated_at'])) }}</p>
        <hr>
    </div>
</div><!-- End right side history-->

@if ($items['parentTree'] != null)
    @include('home.TigerWeb.Article.edit_history', ['items' => $items['parentTree']])
@endif
