@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Ubah Password</div>

                <div class="card-body">
                    <form action="{{ route('change-password') }}" method="post">
                        @csrf
                        @method('patch')
                        <div class="form-group row">
                            <label for="inputPassword" class="col-sm-3 col-form-label">Password Baru</label>
                            <div class="col-sm-6">
                                <input type="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    id="inputPassword"
                                    name="password"
                                    value="{{ old('password') }}"
                                    required autocomplete="password"
                                    autofocus
                                >
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror    
                            </div>
                            <div>
                                <button class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
