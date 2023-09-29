<div class="mb-3">
    <label class="form-label" for="{{ $field->getName() }}"> {{ $field->getLabel() }}</label>
    <textarea class="form-control" name="{{ $field->getName() }}" id="{{ $field->getName() }}" rows="{{ $field->getCustomOption('rows')}}" placeholder="{{ $field->getName() }}">
    {{ old($field->getName(),$field->getValue()) }}
    </textarea>
</div>