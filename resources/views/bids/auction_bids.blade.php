@extends('layout.app')
@section('content')
    <main id="main">
        <section style="padding-bottom: 0">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-md-6">
                        <h4>{{ $auction->product->name }} auction bids list - Total: {{ !empty($bids) ? count($bids) : '' }}
                        </h4>
                    </div>
                </div>
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
                        <label for="category_id">Category</label>
                        <h5>{{ $auction->product->category->name }}</h5>
                    </div>
                    <div class="col-sm-3">
                        <label for="start_time">available from</label>
                        <h5>{{ $auction->start_time }}</h5> <span>to </span>
                        <h5>{{ $auction->closing_time }}</h5>
                    </div>
                    <div class="col-sm-3">
                        <label for="">start price</label>
                        <h5>{{ $auction->lowest_price }}</h5>
                    </div>
                </div>
                <hr>
                <div class="row mb-2">
                    <div class="col">
                        <form action="{{ route('bids.index') }}" method="GET">
                            @csrf
                            <div class="row">
                                <div class="form-group col">
                                    <input type="datetime-local" class="form-control" name="created_at">
                                </div>
                                <div class="col-sm-1">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-1">
                        <form action="{{ route('bids.index') }}" method="GET">
                            @csrf
                            <div class="col">
                                <button type="submit" class="btn btn-success">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
                <hr>
                <div class="row mb-2">
                    <div class="col">
                        <form action="{{ route('bids.store', $auction) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="form-group col-sm-3">
                                    <input type="hidden" name="auction_id" value="{{$auction->id}}">
                                    <input type="number" min="0" step="0.1" class="form-control" id="price"
                                        name="price" placeholder="enter your bidding">
                                    @error('price')
                                        <div class="alert alert-danger">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-1">
                                    <button type="submit" class="btn btn-primary">submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <hr>
            </div>

        </section>
        <section class="content pt-0 col-sm-6">
            <div class="container-fluid">
                @if (count($bids) > 0)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <!-- /.card-header -->
                                <div class="card-body p-0">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>auction for product</th>
                                                <th>customer</th>
                                                <th>price</th>
                                                <th>bids at</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($bids as $bid)
                                                <tr>
                                                    <td>{{ $bid->auction->product->name }}</td>
                                                    <td>{{ $bid->customer->name }}</td>
                                                    <td>{{ $bid->price }}</td>
                                                    <td>{{ $bid->created_at }}</td>
                                                </tr>
                                            @endforeach

                                        </tbody>

                                    </table>

                                </div>
                            </div>

                        </div>


                    </div>
                    {{ $bids->withQueryString()->links() }}
                @endif
            </div>
        </section>
    </main>
@endsection
