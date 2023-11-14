<div class="mb-3">
    <label class="form-label" for="user[]"><strong>Map User:</strong></label>
    <div>
        @foreach ($userList as $key => $user)
            <label class="form-check form-check-inline">
                <input class="form-check-input my-class" type="checkbox" name="user[]"
                    value="{{ $key }}">
                <span class="form-check-label">{{ $user }}</span>
            </label>
        @endforeach
    </div>
</div>
