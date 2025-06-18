
@props(['name'])
@error($name)
     <p class="text-danger col-12 errormessage my-3">{{$message}}</p>
@enderror
