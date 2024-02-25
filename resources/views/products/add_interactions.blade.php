@extends('layout.app')
@section('content')
    <main id="main">
        <section id="hero" class="d-flex align-items-center">
            <div class="container position-relative" data-aos-delay="100">
                <div class="row justify-content-center">
                    <div class="col-xl-7 col-lg-9 text-center">
                        <h3>add rating for product {{$product->name}} product</h3>
                        <form enctype="multipart/form-data" action="{{ route('products.store_interaction',$product) }}" method="POST">
                            @csrf
                            <!-- star rating -->
                            <div class="rating-wrapper">
                                <!-- star 5 -->
                                <input type="radio" id="5-star-rating" name="rate" value="5">
                                <label for="5-star-rating" class="star-rating">
                                    <i class="fas fa-star d-inline-block"></i>
                                </label>

                                <!-- star 4 -->
                                <input type="radio" id="4-star-rating" name="rate" value="4">
                                <label for="4-star-rating" class="star-rating star">
                                    <i class="fas fa-star d-inline-block"></i>
                                </label>

                                <!-- star 3 -->
                                <input type="radio" id="3-star-rating" name="rate" value="3">
                                <label for="3-star-rating" class="star-rating star">
                                    <i class="fas fa-star d-inline-block"></i>
                                </label>

                                <!-- star 2 -->
                                <input type="radio" id="2-star-rating" name="rate" value="2">
                                <label for="2-star-rating" class="star-rating star">
                                    <i class="fas fa-star d-inline-block"></i>
                                </label>

                                <!-- star 1 -->
                                <input type="radio" id="1-star-rating" name="rate" value="1">
                                <label for="1-star-rating" class="star-rating star">
                                    <i class="fas fa-star d-inline-block"></i>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="comment">comment</label>
                                <textarea rows="3" class="form-control" id="comment" name="comment" placeholder="Enter comment"> </textarea>
                                @error('comment')
                                    <div class="alert alert-danger">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                            <div class="card-footer mt-3">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
