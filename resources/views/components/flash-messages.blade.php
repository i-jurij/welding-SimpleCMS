@if ($message = Session::get('success'))
<div class="back shad my-4 p-4 alert alert-success alert-block text-green-800">
    <strong>{{ $message }}</strong>
</div>
@endif


@if ($message = Session::get('error'))
<div class="back shad my-4 p-4 alert alert-danger alert-block">
        <strong>{{ $message }}</strong>
</div>
@endif


@if ($message = Session::get('warning'))
<div class="back shad my-4 p-4 alert alert-warning alert-block">
	<strong>{{ $message }}</strong>
</div>
@endif


@if ($message = Session::get('info'))
<div class="back shad my-4 p-4 alert alert-info alert-block">
	<strong>{{ $message }}</strong>
</div>
@endif


@if ($errors->any())
<div class="back shad my-4 alert alert-danger">
   <ul>
       @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
      @endforeach
   </ul>
</div>

@endif
