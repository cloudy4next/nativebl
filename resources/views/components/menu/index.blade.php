<nav class="sidebar js-sidebar" id="sidebar">
    <div class="sidebar-content js-simplebar">
        @if (session()->has('applicationID') == 4)
            <a class="sidebar-brand" href="/all-campaign">
                <span class="align-middle">{{ env('APP_NAME') }}</span>
            </a>
        @else
            <a class="sidebar-brand" href="/">
                <span class="align-middle">Native BL</span>
            </a>
        @endif

        @if (session()->has('menus'))
            <ul class="sidebar-nav">
                @foreach (session()->get('menus') as $key => $subMenu)
                    @if (count($subMenu) == 0)
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ '/' . $key }}">
                                <i data-feather="{{ ucfirst(str_replace('-', ' ', $key)) }}"></i>
                                <span class="align-middle">{{ ucwords(str_replace('-', ' ', $key)) }}</span>
                            </a>
                        </li>
                    @else
                        <li class="sidebar-item">
                            <a class="sidebar-link collapsed" data-bs-target="#{{ $key }}" data-bs-toggle="collapse">
                                <i data-feather="{{ ucfirst(str_replace('-', ' ', $key)) }}"></i>
                                <span class="align-middle">{{ ucwords(str_replace('-', ' ', $key)) }}</span>
                            </a>
                        </li>
                        @if (is_array($subMenu) && count($subMenu) > 0)
                            <ul class="sidebar-dropdown list-unstyled collapse" id="{{ $key }}"
                                data-bs-parent="#sidebar">
                                @foreach ($subMenu as $subItem)
                                    <li class="sidebar-item">
                                        <a class="sidebar-link"
                                            href="{{ '/' . $subItem['url'] }}">{{ $subItem['title'] }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    @endif
                @endforeach
            </ul>
        @endif
    </div>
</nav>
