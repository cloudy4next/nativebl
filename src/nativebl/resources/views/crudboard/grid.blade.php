 <div class="content">
     <x-native::grid-filter />
     <div class="row mb-2">
         <div class="col-lg-6">
             <h1 class="h3 d-inline ">{{ $attributes['title'] }}</h1>
         </div>
         <div class="col-lg-6 text-end">
             @foreach ($grid->getActions() as $crudAction)
                @php
                 $htmlAttributes  = $crudAction->getAttributesAsHtml();
                @endphp
                <x-dynamic-component :component="$crudAction->getComponent()" :$crudAction :$htmlAttributes  />
             @endforeach
         </div>
     </div>
     <div class="card">
         <div class="card-body">
             <div class="table-responsive">
                 <table class="table table-sm table-bordered {{ $grid->getTableCssClass() }}">
                     <thead class="{{ $grid->getHeaderRowCssClass() }}">
                         <tr>
                             <th scope="col">#</th>
                             @foreach ($grid->getColumns() as $column)
                                 <th scope="col" class="{{ $column->getCssClass() }}">{{ $column->getLabel() }}</th>
                             @endforeach
                             @if ($grid->getRowActions()->count())
                             <th scope="col" style="width: 15%">{{$grid->getActionLevel() }}</th>
                             @endif
                         </tr>
                     </thead>
                     <tbody>
                         @forelse($grid->getGridData() as $k=>$row)
                           @php 
                                $rowCssClass = $grid->getRowCssClass($row);
                                $rowCss = $grid->getRowCss($row);
                            @endphp
                             <tr 
                             @if($rowCssClass)
                              class = "{{ $rowCssClass }}"
                             @endif
                             >
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
                                                 $htmlAttributes = $rowAction->getAttributesAsHtml();
                                             @endphp
                                             @if($rowAction->shouldBeDisplayedFor($row))
                                             <x-dynamic-component :component="$rowAction->getComponent()" :$rowAction :$routeParams :$htmlAttributes  />
                                             @endif
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
