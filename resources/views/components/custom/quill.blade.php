<div {!! $field->getAttributesAsHtml() !!} class="row pb-5 {{$field->getCssClass()}}">
    @php($editorText = $field->getValue())
    @if(empty($editorText))
        <p>Hello World!</p>
        <p>Some initial <strong>bold</strong> text</p>
        <p><br></p>
    @else
        {!! $editorText !!}
    @endif
</div>
<textarea style="display: none" id="editor-html" name="{{$field->getName()}}"></textarea>
