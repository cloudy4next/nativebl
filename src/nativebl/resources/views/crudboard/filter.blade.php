<div class="row mb-2">
    <div class="col-lg-12">
        <h1 class="h3 d-inline align-middle">Search</h1>
    </div>
    <div class="col-12 col-lg-12">
        <div class="card">
            <div class="card-body">
                <form method="get" name='filter'>
                    <div class="row">
                        @foreach($filter->getFields() as $field)
                        <x-dynamic-component :component="$field->getComponent()" :$field />
                        @endforeach
                        <div class="col-lg-12 text-end">  <button type="submit" class="btn btn-success">Search</button> </div>
                    </div>
                </form>   
            </div>
        </div>
    </div>
</div>