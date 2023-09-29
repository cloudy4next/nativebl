<x-main-layout>
    <div class="content">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit role</h5>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="row">
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                <form name="role_form" action={{ route('role_update') }} method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $role->id }}" />
                    <div class="mb-3">
                        <label class="form-label" for="applicationID">Application ID</label>
                        <select class="form-select" name="applicationID">
                            @foreach ($userApplicationIDs as $key => $value)
                                <option value="{{ $key }}"
                                    {{ $key == $role->applicationID ? 'selected' : '' }}>{{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div class="mb-3">
                        <label class="form-label" for="title">Title</label>
                        <input class="form-control  " id="title" name="title" type="text" placeholder="Title"
                            value="{{ $role->title }}" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="shortDescription">Short Description</label>
                        <input class="form-control  " id="shortDescription" name="shortDescription" type="text"
                            value="{{ $role->shortDescription }}" />
                    </div>

                    <div class="form-group">
                        <label for="menus" class="mb-2">Menu:</label>
                        <div class="menu-container">
                            @foreach ($menus as $menu)
                                @if ($menu['parentID'] == 0)
                                    <ul class="tree">
                                        <label class="menu-item">
                                            <input type="checkbox" class="parent-checkbox" name="menus[]"
                                                value="{{ $menu['id'] }}" data-parent-id="{{ $menu['id'] }}"
                                                @if (in_array($menu['id'], $role->menus)) checked @endif>
                                            {{ $menu['title'] }}
                                        </label>
                                        <ul class="child-items">
                                            @foreach ($menus as $value)
                                                @if ($menu['id'] == $value['parentID'])
                                                    <label class="menu-item">
                                                        <input type="checkbox" class="child-checkbox" name="menus[]"
                                                            value="{{ $value['id'] }}"
                                                            data-parent-id="{{ $menu['id'] }}"
                                                            @if (in_array($menu['id'], $role->menus)) checked @endif>
                                                        {{ $value['title'] }}
                                                    </label>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </ul>
                                @endif
                            @endforeach
                        </div>
                    </div>


                    <hr class="mb-2">

                    <button class="btn btn-primary " type="submit">Submit</button>
                    <a href="{{ route('role_list') }}" class="btn btn-secondary"> Cancel </a>
                </form>
            </div>
        </div>
    </div>
</x-main-layout>


{{-- @if (in_array($menu['id'], $role->menus)) checked @endif --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
    integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function() {
        $('.parent-checkbox').click(function() {
            const parentId = $(this).data('parent-id');
            const childCheckboxes = $(`.child-checkbox[data-parent-id="${parentId}"]`);
            childCheckboxes.prop('checked', this.checked);
        });

        $('.child-checkbox').click(function() {
            const parentId = $(this).data('parent-id');
            const parentCheckbox = $(`.parent-checkbox[data-parent-id="${parentId}"]`);
            const allChildCheckboxes = $(`.child-checkbox[data-parent-id="${parentId}"]`);

            if ($(this).prop('checked')) {
                parentCheckbox.prop('checked', true);
            } else {
                parentCheckbox.prop('checked', allChildCheckboxes.is(':checked'));
            }
        });
    });
</script>
