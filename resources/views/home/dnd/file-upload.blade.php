<x-main-layout>
    <div class="content">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Upload File</h5>
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
                <form name="dnd_upload" action="{{ route('dnd.upload.save') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-lg-12" style="margin-top: 16px;">
                            <label class="form-label" for="file">Upload File (CSV, XLS, XLSX)</label>
                            <input id="file" name="file" class="form-control" type="file" value="">
                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-12" style="margin-top: 16px;">
                            <label class="form-label" for="datetime">Select Schedule Date and Time</label>
                            <input id="datetime" name="datetime" class="form-control" type="datetime-local"
                                   value="{{ date('Y-m-d\TH:i', strtotime(now())) }}" required>
                        </div>
                    </div>


                    <button class="btn btn-primary" type="submit">Submit</button>
                    <a href="{{ route('dnd-bulk') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</x-main-layout>
