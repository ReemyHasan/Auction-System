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
                    <div class="col">

                        <form action="{{ route('categories.index') }}" method="GET">
                            @csrf
                            <div class="row">
                                <div class="form-group col">
                                    <input type="text" class="form-control" placeholder="category_name" name="name">
                                </div>
                                <div class="form-group col">
                                    <input type="date" class="form-control" name="created_at">
                                </div>
                                <div class="col-sm-1">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-sm-3">
                        <form action="{{ route('categories.index') }}" method="GET">
                            @csrf
                            <div class="col-sm-2">
                                <button type="submit" class="btn btn-success">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            </div>

        </section>
        <section class="content pt-2">
            <div class="container-fluid">

                <div class="row">
                    @if (count($categories) > 0)
                        @foreach ($categories as $category)
                            <div class="col-md-3">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <a href="{{ route('categories.show', $category->id) }}">
                                            <h3 class="card-title">{{ $category->name }}</h3>
                                        </a>
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

                                        <div><strong>created at:</strong>
                                            {{ date('d-m-Y', strtotime($category->created_at)) }}</div>
                                        <hr>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        {{ $categories->withQueryString()->links() }}
                    @else
                        no result found
                    @endif
                </div>
            </div>
        </section>
    </main>
@endsection
