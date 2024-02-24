@extends('layout.app')
@section('content')
    <main id="main">
        <section style="padding-bottom: 0">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-md-6">
                        <h4>auctions list - Total: {{ !empty($auctions) ? count($auctions) : '0' }} </h4>
                    </div>
                </div>
                <hr>
                <div class="col-sm-12">
                    @include('shared.message')
                </div>
                <div class="row mb-2">
                </div>

        </section>
        <section class="content pt-2">
            <div class="container-fluid">

                <div class="row">
                    @if (!empty($auctions))
                        @foreach ($auctions as $auction)
                            <div class="col-md-3">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <a href="{{ route('auctions.show', $auction->id) }}">
                                            <h3 class="card-title">{{ $auction->product->name }}</h3>
                                        </a>
                                        <div class="card-tools">
                                            @can('create', App\Models\CustomerBid::class)
                                                <button type="button" class="btn btn-md">
                                                    <a href="{{ route('customer.auction.bids', [Auth::user(), $auction]) }}"
                                                        class="">
                                                        <span title="my bids" class="badge bg-secondary">my bids</span>
                                                    </a>
                                                </button>
                                            @endcan
                                        </div>
                                    </div>
                                    <div class="card-body row">
                                        <div class="col">
                                            <div><strong>start at: </strong> {{ $auction->start_time }}</div>
                                            <hr>
                                            <div><strong>end at: </strong> {{ $auction->closing_time }}</div>
                                            <hr>
                                            @can('create', App\Models\CustomerBid::class)
                                                @if ($auction->status === 1)
                                                    <div class="alert alert-info">
                                                        <a href="{{ route('bids.show', $auction->id) }}" class="">
                                                            enter
                                                            <i class="fas fa-arrow-circle-right"></i></a>
                                                    </div>
                                                @elseif ($auction->status === 0)
                                                    <div class="alert alert-warning">
                                                        <strong>not start yet</strong>
                                                    </div>
                                                @else
                                                    <div class="alert alert-danger">
                                                        <strong>closed</strong>
                                                    </div>
                                                @endif

                                                <hr>
                                            @endcan
                                        </div>
                                        <div class="col">
                                            <img class="img-fluid" src="{{ $auction->product->get_imageUrl() }}"
                                                alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @else
                        <span class="alert alert-info col-sm-3">not participated yet in any auction</span>
                    @endif
                </div>
            </div>
        </section>
    </main>
@endsection
