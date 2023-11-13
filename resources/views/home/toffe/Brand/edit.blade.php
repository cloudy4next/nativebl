<x-main-layout>

    <div class="content">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit Brand</h5>
            </div>
            <div class="card-body">
                <form name="user_form" action={{ route('brand_save') }} method="post" class="">
                    @csrf
                    <input type="hidden" name="id" value="{{ $brandDetails['id'] }}" />
                    <input type="hidden" name="icon_test" value="{{ $brandDetails['icon'] }}" />

                    <div class="mb-3">
                        <label class="form-label" for="name">Name</label>
                        <input class="form-control  " id="name" name="name" type="text" placeholder="Name"
                            value="{{ $brandDetails['name'] }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="icon">Icon</label>
                        <input class="form-control  " id="icon" name="icon" type="file" placeholder="Icon"
                            value="{{ $brandDetails['icon'] }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="user[]"><strong>Map User</strong></label>
                        <div>
                            @foreach ($userList as $key => $user)
                                <label class="form-check form-check-inline">
                                    <input class="form-check-input my-class" type="checkbox" name="user[]"
                                        value="{{ $key }}"
                                        {{ in_array($key, $mappedUserArray) ? 'checked' : '' }}>
                                    <span class="form-check-label">{{ $user }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>


                    <label class="form-label" for="user[]"><strong>Mapped User</strong></label>

                    <table class="table table-sm table-bordered">
                        <tbody>
                            @if (count($mappedUserArray))
                                @foreach ($mappedUserArray as $key => $user)
                                    @if (isset($userList[$user]))
                                        <tr>
                                            <td class="text-center">{{ $userList[$user] }}</td>
                                            <td>
                                                <a class="btn btn-danger btn-sm"
                                                    href="/brand/delete-brand-user-map/{{ $key }}/{{ $brandDetails['id'] }}">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        </tbody>
                    </table>


                    <input type="hidden" id="created_by" class="" name="created_by"
                        value="{{ Auth::user()->id }}">
                    <button class="btn btn-primary " type="submit">Submit</button>

                    <a href="{{ route('brand_list') }}" class="btn btn-secondary"> Cancel </a>
                </form>
            </div>
        </div>
    </div>


</x-main-layout>
