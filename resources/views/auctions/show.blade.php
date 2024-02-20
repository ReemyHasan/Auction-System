@extends('layout.app')
@section('content')
    <section class="pb-0">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>Auction: <a href="{{route("products.show",$auction->product->id)}}">{{ $auction->product->name }}</a></h4>
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
                                <div class="col-sm-3">
                                    @if (!empty($auction->product->image))
                                        <div class="col-sm-6">
                                            <img class="img-fluid" src="{{ $auction->product->get_imageUrl() }}" alt="">
                                        </div>
                                    @else
                                        no image found
                                    @endif
                                </div>
                                <div class="col-sm-3">

                                    <div><strong>categories:</strong>
                                        @foreach ($auction->product->categories as $category)
                                            <div>{{$category->name}} </div>
                                        @endforeach
                                        </div>
                                </div>
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
                                @can('update', $auction)
                                <div class="col-sm-3">
                                    <label for="">closing price</label>
                                    <h5>{{ $auction->closing_price }}</h5>
                                </div>
                                @endcan
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
