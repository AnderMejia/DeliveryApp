@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Cliente: {{auth()->user()->name}}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="app">
                        <compra-component :user="{{Auth::user() != null ? Auth::user() :json_encode($user =array())}}"></compra-component>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
