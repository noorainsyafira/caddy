@if(session('success'))
<div class="alert alert-success text-center">
<h4>{{ session('success') }}</h4>
</div>
@elseif(session('error'))
<div class="alert alert-danger text-center">
<h4>{{ session('error') }}</h4>
</div>
@endif