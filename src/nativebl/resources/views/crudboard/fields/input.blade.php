<div class="mb-3 col-lg-6" >
    <label class="form-label" for="{{ $field->getName() }}">{{ $field->getLabel() }}</label>
    <input class="form-control  {{ $field->getCssClass() }}" id="{{ $field->getName() }}"  name="{{ $field->getName() }}" 
    type="{{ $field->getInputType() }}" placeholder="{{ $field->getHtmlAttributes()->get('placeholder')}}"  value="{{ old($field->getName(),$field->getValue()) }}" />
</div>