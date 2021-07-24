<div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                    @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                    @endif
                @if ($showRegisterUser)
                    <livewire:register-user />
                @elseif ($editRegisterUser)
                    <livewire:register-edit/>
                @else
                    <div class="card">
                        <div class="card-header h4">Detail Pengguna</div>
                        
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <button 
                                        class="btn btn-primary btn-sm" 
                                        wire:click="showRegisterUser()"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                        </svg> Tambah Pengguna
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-sm table-responsive">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Nama</th>
                                                <th>Email</th>
                                                <th>Password</th>
                                                <th>Outlet</th>
                                                <th><i>Role</i></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            @foreach ($users as $user)
                                                <tr>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-success" wire:click="resetPassword({{ $user->user_id }})">Reset Password</button>
                                                    </td>
                                                    <td>
                                                        {{ Outlet::getOutlet($user->outlet_id)->nama }}
                                                    </td>
                                                    <td>
                                                        {{ Role::getRole($user->user_id)->name }}
                                                    </td>
                                                    <th>
                                                        <button class="btn btn-sm btn-danger" wire:click="deleteUser({{ $user->user_id }})">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                                            </svg>
                                                        </button>
                                                        <button class="btn btn-sm btn-success" wire:click="editUser({{ $user->user_id }})">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                                            </svg>
                                                        </button>
                                                    </th>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            @role("SUPER ADMIN")
                                <div class="row mt-5">
                                    <div class="col-sm-12">
                                        <table class="table table-sm table-responsive">
                                            <thead class="text-center">
                                                <tr>
                                                    <th>Nama</th>
                                                    <th>Email</th>
                                                    <th>Password</th>
                                                    <th>Mirtra Kredit</th>
                                                    <th><i>Role</i></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                @foreach ($partners as $partner)
                                                    <tr>
                                                        <td>{{ $partner->name }}</td>
                                                        <td>{{ $partner->email }}</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-success" wire:click="resetPassword({{ $partner->user_id }})">Reset Password</button>
                                                        </td>
                                                        <td>
                                                            {{ User::getCreditSales($partner->credit_partner_id)->alias }}
                                                        </td>
                                                        <td>
                                                            {{ Role::getRole($partner->user_id)->name }}
                                                        </td>
                                                        <th>
                                                            <button class="btn btn-sm btn-danger" wire:click="deleteUser({{ $partner->user_id }})">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                                                </svg>
                                                            </button>
                                                            <button class="btn btn-sm btn-success" wire:click="editUser({{ $partner->user_id }})">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                                                </svg>
                                                            </button>
                                                        </th>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endrole
                            
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
