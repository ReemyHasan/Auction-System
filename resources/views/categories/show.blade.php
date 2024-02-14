@extends('layout.app')
@section('content')
    <section class="content-header pb-0">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h3>{{ $category->name }}</h3>
                </div>
            </div>
        </div>

    </section>

    <section class="content pt-0">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">name:</label>
                            <h5>{{ $category->name }}</h5>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="description">description:</label>
                            <h5>{{ $category->description }}</h5>

                        </div>
                    </div>
                </div>
            </div>

        </div>
        </div>
    </section>
@endsection
