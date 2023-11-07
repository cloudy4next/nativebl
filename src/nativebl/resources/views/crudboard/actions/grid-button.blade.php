
@if($rowAction->isGridDeleteAction())
<a  class="btn  {{ $rowAction->getCssClass() }} btn-sm" onclick="return gridDeleteConfirm(this)" 
 href="{{route($rowAction->getRouteName(), $routeParams) }}" {{ $htmlAttributes }}  data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
    <i class="fas {{$rowAction->getIcon() }}"></i>
</a>
@else
<a  class="btn  {{ $rowAction->getCssClass() }} btn-sm" href="{{route($rowAction->getRouteName(), $routeParams) }}" {{ $htmlAttributes }} 
data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $rowAction->getLabel() }}"
>
    <i class="fas {{$rowAction->getIcon() }}"></i>
</a>
@endif
