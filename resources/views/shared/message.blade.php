@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{session('success')}}
</div>
@endif

@if (session('error'))
<div class="alert alert-danger">
    <strong>{{session('error')}}</strong>
</div>
@endif
