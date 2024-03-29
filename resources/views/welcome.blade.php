@extends('layout.app')
@section('content')
    <main id="main">
        <section id="hero" class="d-flex align-items-center">
            <div class="container position-relative" data-aos-delay="100">
                <div class="row justify-content-center">
                    <div class="col-xl-7 col-lg-9 text-center">
                        <div class="col-sm-6" style="text-align: right">
                            @include('shared.message')
                        </div>
                        <h1>Welcome</h1>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
