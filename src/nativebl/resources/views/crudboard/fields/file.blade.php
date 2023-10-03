
<label class="form-label" for="{{ $field->getName() }}">{{ $field->getLabel() }}  @if($field->isRequired()) <span class="required">*</span> @endif</label>
<input  id="{{ $field->getName() }}"  name="{{ $field->getName() }}" class="form-control" type="file" 
@if($field->isDisabled()) disabled @endif  @if($field->isReadonly()) readonly @endif
>
