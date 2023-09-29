<x-main-layout>
    <div class="content">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit menu</h5>
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
                <form name="menu_form" action={{ route('menu_store') }} method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $menu->id }}" />
                    <div class="mb-3">
                        <label class="form-label" for="applicationID">Parent ID</label>
                        <select class="form-select" name="applicationID">
                            @foreach ($userApplicationIDs as $key => $value)
                                <option value="{{ $key }}"
                                    {{ $key == $menu->applicationID ? 'selected' : '' }}>{{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="title">Title</label>
                        <input class="form-control  " id="title" name="title" type="text" placeholder="Title"
                            value="{{ $menu->title }}" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="parentID">Parent ID</label>
                        <select class="form-select" name="parentID">
                            @foreach ($all_menu as $item)
                                <option value="{{ $item['id'] }}"
                                    {{ $item['id'] == $menu->parentID ? 'selected' : '' }}>{{ $item['title'] }}
                                </option>
                            @endforeach
                            <option value="-1">None</option>

                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="iconName">Icon Name</label>
                        <input class="form-control  " id="iconName" name="iconName" type="text"
                            value="{{ $menu->iconName }}" />
                    </div>


                    <div class="mb-3">
                        <label class="form-label" for="displayOrder">Display Orders</label>
                        <input class="form-control  " id="displayOrder" name="displayOrder" type="text"
                            value="{{ $menu->displayOrder }}" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="target">Target</label>
                        <input class="form-control  " id="target" name="target" type="text"
                            value="{{ $menu->target }}" />
                    </div>

                    <button class="btn btn-primary " type="submit">Submit</button>
                    <a href="{{ route('menu_list') }}" class="btn btn-secondary"> Cancel </a>
                </form>
            </div>
        </div>
    </div>
</x-main-layout>
