<x-main-layout>
   <div class="row pt-2">
    <div class="col-3"> </div>
   <div class="col-3">
   <!-- <button type="button" class="btn btn-primary" onclick="deleteConfirm('delele-product-form-39')">Alert</button> -->
   <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="Tooltip on top">
  Tooltip on top
</button>
  </div>
   </div> 
    <x-slot:title>
      NativeBL:: Customer List
    </x-slot>
    <x-native::crud-grid title="Customer List" />

</x-main-layout>

