<div class="mb-3">
    <label class="form-label" for="{{ $field->getName() }}">{{ $field->getLabel() }}</label>
    <div>
    @switch($field->getInputType())
        @case("radio")
        @case("checkbox")
             @foreach ($field->getCustomOption('choiceList') as $key=>$value)
             <label class="form-check form-check-inline">
                <input class="form-check-input {{ $field->getCssClass() }}" type="{{ $field->getInputType() }}" name="{{ $field->getName() }}" value="{{ $key }}"
                @if($field->getCustomOption(NativeBL\Field\ChoiceField::SELECTED.".".$key) !==null )
                    checked
                 @endif   
                >
                <span class="form-check-label">{{ $value }}</span>
			</label>
            @endforeach
        @break
        @default
            <select class="form-control {{ $field->getCssClass() }}" name="{{ $field->getName() }}" id="{{ $field->getName() }}"  {{ $field->getAttributesAsHtml() }}>
            @if ($field->getCustomOption(NativeBL\Field\ChoiceField::EMPTY))
            <option value="">{{ $field->getCustomOption(NativeBL\Field\ChoiceField::EMPTY) }}</option>
            @endif
            @foreach ($field->getCustomOption('choiceList') as $key=>$value)
                 
                <option value="{{ $key }}"
                  @if($field->getCustomOption(NativeBL\Field\ChoiceField::SELECTED.".".$key) !==null )
                    selected
                 @endif                
                >{{ $value}}</option>
            @endforeach
            </select>
    @endswitch
   </div>
  
</div>