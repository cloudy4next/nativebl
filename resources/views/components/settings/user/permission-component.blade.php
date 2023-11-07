<div class="form-group">
    <label for="permissions" class="mb-3">Permissions:</label>
    <div class="row">
        @foreach ($permissions as $permission)
        <div class="col-md-4"> <!-- Adjust the column size as needed -->
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="permissions[]" value="{{ $permission['id'] }}" id="permission_{{ $permission['id'] }}">
                <label class="form-check-label" for="permission_{{ $permission['id'] }}">{{ $permission['title'] }}</label>
            </div>
        </div>
        @endforeach
    </div>
</div>
<hr class="mb-3" />
