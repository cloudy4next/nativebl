<div class="mb-3">
    <label class="form-label" for="{{ $field->getName() }}">{{ $field->getLabel() }}</label>
    <input class="form-control  {{ $field->getCssClass() }}" id="{{ $field->getName() }}"  name="{{ $field->getName() }}" 
    type="date" placeholder="{{ $field->getHtmlAttributes()->get('placeholder')}}"  value="{{ old($field->getName(),$field->getValue()) }}" />
</div>