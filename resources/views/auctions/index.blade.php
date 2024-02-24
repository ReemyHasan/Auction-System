@extends('layout.app')
@section('content')
    <main id="main">
        <section style="padding-bottom: 0">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-md-6">
                        <h4>auctions list - Total: {{ !empty($auctions) ? count($auctions) : '' }} </h4>
                    </div>
                    @can('create', App\Auction::class)
                        <div class="col-md-3">
                            <form action="{{ route('auctions.index') }}" method="GET">
                                @csrf
                                <div class="col-sm-6">
                                    <input type="hidden" class="form-control" name="my_auctions">
                                    <button type="submit" class="btn btn-secondary">my auctions</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-3" style="text-align: right">
                            <a class="btn btn-primary" href="{{ route('auctions.create') }}">Add new auction</a>
                        </div>
                    @endcan
                </div>
                <hr>
                <div class="col-sm-12">
                    @include('shared.message')
                </div>
                <div class="row mb-2">
                    <div class="col">

                        <form action="{{ route('auctions.index') }}" method="GET">
                            @csrf
                            <div class="row">
                                <div class="form-group col">
                                    <input type="text" class="form-control" placeholder="product_name" name="name">
                                </div>
                                <div class="form-group col">
                                    <select class="form-control" name="category_id">
                                        <option value="">select category</option>
                                        @foreach ($categories as $category)
                                            <option value={{ $category->id }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col">
                                    <input type="date" class="form-control" name="start_time">
                                </div>
                                <div class="col-sm-1">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-sm-3">
                        <form action="{{ route('auctions.index') }}" method="GET">
                            @csrf
                            <div class="col-sm-2">
                                <button type="submit" class="btn btn-success">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </section>
        <section class="content pt-2">
            <div class="container-fluid">

                <div class="row">
                    @if (count($auctions) > 0)
                        @foreach ($auctions as $auction)
                            <div class="col-md-3">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <a href="{{ route('auctions.show', $auction->id) }}">
                                            <h3 class="card-title">{{ $auction->product->name }}</h3>
                                        </a>
                                        <div class="card-tools">
                                            @can('update', $auction)
                                                <button type="button" class="btn btn-tool">
                                                    <a href="{{ route('auctions.edit', $auction->id) }}" class="">
                                                        <span title="edit" class="badge bg-success">edit</span>
                                                    </a>
                                                </button>
                                            @endcan
                                            @can('delete', $auction)
                                                <form action="{{ route('auctions.destroy', $auction->id) }}" method="POST">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-tool">
                                                        <span title="edit" class="badge bg-danger">delete</span>

                                                    </button>
                                                </form>
                                            @endcan
                                            @can('auctions.addInteractions', $auction)
                                                <a href="{{ route('auctions.add_interaction', $auction->id) }}"
                                                    class="btn btn-tool bg-success">add review</a>
                                            @endcan
                                        </div>
                                    </div>
                                    <div class="card-body row">
                                        <div class="col">
                                            @if ($auction->status !== 2)
                                                <div><strong>lowest price:</strong> {{ $auction->lowest_price }}</div>
                                                <hr>

                                                <div><strong>start at: </strong> {{ $auction->start_time }}</div>
                                                <hr>
                                                <div><strong>end at: </strong> {{ $auction->closing_time }}</div>
                                                <hr>
                                                <div><strong>owner: </strong> {{ $auction->product->user->name }}</div>
                                                <hr>
                                            @endif

                                            <div><strong>categories:</strong>
                                                @foreach ($auction->product->categories as $category)
                                                    <span>{{ $category->name }} </span>
                                                @endforeach
                                            </div>
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
                                                        {{-- {{Carbon\Carbon::now()}} --}}
                                                    </div>
                                                @else
                                                    @if ($auction->auctionWinner())
                                                        <div><strong>WINNER: </strong>
                                                            <span class="text-bg-success p-sm-1"
                                                                style="font-weight:900;">{{ $auction->auctionWinner()->name }}
                                                            </span>
                                                        </div>
                                                    @else
                                                        no winner
                                                    @endif
                                                    <hr>
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
                        {{ $auctions->withQueryString()->links() }}
                    @endif
                </div>
            </div>
        </section>
    </main>
@endsection
