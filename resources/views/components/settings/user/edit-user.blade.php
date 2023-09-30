<x-main-layout>
    <div class="content">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit User</h5>
            </div>
            <div class="card-body">
                <form name="user_form" action={{ route('user_update') }} method="post" class="">
                    @csrf
                    <input type="hidden" name="id" value="{{ $user->userID }}" />
                    <div class="mb-3">
                        <label class="form-label" for="applicationID">Default Applicaion ID</label>
                        <input class="form-control  " id="applicationID" name="applicationID" type="text"
                            placeholder="Default Application ID" value="{{ $appliationName }}" readonly />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="userName">User Name</label>
                        <input class="form-control  " id="userName" name="userName" type="text"
                            placeholder="Full Name" value="{{ $user->userName }}" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="fullName">Full Name</label>
                        <input class="form-control  " id="fullName" name="fullName" type="text"
                            value="{{ $user->fullName }}" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="emailAddress">Email</label>
                        <input class="form-control  " id="emailAddress" name="emailAddress" type="email"
                            value="{{ $user->emailAddress }}" readonly />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="mobileNumber">Mobile</label>
                        <input class="form-control  " id="mobileNumber" name="mobileNumber" type="text"
                            value="{{ $user->mobileNumber }}" />
                    </div>
                    @if ($user->applicationID != 4)

                        <div class="mb-3">
                            <label class="form-label" for="grantType">Authentication Type</label>
                            <select class="form-control" name="grantType" id="grantType" readonly>

                                @foreach ($authList as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ $key == $user->grantType ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    @endif
                    <div class="mb-3">
                        <label class="form-label" for="password">Password</label>
                        <input class="form-control  " id="password" name="password" type="password"
                            value="{{ $user->password }}" />
                    </div>

                    <div class="col-md-4">
                        <div class="form-check">
                            <label class="form-check-label" for="isActiveUser"><strong>Active</strong></label>
                            <input type="checkbox" class="form-check-input" name="isActiveUser" id="isActiveUser" value="1" {{ $user->isUserActive ? 'checked' : '' }}>
                        </div>
                    </div>


                    <div class="form-group mt-3">
                        <label for="roles"><strong>Roles:</strong></label>
                        <div class="row">
                            @foreach ($roles as $role)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="roles[]"
                                            value="{{ $role->id }}"
                                            @if (in_array($role->id, $userRole)) checked @endif
                                            id="role_{{ $role->id }}">
                                        <label class="form-check-label"
                                            for="role_{{ $role->id }}">{{ $role->title }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label for="permissions">Permissions:</label>
                        <div class="row">
                            @foreach ($permissions as $permission)
                                <div class="col-md-4"> <!-- Adjust the column size as needed -->
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="{{ $permission['id'] }}" id="permission_{{ $permission['id'] }}"
                                            @if (in_array($permission['id'], $userPermission)) checked @endif>
                                        <label class="form-check-label"
                                            for="permission_{{ $permission['id'] }}">{{ $permission['title'] }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <hr class="mb-2">


                    <button class="btn btn-primary " type="submit">Submit</button>
                    <a href="{{ route('user_list') }}" class="btn btn-secondary"> Cancel </a>
                </form>
            </div>
        </div>
    </div>
</x-main-layout>
