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
                        <label for="menus">Menu:</label>
                        @foreach ($menus as $menu)
                            @if ($menu['parentID'] == 0)
                                <div class="menu-container">
                                    <ul>
                                        @include('components.settings.user.menu-item', [
                                            'menu' => $menu,
                                            'menus' => $menus,
                                            'role' => $role,
                                        ])
                                    </ul>
                                </div>
                            @endif
                        @endforeach
                    </div>



                    <hr class="mb-2">

                    <button class="btn btn-primary " type="submit">Submit</button>
                    <a href="{{ route('role_list') }}" class="btn btn-secondary"> Cancel </a>
                </form>
            </div>
        </div>
    </div>
</x-main-layout>

