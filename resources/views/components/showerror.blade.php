@props(['name'])

@if(isset($errors) && $errors->has($name))
     <div class="col-10 mx-auto alert alert-danger alert-dismissible fade show my-3" role="alert">
       {{ $errors->first($name) }}
     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
   </div>
@endif