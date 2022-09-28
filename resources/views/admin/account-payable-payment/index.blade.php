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
</style>
@endpush

@section('content')

<div class="container" id="account-payable-payment-container" data-outlet-id="{{ $outletUser['outlet_id'] }}" data-outlet-name="{{ $outlet['nama'] }}" data-outlet-address="{{ $outlet['alamat'] }}">
    <div class="row justify-content-center">
        <div class="col-md-10 col-12">
            <div class="card">
                <div class="card-header h3">
                    <div class="row">
                        <div class="col-12 col-md-6" >
                            Pembayaran Hutang <span id="count-data" style="color: gray;font-size:20px;margin-left:10px"></span>
                        </div>
                        <div class="col-12 col-md-6 text-md-right text-center mt-2 mt-md-0">
                            <div class="row">
                                <div class="col-12 col-md-9">
                                    <form onsubmit="submitSearchAccountPayablePayment(event)">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Cari..." aria-label="Cari..." 
                                            id="search-account-payable">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="submit"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-12 col-md-3 mt-2 mt-md-0 text-right">
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#createAccountPayablePaymentModal"
                                    onclick="createPayment()">Tambah</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="card-body table-responsive" style="height: 450px" >
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Tanggal Pembayaran</th>
                                <th>Nota Pembayaran</th>
                                <th>Nama Konsumen</th>
                                <th class="text-right">Jumlah (IDR)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="account-payable-payment-list-table">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createAccountPayablePaymentModal" tabindex="-1" aria-labelledby="createAccountPayablePaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="createAccountPayablePaymentModalLabel">Rincian Hutang</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="invoice_number" class="col-sm-4 col-form-label">Nomor</label>
                            <div class="col-sm-8">
                              <input type="text" readonly class="form-control" id="invoice_number">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="supplier-input" class="col-md-4 col-form-label">Pelanggan</label>

                            <div class="col-md-8">
                                <div class="input-group mb-3">
                                    <div class="dropdown w-100">
                                        <input 
                                            id="supplier-input"
                                            class="form-control account-input-dropdown" 
                                            type="text" 
                                            data-toggle="dropdown" aria-expanded="false"  data-reference="parent" placeholder="Pelanggan"
                                            autocomplete="off"
                                            onkeyup="accountDropdownKeyup(this)"
                                            onclick="accountDropdownFocus(this)"
                                            onchange="accountDropdownChange(this)"
                                        >
    
                                        <div class="dropdown-menu w-100 overflow-auto" id="supplier-list" style="max-height:180px">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="form-group row">
                            <label for="payment-value" class="col-sm-4 col-form-label">Jumlah Bayar</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control disabled" id="payment-value" disabled inputmode="numeric" inputmode="numeric" onkeyup="setCurrencyFormat(this)">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group row">
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
                <div class="row">
                    <div class="col-12 text-right">
                        <button class="btn btn-primary disabled" id="save-payment-button"
                        disabled onclick="handleSavePayment()" data-dismiss="modal">Bayar</button>
                    </div>
                </div>

                <div class="row mt-2 border-top">
                    <div class="col-12 h5 font-weight-bold mt-2">
                        Rincian Hutang
                    </div>
                    <div class="col-12 table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>No. Ref</th>
                                    <th class="text-right">Sisa</th>
                                    <th class="text-center">Tanggal Transaksi</th>
                                </tr>
                            </thead>
                            <tbody id="account-payable-detail-list-table">

                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                <div class="row justify-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('scripts')
    <script src="{{ asset('js/account-payable-payment/index.js') }}">
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
