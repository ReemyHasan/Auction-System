@extends('layout.app')
@section('content')
    <main id="main">
        <section style="padding-bottom: 0">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-md-6">
                        <h4>categories list - Total: {{ !empty($categories) ? count($categories) : '' }} </h4>
                    </div>
                    @if (Auth::user()->can('create', App\Category::class))
                        <div class="col-md-6" style="text-align: right">
                            <a class="btn btn-primary" href="{{ route('categories.create') }}">Add new category</a>
                        </div>
                        <div class="col-sm-6" style="text-align: right">
                            @include('shared.message')
                        </div>
                    @endif
                </div>
                <hr>
                <div class="row mb-2">
                    <form action="{{ route('categories.index') }}" method="GET">
                        @csrf
                        <div class="row">
                            <div class="form-group col-sm-3">
                                <input type="text" class="form-control" placeholder="category_name" name="category_name">
                            </div>
                            <div class="form-group col-sm-3">
                                <input type="date" class="form-control" name="date">
                            </div>
                            <div class="col-sm-3">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </section>
        <section class="content">
            <div class="container-fluid">

                <div class="row">
                    @if (count($categories) > 0)
                        @foreach ($categories as $category)
                            <div class="col-md-3">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">{{ $category->name }}</h3>
                                        <div class="card-tools">
                                            @can('update', $category)
                                                <button type="button" class="btn btn-tool">
                                                    <a href="{{ route('categories.edit', $category->id) }}" class="">
                                                        <span title="edit" class="badge bg-success">edit</span>
                                                    </a>
                                                </button>
                                            @endcan
                                            @can('delete', $category)
                                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-tool">
                                                        <span title="edit" class="badge bg-danger">delete</span>

                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div><strong>description:</strong> {{ $category->description }}</div>
                                        <hr>

                                        <div><strong>created by:</strong> {{ $category->user->name }}</div>
                                        <hr>

                                        <div><strong>created at:</strong> {{ $category->created_at }}</div>
                                        <hr>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </section>
    </main>
@endsection
