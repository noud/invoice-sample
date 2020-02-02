@extends('layouts.base')
@section('content')   


    <div class="row mt-5">
        <div class="col-md-3">
        <form method="post" action="{{ route('product.store')}}">
        @csrf
            <div class="form-group">
                <label for="customer">Customer</label>
                <input type="text" class="form-control" id="customer" aria-describedby="emailHelp" name="customer">
               
            </div>
            <div class="form-group">
                <label for="product">Product</label>
                <input type="text" class="form-control" id="product" name="product">
            </div>
            <div class="form-group">
                <label class="form-check-label" for="price">Price</label>
                <input type="number" class="form-control" id="price" name="price">
               
            </div>

            <div class="form-group">
                <label class="form-check-label" for="quantity">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity">
               
            </div>
            <button type="submit" class="btn btn-primary" style="width:100px;">Add</button>
        </form>
        </div>

        @if($products->count() > 0) 
        <div class=" ml-5 col-md-8">
            <table class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $products as $p)
                        <tr>
                            <td>{{$p->customer}}</td>
                            <td>{{$p->product}}</td>
                            <td>{{$p->price}}</td>
                            <td>{{$p->quantity}}</td>
                            <td><a href="{{ route('generate.invoice', $p->id) }}" target="_blank" class="btn btn-sm btn-primary">Generate Invoice</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

@endsection