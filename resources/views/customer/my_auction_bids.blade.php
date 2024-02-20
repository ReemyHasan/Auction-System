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
                        <label>categories:</label>
                        @foreach ($auction->product->categories as $category)
                            <div>{{$category->name}} </div>
                        @endforeach
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
                <hr>
                <div class="row mb-2">
                    <div class="col-sm-3">
                        <form action="{{ route('bids.destroylatest', [Auth::user(), $auction]) }}" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-secondary">delete latest</button>

                        </form>
                    </div>
                    <div class="col-sm-3">
                        <form action="{{ route('bids.leave_auction', [Auth::user(), $auction]) }}" method="GET">
                            @csrf
                            <button type="submit" class="btn btn-danger">leave the auction</button>

                        </form>
                    </div>
                </div>
                <div class="col-sm-12">
                    @include('shared.message')
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
                                                <th>price</th>
                                                <th>bids at</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($bids as $bid)
                                                <tr>
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
