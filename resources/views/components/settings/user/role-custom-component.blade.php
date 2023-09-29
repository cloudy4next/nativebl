<div class="form-group">
    <label for="menus" class="mb-2">Menu:</label>
    <div class="menu-container">
        @foreach ($menus as $menu)
            @if ($menu['parentID'] == 0)
                <ul class="tree">
                    <label class="menu-item">
                        <input type="checkbox" class="parent-checkbox" name="menus[]" value="{{ $menu['id'] }}"
                            data-parent-id="{{ $menu['id'] }}">
                        {{ $menu['title'] }}
                    </label>
                    <ul class="child-items">
                        @foreach ($menus as $value)
                            @if ($menu['id'] == $value['parentID'])
                                <label class="menu-item">
                                    <input type="checkbox" class="child-checkbox" name="menus[]"
                                        value="{{ $value['id'] }}" data-parent-id="{{ $menu['id'] }}">
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


<hr class="my-3">

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
