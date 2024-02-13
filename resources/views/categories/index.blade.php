@extends('layout.app')
@section('content')
    <main id="main">
        <div class="content-wrapper">
            <section class="content-header" style="padding-bottom: 0">
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
                                    <input type="text" class="form-control" placeholder="category_name"
                                        name="category_name">
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

                    @if (count($categories) > 0)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <!-- /.card-header -->
                                    <div class="card-body p-0">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>name</th>
                                                    <th>description</th>
                                                    <th>created by</th>
                                                    <th>created at</th>
                                                    <th>edit</th>
                                                    <th>delete</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($categories as $category)
                                                    <tr>
                                                        <td>{{ $category->name }}</td>
                                                        <td>{{ $category->description }}</td>
                                                        <td>{{ $category->user->name }}</td>
                                                        <td>{{ $category->created_at }}</td>
                                                        <td>
                                                            <a href="{{ route('categories.edit', $category->id) }}"
                                                                class="btn btn-secondary btn-sm">
                                                                edit</a>
                                                        </td>
                                                        <td>
                                                            <form action="{{ route('categories.destroy', $category->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('delete')
                                                                <button type="submit"
                                                                    class="btn btn-danger btn-sm">delete</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>

                                        </table>

                                    </div>
                                </div>

                            </div>


                        </div>
                        {{ $categories->withQueryString()->links() }}
                    @else
                        no result found
                    @endif
                </div>
            </section>

        </div>
    </main>
@endsection
