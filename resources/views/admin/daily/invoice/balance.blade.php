@extends('layouts.app')

@push('stylesheets')    
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<style>
    .datepicker td, .datepicker th {
        width: 2.5rem;
        height: 2.5rem;
        font-size: 0.85rem;
    }

    .datepicker {
        margin-bottom: 3rem;
    }

    @media screen and (min-width: 480px) {
        #cashier-input{
            height: 500px
        }
    }

    


</style>
@endpush

@section('content')

<div class="container">
    <div class="row justify-content-center" id="outlet" data-id="{{ $outlet['id'] }}">
        <div class="col-12 col-md-8">
            <div class="card" id="cashier-input">
                <div class="card-header h3">
                    Top Up
                </div>
                <form onsubmit="handleTopUpInvoiceForm(event)">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group row">
                                    <label for="nomor-nota" class="col-sm-4 col-form-label">Nomor</label>
                                    <div class="col-sm-8">
                                    <input type="text" readonly class="form-control" id="nomor-nota">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-12">
                                <div class="form-group row">
                                    <label for="pelanggan" class="col-md-4 col-form-label">Pelanggan</label>

                                    <div class="col-md-8">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" id="pelanggan" readonly>
                                            <button type="button" class="btn-outline-secondary btn btn-sm" data-toggle="modal" data-target="#searchModal" id="cari-pelanggan">
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
                            <div class="col-md-4 col-12">
                                <div class="form-group row">
                                    <label for="select-server" class="col-sm-4 col-form-label">Server</label>
                                    <div class="col-sm-8">
                                        <select id="select-server" class="custom-select w-100" onchange="selectServer(this)">
                                            <option value="0">-- Pilih Server --</option>
                                            @foreach ($servers as $server)
                                                <option value="{{ $server->id }}">{{ $server->name }}</option>
                                            @endforeach
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-12">
                                <div class="form-group row">
                                    <label for="address-no" class="col-sm-3 col-form-label">ID</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="address-no" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 col-12">
                                <div class="form-group row">
                                    <label for="product" class="col-sm-4 col-form-label">Produk</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="product" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group row">
                                    <label for="unit-cost" class="col-sm-4 col-form-label">Modal</label>
                                    <div class="col-sm-8">
                                    <input type="text" class="form-control text-right" id="unit-cost" required inputmode="numeric" onkeyup="setCurrencyFormat(this)" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-12">
                                <div class="form-group row">
                                    <label for="selling-price" class="col-sm-4 col-form-label">Jual</label>
                                    <div class="col-sm-8">
                                    <input type="text" class="form-control text-right" id="selling-price" inputmode="numeric" onkeyup="setCurrencyFormat(this)" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8 col-12">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="custom-control custom-switch my-auto">
                                            <input type="checkbox" class="custom-control-input" id="is-paid" name="is-paid" 
                                            onclick="togglePaid(this)" checked>
                                            <label class="custom-control-label" for="is-paid" id="label-is-paid">Lunas</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group row" id="is-cash">
                                            <label for="select-cash" class="col-sm-4 col-form-label">Kasir</label>
                                            <div class="col-sm-8">
                                                <select id="select-cash" class="custom-select w-100" onchange="selectCashier(this)">
                                                    <option value="0">-- Pilih Kas --</option>
                                                    @foreach ($cashAccounts as $cashAccount)
                                                        <option value="{{ $cashAccount->id }}">{{ $cashAccount->name }}</option>
                                                    @endforeach
                                                    
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div class="col-md-4 col-12 text-right">
                                <button class="button-submit btn btn-primary disabled " type="submit" id="btn-create" disabled>
                                    Simpan
                                </button>
                                <button class="btn btn-success d-none button-submit" type="submit" id="btn-update">
                                    Ubah
                                </button>
                                <button class="btn btn-outline-success d-none" type="button" id="btn-cancel-update" onclick="cancelUpdateFunc()">
                                    Batal
                                </button>
                            </div>
                        </div>
                        
                    </div>
                </form>
                <div class="card-footer h5">
                    <table class="table">
                        <tbody id="table-server-balance">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card" style="height: 500px">
                <div class="card-header h3">
                    <div class="row justify-content-between">
                        <div class="col-6">
                            Detail
                        </div>
                        <div class="col-6 text-right" id="total-transaction">
                            
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Server</th>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody id="table-top-up-invoice">

                        </tbody>
                        
                    </table>
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

<div class="modal fade" id="invoiceDetailModal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Detail Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body w-75" id="modal-body">
                <table class="table">
                    <tbody id="table-top-up-invoice-detail">
                        
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/invoice/topup.js') }}">
    </script>

    <script>
         // INITIALIZE DATEPICKER PLUGIN
        $('.datepicker').datepicker({
            clearBtn: true,
            autoclose: true,
            format: "dd/mm/yyyy"
        });

    </script>
@endpush
