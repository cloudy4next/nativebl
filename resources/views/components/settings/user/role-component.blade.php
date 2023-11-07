<hr class="mb-3" />

<div class="form-group">
    <label for="roles" class="mb-3">Roles:</label>
    <div class="row">
        @foreach ($roles as $role)
            <div class="col-md-4"> <!-- Adjust the column size as needed -->
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="roles[]" value="{{ $role['id'] }}"
                        id="role_{{ $role['id'] }}">
                    <label class="form-check-label" for="role_{{ $role['id'] }}">{{ $role['title'] }}</label>
                </div>
            </div>
        @endforeach
    </div>
</div>
<hr class="mb-3" />
