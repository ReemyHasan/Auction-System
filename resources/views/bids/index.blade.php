@extends('layout.app')
@section('content')
    <main id="main">
        <section style="padding-bottom: 0">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-md-6">
                        <h4>bids list - Total: {{ !empty($bids) ? count($bids) : '' }} </h4>
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

                    <div class="col-sm-3">
                        <form action="{{ route('bids.index') }}" method="GET">
                            @csrf
                            <div class="col-sm-2">
                                <button type="submit" class="btn btn-success">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </section>
        <section class="content">
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
                                                <th>bids at</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($bids as $bid)
                                                <tr>
                                                    <td>{{ $bid->auction->product->name }}</td>
                                                    <td>{{$bid->customer->name}}</td>
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
