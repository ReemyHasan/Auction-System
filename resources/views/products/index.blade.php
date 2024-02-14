@extends('layout.app')
@section('content')
    <main id="main">
        <section style="padding-bottom: 0">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-md-6">
                        <h4>products list - Total: {{ !empty($products) ? count($products) : '' }} </h4>
                    </div>
                    @if (Auth::user()->can('create', App\Product::class))
                        <div class="col-md-6" style="text-align: right">
                            <a class="btn btn-primary" href="{{ route('products.create') }}">Add new product</a>
                        </div>
                        <div class="col-sm-6" style="text-align: right">
                            @include('shared.message')
                        </div>
                    @endif
                </div>
                <hr>
                <div class="row mb-2">
                    <form action="{{ route('products.index') }}" method="GET">
                        @csrf
                        <div class="row">
                            <div class="form-group col-sm-3">
                                <input type="text" class="form-control" placeholder="product_name" name="product_name">
                            </div>
                            <div class="form-group col-sm-3">
                                <input type="date" class="form-control" name="date">
                            </div>
                            <div class="col-sm-3">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </section>
        <section class="content pt-2">
            <div class="container-fluid">

                <div class="row">
                    @if (count($products) > 0)
                        @foreach ($products as $product)
                            <div class="col-md-3">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <a href="{{ route('products.show', $product->id) }}">
                                            <h3 class="card-title">{{ $product->name }}</h3>
                                        </a>
                                        <div class="card-tools">
                                            @can('update', $product)
                                                <button type="button" class="btn btn-tool">
                                                    <a href="{{ route('products.edit', $product->id) }}" class="">
                                                        <span title="edit" class="badge bg-success">edit</span>
                                                    </a>
                                                </button>
                                            @endcan
                                            @can('delete', $product)
                                                <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-tool">
                                                        <span title="edit" class="badge bg-danger">delete</span>

                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                    <div class="card-body row">
                                        <div class="col">
                                            <div><strong>description:</strong> {{ $product->description }}</div>
                                            <hr>

                                            <div><strong>available by:</strong> {{ $product->count }}</div>
                                            <hr>

                                            <div><strong>category:</strong> {{ $product->category->name }}</div>
                                            <hr>
                                            <div><strong>created by:</strong> {{ $product->user->name }}</div>
                                            <hr>

                                            <div><strong>added at:</strong> {{ $product->created_at }}</div>
                                        </div>
                                        <div class="col">
                                            <img class="img-fluid" src="{{ $product->get_imageUrl() }}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </section>
    </main>
@endsection
