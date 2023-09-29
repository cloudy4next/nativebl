<!-- Content-->
<div class="content">
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title mb-0">{{$attributes['title']}}</h5>
        </div>
        <div class="card-body">
            @if ($errors->any())
            <div class="row">
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
            <form name="{{$form->getName()}}" action="{{$form->getActionUrl()}}" method="{{$form->getMethod()}}" class="{{$form->getCssClass()}}" {!! $form->getAttributesAsHtml() !!}>
                @foreach($form->getFields() as $field)
                <x-dynamic-component :component="$field->getComponent()" :$field />
                @endforeach
                @csrf
                @foreach($form->getActions()->getFormActions() as $action)
                @if($action->isSubmitAction())
                <button class="btn btn-primary {{ $action->getCssClass() }}" type="submit">{{ $action->getLabel() }}</button>
                @else
                <a href="{{route($action->getRouteName(),$action->getRouteParameters())}}" class="btn {{$action->getCssClass() }}"> {{$action->getLabel() }} </a>
                @endif
                @endforeach
            </form>
        </div>
    </div>
</div>