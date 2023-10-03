 <div class="content">
     <x-native::grid-filter />
     <div class="row mb-2">
         <div class="col-lg-6">
             <h1 class="h3 d-inline ">{{ $attributes['title'] }}</h1>
         </div>
         <div class="col-lg-6 text-end">
             @foreach ($grid->getActions() as $crudAction)
                 <a class="btn btn-primary"
                     href="{{ route($crudAction->getRouteName(), $crudAction->getRouteParameters()) }}">
                     @if ($crudAction->getIcon())
                         <i class="fas {{ $crudAction->getIcon() }}"></i>
                     @endif
                     {{ $crudAction->getLabel() }}
                 </a>
             @endforeach
         </div>
     </div>
     <div class="card">
         <div class="card-body">
             <div class="table-responsive">
                 <table class="table table-sm table-bordered">
                     <thead>
                         <tr>
                             <th scope="col">#</th>
                             @foreach ($grid->getColumns() as $column)
                                 <th scope="col" class="{{ $column->getCssClass() }}">{{ $column->getLabel() }}</th>
                             @endforeach
                             @if ($grid->getRowActions()->count())
                             <th scope="col" style="width: 15%">Action</th>
                             @endif
                         </tr>
                     </thead>
                     <tbody>
                         @forelse($grid->getGridData() as $k=>$row)
                             <tr>
                                 <th scope="row">{{ $k + 1 }}</th>
                                 @foreach ($grid->getColumns() as $column)
                                     <td  class="{{ $column->getCssClass() }}" >
                                        @php
                                            $value =  $row[$column->getName()] ;
                                            if($formaterFunc = $column->getFormatValueCallable()){ 
                                                $value = $formaterFunc($value,$row);
                                            }
                                        @endphp
                                        <x-dynamic-component :component="$column->getComponent()" :$value />
                                     </td>
                                 @endforeach
                                 @if($grid->getRowActions()->count())
                                 <td class="text-center">
                                         @foreach ($grid->getRowActions() as $rowAction)
                                             @php
                                                 $routeParams = $rowAction->getRouteParameters();
                                                 if ($routeParams instanceof \Closure) {
                                                     $routeParams = $routeParams($row);
                                                 } else {
                                                     foreach ($routeParams as $key => $val) {
                                                         $routeParams[$key] = $row[$val];
                                                     }
                                                     empty($routeParams) && ($routeParams['id'] = $row['id']);
                                                 }
                                             @endphp
                                             @switch($rowAction->getName())
                                                 @case('detail')
                                                     <a class="btn btn-info btn-sm"
                                                         href="{{ route($rowAction->getRouteName(), $routeParams) }}">
                                                         <i class="fas fa-file-lines"></i>
                                                     </a>
                                                 @break

                                                 @case('edit')
                                                     <a class="btn btn-primary btn-sm"
                                                         href="{{ route($rowAction->getRouteName(), $routeParams) }}">
                                                         <i class="fas fa-pen-to-square"></i>
                                                     </a>
                                                 @break

                                                 @case('delete')
                                                     <a class="btn btn-danger btn-sm"
                                                         href="{{ route($rowAction->getRouteName(), $routeParams) }}">
                                                         <i class="fas fa-times"></i>
                                                     </a>
                                                 @break

                                                 @default
                                                     <a class="btn btn-sm {{ $rowAction->getCssClass() }}"
                                                         href="{{ route($rowAction->getRouteName(), $routeParams) }}">
                                                         <i class="fas {{ $rowAction->getIcon() }}"> </i>
                                                     </a>
                                             @endswitch
                                         @endforeach
                                 </td>
                                 @endif

                             </tr>
                             @empty
                                 <tr>
                                     <td 
                                     @if ($grid->getRowActions()->count())
                                     colspan="{{ $grid->getColumns()->count() + 2 }}" 
                                     @else
                                     colspan="{{ $grid->getColumns()->count() + 1 }}" 
                                     @endif
                                     class="text-center">No record is
                                         found
                                     </td>
                                 </tr>
                             @endforelse

                         </tbody>
                     </table>
                     <x-native::crudboard.pagination :data="$grid->getGridData()" />
                 </div>
             </div>
         </div>
     </div>
