<x-main-layout>
    <div class="content">
        <div class="mb-3">
            <h1 class="h3 d-inline align-middle">{{ $data->fullName }} </h1>
        </div>
        <div class="row">
            <div class="col-md-12 col-xl-12s">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">User Profile:</h5>
                        <div class="card-body">
                            <div class="row g-0 mt-1">
                                <div class="col-6">User Name : {{ $data->userName }}</div>
                                <div class="col-6">Mobile : {{ $data->mobileNumber }}</div>
                                <div class="col-6">Email : {{ $data->emailAddress }}</div>
                            </div>
                            <br>
                            <div class="row">
                                <hr class="mb-2">
                                <div class="form-group">
                                    <h5 class="card-title mb-1">Role:</h5>
                                    <div
                                        style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px;">
                                        @foreach ($data->roles as $role)
                                            <div style="display: flex; align-items: center;">
                                                <label
                                                    style="max-width: 120px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">{{ $role->title }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <hr class="mb-3">

                                <div class="form-group">
                                    <h5 class="card-title mb-1">Permission:</h5>
                                    <div
                                        style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px;">
                                        @foreach ($data->permissions as $permission)
                                            <div style="display: flex; align-items: center;">
                                                <label
                                                    style="max-width: 120px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">{{ $permission->title }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <hr class="mb-3">
                            <div class="form-group">
                                <h5 class="card-title mb-1">Menu:</h5>
                                <div
                                    style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px;">
                                    @foreach ($data->menus as $menu)
                                        <div style="display: flex; align-items: center;">
                                            <label
                                                style="max-width: 120px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">{{ $menu->title }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-main-layout>
