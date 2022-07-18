@extends('layouts.app')

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endpush

@section('content')

<div class="container">
    {{-- <livewire:invoice-index /> --}}

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header h3">
                    <div class="row">
                        <div class="col-md-4 my-auto">Invoice</div>
                        
                        @role("SUPER ADMIN")
                            <div class="col-md-4 offset-md-4 text-right">
                                <select class="custom-select" wire:model="selectOutlet" wire:click="selectOutlet()">
                                    @foreach ($outlets as $outlet)
                                        <option value="{{ $outlet->id }}">{{ $outlet->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" id="outlet-id" value="{{ $outlet_id }}">
                        @endrole
                    </div>
                </div>

                <div class="card-body">
                    {{-- input data --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="nomor_nota" class="col-sm-4 col-form-label">Nomor</label>
                                <div class="col-sm-8">
                                  <input type="text" readonly class="form-control" id="nomor_nota">
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="pelanggan" class="col-md-4 col-form-label">Pelanggan</label>

                                <div class="col-md-8">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="pelanggan" readonly>
                                        <button class="btn-outline-secondary btn btn-sm" data-toggle="modal" data-target="#searchModal" id="cari-pelanggan">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                            </svg>
                                        </button>
                                      </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="kode" class="col-md-4 col-form-label">Kode</label>

                                <div class="col-md-8">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="kode" disabled>
                                        <button class="btn-outline-secondary btn btn-sm" data-toggle="modal" data-target="#searchModal" id="cari-produk">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                            </svg>
                                        </button>
                                      </div>
                                </div>
                            </div>
                            
                        </div>

                        <input type="hidden" id="id-produk">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="produk" class="col-sm-4 col-form-label">Produk</label>
                                <div class="col-sm-8">
                                  <input type="text" readonly class="form-control" id="produk">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="harga" class="col-sm-4 col-form-label">Harga</label>
                                <div class="col-sm-8">
                                  <input class="form-control text-right" id="harga" type="text" inputmode="numeric">
                                </div>
                            </div>
                        </div>
                        
                        
                        
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="jumlah" class="col-sm-4 col-form-label">Jumlah</label>
                                <div class="col-sm-8">
                                  <input type="text" value="1" class="form-control text-right" id="jumlah">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 text-right" id="submit-product">
                            <button class="btn btn-secondary d-none" id="cancel-edit-product-button">
                                Batal
                            </button>
                            <button class="btn btn-primary btn-submit disabled" id="add-product-button" disabled>
                                Tambah
                            </button>
                            <button class="btn btn-success btn-submit disabled d-none" id="edit-product-button" disabled>
                                Ubah
                            </button>
                        </div>
                    </div>

                    <div class="row justify-content-between mt-3 bg-info p-3">
                        <div class="col-md-6 h3 my-auto text-center text-lg-left">
                            Total : <span class="total-invoice"></span>
                        </div>
                        <div class="col-md-6 text-right mt-2 mt-lg-0">
                            <button class="btn btn-light font-weight-bold btn-submit disabled" disabled id="btn-bayar" data-toggle="modal" data-target="#bayarModal" >
                                Bayar
                            </button>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12 font-weight-bold h4">
                            Detail
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table-sm table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Kode</th>
                                        <th>Produk</th>
                                        <th>Qty</th>
                                        <th>Harga</th>
                                        <th>Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="detail-invoice" id="detail-invoice">
                                   
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-body">
                <input class="form-control" type="text" placeholder="Masukkan Nama Pelanggan">
                <div class="list-group mt-2" id="list-pelanggan">
                
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="submit-button">Submit</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="bayarModal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bayar-modal-title">Bayar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body table-responsive" id="bayar-modal-body">
                <table class="table table-sm">
                    <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th>Kode</th>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="detail-list">
                        
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-right" colspan="5">Grand Total</th>
                            <th class="text-right total-invoice"></th>
                        </tr>
                        <tr>
                            <th class="text-right" colspan="5">Bayar</th>
                            <th class="text-right">
                                <input type="text" class="form-input text-right" id="input-payment" value="0">
                            </th>
                        </tr>
                        <tr>
                            <th class="text-right" colspan="5">Sisa</th>
                            <th class="text-right" id="sisa"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="pay-confirmation-button" data-dismiss="modal" data-outlet-name="{{ $outlet->nama }}" data-outlet-address="{{ $outlet->alamat }}" data-user="{{ Auth::user()->name }}">Bayar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/invoice/index.js') }}">
        
    </script>
@endpush
