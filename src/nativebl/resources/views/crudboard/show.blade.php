<!-- Content-->
<div class="content">
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title mb-0">{{$attributes['title']}}</h5>
        </div>
        <div class="card-body">
        <table class="table table-sm table-bordered">
            <tbody>
                @foreach($show->getFields() as $field)
                <tr>
                        <th scope="row">{{$field->getLabel()}}</th> <td>{{$field->getValue()}}</td>
                </tr>
                @endforeach

            </tbody>
            </table>
            @foreach($show->getActions()->getShowActions() as $action)
            <a href="{{route($action->getRouteName(),$action->getRouteParameters())}}" class="btn {{$action->getCssClass() }}"> 
                @if($action->getIcon())
                <i class="fas {{$action->getIcon()}}"> </i>
                @endif
                {{$action->getLabel() }} 
            </a>
            @endforeach
        </div>
    </div>
</div>
