
@extends('products.layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">

    <h3 ><strong>Add Product</strong></h3>

    @include('products.form')

</div>

</div>
</div>
</div>
</div>


@endsection