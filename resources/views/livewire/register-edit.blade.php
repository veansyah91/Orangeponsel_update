<div>
    <div class="card">
        <div class="card-header h4">Edit Pengguna</div>
        
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-sm-12">
                    <button 
                        class="btn btn-secondary btn-sm" 
                        wire:click="cancelRegisterUser()"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                        </svg> Batal Ubah Pengguna
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <form method="POST" wire:submit.prevent="update()">
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nama') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    name="name" value="{{ old('name') }}" 
                                    required autocomplete="name" 
                                    wire:model="name"
                                    autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    name="email" value="{{ old('email') }}" 
                                    wire:model='email'
                                    required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        @role('SUPER ADMIN')
                        <div class="form-group row">
                            <label for="outlet" class="col-md-4 col-form-label text-md-right">{{ __('Outlet') }}</label>

                            <div class="col-md-6">
                                <select id="inputState" class="form-control" name="outlet" wire:model="outlet">
                                    <option>-- Pilih Outlet --</option>
                                    @foreach ($outlets as $outlet)
                                        <option value="{{ $outlet->id }}">{{ $outlet->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @if ($partners->isNotEmpty())
                            <div class="form-group row">
                                <label for="partner" class="col-md-4 col-form-label text-md-right">Mitra Kredit</label>
                                <div class="col-md-6">
                                    <select id="inputState" class="form-control" name="partner" wire:model="partner" wire:click="selectPartner()">
                                        <option>-- Pilih Mitra Kredit --</option>
                                        @foreach ($partners as $partner)
                                            <option value="{{ $partner->id }}">{{ $partner->nama_mitra }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        @endrole

                        <div class="form-group row">
                            <label for="role" class="col-md-4 col-form-label text-md-right">{{ __('Role') }}</label>
                            <div class="col-md-6">
                                <select id="inputState" class="form-control" name="role" wire:model="role">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}"
                                        >
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Edit') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>
