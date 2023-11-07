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
            <form name="{{$form->getName()}}" action="{{$form->getActionUrl()}}" method="{{$form->getMethod()}}" class="row g-3 {{$form->getCssClass()}}" {{ $form->getAttributesAsHtml() }}
            @if($form->getFields()->hasFileInput())
            enctype="multipart/form-data"
            @endif
            >
                @foreach($form->getFields() as $field)
                @php $htmlAttributes = $field->getAttributesAsHtml() ; @endphp
                 @if($field->isHiddenInput())
                     <x-dynamic-component :component="$field->getComponent()" :$field :$htmlAttributes />
                @else
                <div class="{{ $field->getLayoutClass() }}">
                     <x-dynamic-component :component="$field->getComponent()" :$field :$htmlAttributes />
                </div>
                @endif
                @endforeach
                @csrf
                <div class="mt-3 form-row">
                    @foreach($form->getActions()->getFormActions() as $action)
                     @if($action->shouldBeDisplayedFor(null))
                       @php $htmlActionAttributes = $action->getAttributesAsHtml() ; @endphp
                      <x-dynamic-component :component="$action->getComponent()" :$action :$htmlActionAttributes />
                     @endif
                     @endforeach
                </div>
            </form>
        </div>
    </div>
</div>
