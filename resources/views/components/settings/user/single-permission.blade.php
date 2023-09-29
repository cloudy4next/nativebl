<x-main-layout>
    <div class="content">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit permission</h5>
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
                <form name="permission_form" action={{ route('permission_update') }} method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $permission->id }}" />

                    <div class="mb-3">
                        <label class="form-label" for="applicationID">Parent ID</label>
                        <select class="form-select" name="applicationID">
                            @foreach ($userApplicationIDs as $key => $value)
                                <option value="{{ $key }}"
                                    {{ $key == $permission->applicationID ? 'selected' : '' }}>{{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="title">Title</label>
                        <input class="form-control  " id="title" name="title" type="text" placeholder="Title"
                            value="{{ $permission->title }}" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="shortDescription">Short Description</label>
                        <input class="form-control  " id="shortDescription" name="shortDescription" type="text"
                            value="{{ $permission->shortDescription }}" />
                    </div>


                    <button class="btn btn-primary " type="submit">Submit</button>
                    <a href="{{ route('permission_list') }}" class="btn btn-secondary"> Cancel </a>
                </form>
            </div>
        </div>
    </div>
</x-main-layout>
