@if ($message = Session::get('success'))
<div class="alert alert-success alert-dismissible fade show" style="margin: 10px;">
    <strong>{{ $message }}</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@elseif ($message = Session::get('info'))
<div class="alert alert-info alert-dismissible fade show" style="margin: 10px;">
    <strong>{{ $message }}</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@elseif ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" style="margin: 10px;">
    Check the following errors :
    @foreach ($errors->all() as $error)
    <br><strong>{{ $error }}</strong>
    @endforeach
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@elseif ($message = Session::get('error'))
<div class="alert alert-danger alert-dismissible fade show" style="margin: 10px;">
    <strong>{{ $message }}</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
