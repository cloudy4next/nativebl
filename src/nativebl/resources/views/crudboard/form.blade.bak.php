<!-- Content-->
<div class="content">
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">{{$attributes['title']}}</h1>
    </div>
    <div class="row">
        <form name="{{$form->getName()}}" action="{{$form->getAction()}}" method="{{$form->getMethod()}}" class="{{$form->getCssClass()}}" {!! $form->getAttributesAsHtml() !!}>
        <div class="col-12 col-lg-6">
            @foreach($form->getFields() as $field)
                <x-dynamic-component :component="$field->getComponentName()" :$field />    
           @endforeach 
      
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Checkboxes</h5>
                </div>
                <div class="card-body">
                    <div>
                        <label class="form-check">
                            <input class="form-check-input" type="checkbox" value="">
                            <span class="form-check-label">Option one is this and that&mdash;be sure to include why it&apos;s great</span>
                        </label>
                        <label class="form-check">
                            <input class="form-check-input" type="checkbox" value="" disabled="">
                            <span class="form-check-label">Option two is disabled</span>
                        </label>
                    </div>
                    <div>
                        <label class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" value="option1">
                            <span class="form-check-label">1</span>
                        </label>
                        <label class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" value="option2">
                            <span class="form-check-label">2</span>
                        </label>
                        <label class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" value="option3" disabled="">
                            <span class="form-check-label">3</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Read only</h5>
                </div>
                <div class="card-body">
                    <!-- <input class="form-control" type="text" placeholder="Readonly input" readonly=""> -->
                  

                   
                </div>
            </div>
        </form>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Radios</h5>
                </div>
                <div class="card-body">
                    <div>
                        <label class="form-check">
                            <input class="form-check-input" type="radio" value="option1" name="radios-example" checked="">
                            <span class="form-check-label">Option one is this and that&mdash;be sure to include why it&apos;s great</span>
                        </label>
                        <label class="form-check">
                            <input class="form-check-input" type="radio" value="option2" name="radios-example">
                            <span class="form-check-label">Option two can be something else and selecting it will deselect option one</span>
                        </label>
                        <label class="form-check">
                            <input class="form-check-input" type="radio" value="option3" name="radios-example" disabled="">
                            <span class="form-check-label">Option three is disabled</span>
                        </label>
                    </div>
                    <div>
                        <label class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inline-radios-example" value="option1">
                            <span class="form-check-label">1</span>
                        </label>
                        <label class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inline-radios-example" value="option2">
                            <span class="form-check-label">2</span>
                        </label>
                        <label class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inline-radios-example" value="option3" disabled="">
                            <span class="form-check-label">3</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Selects</h5>
                </div>
                <div class="card-body">
                    <select class="form-select mb-3">
                        <option selected="">Open this select menu</option>
                        <option>One</option>
                        <option>Two</option>
                        <option>Three</option>
                    </select>
                    <select class="form-control" multiple="">
                        <option>One</option>
                        <option>Two</option>
                        <option>Three</option>
                        <option>Four</option>
                    </select>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Disabled</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Disabled input</label>
                        <input class="form-control" type="text" placeholder="Disabled input" disabled="">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Disabled select menu</label>
                        <select class="form-control" disabled="">
                            <option>Disabled select</option>
                        </select>
                    </div>
                    <label class="form-check">
                        <input class="form-check-input" type="checkbox" value="" disabled="">
                        <span class="form-check-label">Can&apos;t check this</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>