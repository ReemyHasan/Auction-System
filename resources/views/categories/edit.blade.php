@extends('layout.app')
@section('content')
    <section class="pb-0">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>UPDATE CATEGORY {{ $category->name }}</h4>
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

                        <form action="{{ route('categories.update', $category->id) }}" method="POST">
                            @csrf
                            @method('put')
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Enter name" value="{{ $category->name }}">
                                    @error('name')
                                        <div class="alert alert-danger">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="description">description</label>
                                    <textarea rows="3" class="form-control" id="description" name="description" placeholder="Enter description">{{ $category->description }} </textarea>
                                    @error('description')
                                        <div class="alert alert-danger">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
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
