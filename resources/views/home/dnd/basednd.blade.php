<x-main-layout>
    <x-slot:title>
        NativeBL:: DND
    </x-slot>
    <div class="content">


        <div class="tab ">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" href="#primary-tab-1" data-bs-toggle="tab" role="tab"
                        aria-selected="true">Api Status</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="#primary-tab-2" data-bs-toggle="tab" role="tab" aria-selected="false"
                        tabindex="-1">DND on/off</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="primary-tab-1" role="tabpanel">
                    <x-native::crud-grid />
                </div>
                <div class="tab-pane" id="primary-tab-2" role="tabpanel">
                    <div class="content">
                        <div class="card mb-3">
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
                                <form name="user_form" action={{ route('dnd.onoff') }} method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-6" style="margin-top: 16px;">
                                            <label class="form-label" for="msisdn">MSISDN</label>
                                            <input class="form-control  " id="msisdn" name="msisdn" type="number"
                                                placeholder="MSISDN" />
                                        </div>
                                        <div class="col-lg-6" style="margin-top: 16px;">
                                            <label class="form-label" for="channel">Channel</label>
                                            <select class="form-select" id="channel" name="channel">
                                                @foreach ($channels as $ch)
                                                    <option value="{{ $ch }}">{{ $ch }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-6" style="margin-top: 16px;">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                                    id="inlineRadio1" value="on">
                                                <label class="form-check-label" for="inlineRadio1">On</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                                    id="inlineRadio2" value="off">
                                                <label class="form-check-label" for="inlineRadio2">Off</label>
                                            </div>
                                        </div>

                                    </div>
                                    <hr>

                                    <button class="btn btn-primary " type="submit">Submit</button>
                                    <a href="{{ route('dnd') }}" class="btn btn-secondary"> Cancel </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main-layout>
