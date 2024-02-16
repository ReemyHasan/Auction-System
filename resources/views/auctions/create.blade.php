@extends('layout.app')
@section('content')
    <section class="pb-0">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>ADD NEW AUCTIONS</h4>
                    <h5> <strong>Time Now: </strong> {{Carbon\Carbon::now()}}</h5>
                </div>
            </div>
            <div class="col-sm-6" style="text-align: right">
                @include('shared.message')
            </div>
        </div>

    </section>

    <section class="content pt-0">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="">

                        <form action="{{ route('auctions.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-sm-3">
                                        <label for="product_id">My Products</label>
                                        <select class="form-control" name="product_id">
                                            <option value="">select option</option>
                                            @foreach ($products as $product)
                                                <option value={{ $product->id }}>{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('product_id')
                                        <div class="alert alert-danger">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label for="lowest_price">lowest price</label>
                                        <input type="number" min="0" step="0.1" class="form-control" id="lowest_price" name="lowest_price">
                                        @error('lowest_price')
                                            <div class="alert alert-danger">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label for="closing_price">closing price</label>
                                        <input type="number" min="0" step="0.1" class="form-control" id="closing_price" name="closing_price">
                                        @error('closing_price')
                                            <div class="alert alert-danger">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label for="start_time">start time</label>
                                        <input type="datetime-local" id="start_time" class="form-control" name="start_time">
                                        @error('start_time')
                                            <div class="alert alert-danger">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label for="closing_time">closing time</label>
                                        <input type="datetime-local" id="closing_time" class="form-control" name="closing_time">
                                        @error('closing_time')
                                            <div class="alert alert-danger">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                    </div>

                    <div class="card-footer mt-3">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    </form>
                </div>


            </div>

        </div>
        </div>
    </section>
@endsection
