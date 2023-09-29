<div class="mb-3">
    <label> {{ $field->getLabel() }} </label>
    <input class="form-control" type="text" id="daterangepicker" />
</div>

<hr class="mb-3">


<div class="mb-3">
    <select id="category" name="category" class="form-control">
        <option value="fruits">Fruits</option>
        <option value="vegetables">Vegetables</option>
    </select>
</div>

<div class="card mb-3">
    <label for="chain-select">Item:</label>
    <select id="chain-select" name="chain-select" class="form-control"></select>
</div>

<div class="card mb-3">

    <label>Search:</label>

    <input class="form-control" type="text" id="autocomplete" name="autocomplete" autocomplete="off"
        list="autocomplete-datalist" placeholder="eg:['Banana','Cherry','Apple']" />
    <datalist id="autocomplete-datalist"></datalist>
</div>

<div class="card mb-3">
    <div class="form-control" id="editor">
        <p>Hello World!</p>
    </div>
</div>

<hr class="mb-3">
