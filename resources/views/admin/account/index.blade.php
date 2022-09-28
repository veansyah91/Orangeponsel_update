@extends('layouts.app')

@push('stylesheets')
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endpush

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-12">
            <div class="card">
                <div class="card-header ">
                    <div class="row justify-content-between">
                        <div class="col-lg-6 h3 my-auto">
                            Akun
                        </div>
                        <div class="col-lg-6 text-right">
                            <Button class="btn btn-primary" data-outlet-id="{{ $outletUser->outlet_id }}" id="create-account-button"
                            data-toggle="modal" data-target="#createAccountModal">Buat Akun</Button>
                        </div>
                    </div>
                    
                </div>

                <div class="card-body overflow-auto" style="height: 450px">
                    <table class="table table-sm fixed-header table-hover">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="account-table-body">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="createAccountModal" tabindex="-1" aria-labelledby="createAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="createAccountCategoryModalContent">
            <div class="modal-header">
                <h5 class="modal-title" id="createAccountModalLabel">Tambah Akun</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id-account">
                <div class="form-group">
                    <label for="categoryAccount">Kategori</label>
                    <div>
                        <div class="btn-group w-100">
                            <input class="form-control dropdown-toggle dropdown-toggle-split" type="text" data-toggle="dropdown" aria-expanded="false"  data-reference="parent" placeholder="Kategori Akun" id="categoryAccount"
                            autocomplete="off">
                            <button class="btn" data-dismiss="modal" data-toggle="modal" data-target="#createAccountCategoryModal" id="create-account-category-modal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                </svg>
                            </button>
                            
                            <div class="dropdown-menu w-100 overflow-auto" style="max-height:180px" id="account-category-dropdown-list">
                              
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="codeAccount">Kode</label>
                    <input type="text" class="form-control" id="codeAccount" placeholder="Kode">
                </div>
                <div class="form-group">
                    <label for="nameAccount">Nama</label>
                    <input type="text" class="form-control" id="nameAccount" placeholder="Nama" autocomplete="off">
                </div>
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="cashAccount">
                    <label class="custom-control-label" for="cashAccount">Atur Sebagai Kas/Bank</label>
                </div>
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="isActiveAccount" checked>
                    <label class="custom-control-label" for="isActiveAccount">Aktif</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary d-none" id="add-account-button" data-dismiss="modal">Tambah</button>
                <button type="button" class="btn btn-primary d-none" id="edit-account-button" data-dismiss="modal">Ubah</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createAccountCategoryModal" tabindex="-1" aria-labelledby="createAccountCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createAccountCategoryModalLabel">Kategori Akun</h5>
                <button type="button" class="close category-account-close" data-dismiss="modal" aria-label="Close" data-toggle="modal" data-target="#createAccountModal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div>
                    <input type="hidden" id="categoryAccountId">
                    <div class="form-group">
                        <label for="categoryAccountName">Nama</label>
                        <input type="text" class="form-control" id="categoryAccountName" placeholder="Nama">
                    </div>
                    <div class="text-right">
                        <button class="btn btn-primary btn-submit" id="create-category-account-button" disabled>
                            Tambah
                        </button>
                        <button class="btn btn-primary btn-submit d-none" id="edit-category-account-button">
                            Ubah
                        </button>
                    </div>
                    
                </div>
                
                <div style="max-height: 150px" class="overflow-auto mt-2">
                    <table class="table">
                        <tbody id="accountCategotyDetail">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary category-account-close" data-dismiss="modal" data-toggle="modal" data-target="#createAccountModal">Batal</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/account/index.js') }}">
    </script>
@endpush
