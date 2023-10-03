<div class="row mb-2">
    <div class="col-lg-12">
        <h1 class="h3 d-inline align-middle">Search</h1>
    </div>
    <div class="col-12 col-lg-12">
        <div class="card">
            <div class="card-body">
                <form method="get" name='filter' class="row g-3">
                    <div class="row">
                        @foreach($filter->getFields() as $field)
                        @php $htmlAttributes = $field->getAttributesAsHtml() ; @endphp
                        <div class=" mt-3 {{ $field->getLayoutClass() }}">
                            <x-dynamic-component :component="$field->getComponent()" :$field :$htmlAttributes />
                        </div>
                        @endforeach
                        <div class="col-lg-12 text-end mt-3">  <button type="submit" class="btn btn-success">Search</button> </div>
                    </div>
                </form>   
            </div>
        </div>
    </div>
</div>