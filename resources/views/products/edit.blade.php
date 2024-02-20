@extends('layout.app')
@section('content')
    <section class="pb-0">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>UPDATE PRODUCT {{$product->id}}</h4>
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

                        <form enctype="multipart/form-data" action="{{ route('products.update',$product->id) }}" method="POST">
                            @csrf
                            @method('put')
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-sm-3">
                                        <label for="name">name</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Enter name"
                                            value="{{$product->name}}"
                                            >
                                        @error('name')
                                            <div class="alert alert-danger">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label for="status">Status</label>
                                        <select class="form-control" name="status">
                                            <option value="">select option</option>
                                            <option value=1 {{$product->status==1? 'selected':''}}>active</option>
                                            <option value=0 {{$product->status==1? 'selected':''}}>inactive</option>
                                        </select>
                                        @error('status')
                                        <div class="alert alert-danger">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label for="count">Available count</label>
                                        <input type="number" class="form-control" id="count" name="count"
                                        value="{{$product->count}}">
                                        @error('count')
                                            <div class="alert alert-danger">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-sm-3">
                                        <label for="category_id">Category</label>
                                        <div style="border:2px solid #ccc; width:300px; height: 100px; overflow-y: scroll;">
                                            @foreach ($categories as $category)
                                                <div class="form-control">
                                                    <input type="checkbox" value="{{ $category->id }}" name="category_id[]"
                                                    {{ !empty(in_array($category->id, $checked)) ? 'checked' : '' }}>
                                                    <label for="category_id">{{ $category->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                            @error('category_id')
                                                <div class="alert alert-danger">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12 form-group">
                                        <label for="image" class="mt3">Product Image</label>
                                        <input type="file" name="image" class="form-control">
                                        @if (!empty($product->image))
                                        <div class="col-sm-3">
                                            <img  class="img-fluid" src="{{$product->get_imageUrl()}}" alt="">
                                        </div>
                                        @endif
                                        @error('image')
                                        <div class="alert alert-danger">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="description">description</label>
                                        <textarea rows="3" class="form-control" id="description" name="description" placeholder="Enter description">{{$product->description}} </textarea>
                                        @error('description')
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
