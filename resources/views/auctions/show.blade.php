@extends('layout.app')
@section('content')
    <section class="pb-0">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>Auction: {{ $auction->product->name }}</h4>
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
                                <hr>
                                <div class="">
                                    <label for="image" class="mt3">Product Image</label>
                                    @if (!empty($auction->product->image))
                                        <div class="col-sm-3">
                                            <img class="img-fluid" src="{{ $auction->product->get_imageUrl() }}" alt="">
                                        </div>
                                    @else
                                        no image found
                                    @endif
                                </div>
                                <hr>
                                <div class="col-sm-3">
                                    <label for="status">Status</label>
                                    <h5>{{ $auction->product->status == 1 ? 'active' : 'inactive' }}</h5>
                                </div>
                                <div class="col-sm-3">
                                    <label for="count">Available count</label>
                                    <h5>{{ $auction->product->count }}</h5>
                                </div>
                                <div class="col-sm-3">
                                    <label for="category_id">Category</label>
                                    <h5>{{ $auction->product->category->name }}</h5>
                                </div>
                                <hr>
                                <hr>
                                <div class="col-sm-3">
                                    <label for="start_time">start time</label>
                                    <h5>{{ $auction->start_time }}</h5>
                                </div>
                                <div class="col-sm-3">
                                    <label for="closing_time">closing time</label>
                                    <h5>{{ $auction->closing_time }}</h5>
                                </div>
                                <hr>
                                <hr>
                                <div class="col-sm-3">
                                    <label for="">start price</label>
                                    <h5>{{ $auction->lowest_price }}</h5>
                                </div>
                                <div class="col-sm-3">
                                    <label for="">closing price</label>
                                    <h5>{{ $auction->closing_price }}</h5>
                                </div>
                                <hr>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </div>
        </div>
    </section>
@endsection
