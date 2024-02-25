@extends('layout.app')
@section('content')
    <section class="pb-0">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>{{ $product->name }}</h4>
                </div>
            </div>
        </div>

    </section>

    <section class="content pt-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="">
                        <div class="card-body">
                            <div class="row">
                                <div class="">
                                    <label for="name">name</label>
                                    <h5>{{ $product->name }}</h5>
                                </div>
                                <hr>
                                <div class="">
                                    <label for="image" class="mt3">Product Image</label>
                                    @if (!empty($product->image))
                                        <div class="col-sm-3">
                                            <img class="img-fluid" src="{{ $product->get_imageUrl() }}" alt="">
                                        </div>
                                    @else
                                        no image found
                                    @endif
                                </div>
                                <hr>
                                <div class="col-sm-3">
                                    <label for="status">Status</label>
                                    <h5>{{ $product->status == 1 ? 'active' : 'inactive' }}</h5>
                                </div>
                                <div class="col-sm-3">
                                    <label for="count">Available count</label>
                                    <h5>{{ $product->count }}</h5>
                                </div>
                                <div class="col-sm-3">
                                    <label for="category_id">Category</label>
                                    <div>
                                        @foreach ($product->categories as $category)
                                            <div>{{$category->name}} </div>
                                        @endforeach
                                        </div>
                                </div>
                                <hr>
                                <div class="">
                                    <label for="description">description</label>
                                    <h5>{{ $product->description }}</h5>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </div>
        </div>
    </section>
@endsection
