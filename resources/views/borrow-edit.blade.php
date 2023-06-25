@extends('layouts.main')
@section('title', $borrow->book->name)
@section('content')
    <!-- push external head elements to head -->
    @push('head')
        <link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2.min.css') }}">
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-inbox bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Edit Borrow') }}</h5>
                            <span>{{ __('Edit data borrow') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ url('/') }}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#">{{ __('Book') }}</a>
                            </li>
                            <li class="breadcrumb-item">
                                <!-- clean unescaped data is to avoid potential XSS risk -->
                                {{ clean($borrow->book->name, 'titles') }}
                            </li>

                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- start message area-->
            @include('include.message')
            <!-- end message area-->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form class="forms-sample" method="POST" action="{{ url('borrow/update') }}">
                            @csrf
                            <input type="hidden" name="id" value="{{ $borrow->id }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="user">{{ __('User') }}<span class="text-red">*</span></label>
                                        {!! Form::select('user', $users, $borrow_user->id ?? '', [
                                            'class' => 'form-control select2',
                                            'placeholder' => 'Select User',
                                            'id' => 'user',
                                            'required' => 'required',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="book">{{ __('Book') }}<span class="text-red">*</span></label>
                                        {!! Form::select('book', $books, $borrow_book->id ?? '', [
                                            'class' => 'form-control select2',
                                            'placeholder' => 'Select Book',
                                            'id' => 'book',
                                            'required' => 'required',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit"
                                            class="btn btn-primary form-control-right">{{ __('Update') }}</button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- push external js -->
    @push('script')
        <script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
    @endpush
@endsection
