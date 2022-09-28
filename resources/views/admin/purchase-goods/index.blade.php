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

<div class="container" id="purchase-goods-container" data-outlet-id="{{ $outletUser['outlet_id'] }}">
    <div class="row justify-content-center">
        <div class="col-md-10 col-12">
            <div class="card">
                <div class="card-header h3">
                    <div class="row">
                        <div class="col-12 col-md-6" >
                            Pembelian Barang Dagang<span id="count-data" style="color: gray;font-size:20px;margin-left:10px"></span>
                        </div>
                        <div class="col-12 col-md-6 text-md-right text-center mt-2 mt-md-0">
                            <div class="row">
                                <div class="col-12 col-md-9">
                                    <form onsubmit="submitSearchPurchaseGoods(event)">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Cari..." aria-label="Cari..." 
                                            id="search-purchase-goods">
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
                                <div class="col-12 col-md-3">
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#createPurchaseGoodsModal" onclick="addData()">Tambah</button>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                </div>
                <div class="card-body table-responsive table-hover" style="height: 450px" >
                    <table class="table table-sm mt-2">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>No. Invoice</th>
                                <th>Supplier</th>
                                <th class="text-right">Jumlah</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="purchase-goods-list-table">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createPurchaseGoodsModal" tabindex="-1" aria-labelledby="createPurchaseGoodsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="createPurchaseGoodsModalLabel">Tambah Data Retur Penjualan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group row">
                            <label for="invoice_number" class="col-sm-4 col-form-label">Nomor</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control" id="invoice_number" onchange="handleInvoiceNumberChange(this)">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="supplier-input" class="col-md-4 col-form-label">Supplier</label>

                            <div class="col-md-8">
                                <div class="input-group mb-3">
                                    <div class="dropdown w-100">
                                        <input 
                                            id="supplier-input"
                                            class="form-control" 
                                            type="text" 
                                            data-toggle="dropdown" aria-expanded="false"  data-reference="parent" placeholder="Supplier"
                                            autocomplete="off"
                                            onkeyup="supplierDropdownKeyup(this)"
                                            onclick="supplierDropdownFocus(this)"
                                            onchange="supplierDropdownChange(this)"
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
                    <div class="col-12 table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-right">Qty</th>
                                    <th class="text-right">Modal</th>
                                    <th class="text-right">Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="purchase-goods-input-list">
                                
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5">
                                        <button class="btn btn-secondary w-100" onclick="handleAddRowInputList()">Tambah</button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="row justify-content-end">
                            <div class="co-12 col-md-6">
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <th>Grand Total</th>
                                            <th class="d-flex">
                                                <div class="col-2">
                                                    :
                                                </div>
                                                <div class="col-10 text-right" id="grand-total">
                                                    Rp. -
                                                </div>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Kasir</th>
                                            <th>
                                                <div class="col-12 text-right">
                                                    <select id="select-cash" class="custom-select w-full" onchange="selectCashier(this)">
                                                        <option value="-1">-- Pilih Kas --</option>
                                                        <option value="0" class="text-danger font-weight-bold">Tidak Tunai</option>
                                                        @foreach ($cashAccounts as $cashAccount)
                                                            <option value="{{ $cashAccount->id }}">{{ $cashAccount->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                <div class="row justify-between">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary ml-md-2 d-none btn-submit" id="submit-create" onclick="handleSubmitCreate()" data-dismiss="modal">Simpan</button>
                    <button type="button" class="btn btn-success ml-md-2 d-none btn-submit" id="submit-edit" onclick="handleSubmitEdit()" data-dismiss="modal">Ubah</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailPurchaseGoodsModal" tabindex="-1" aria-labelledby="detailPurchaseGoodsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="detailPurchaseGoodsModalLabel">Detail Pembelian Barang Dagang</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-md-8">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th>No. Invoice</th>
                                <th>: <span id="invoice-number-detail"></span> </th>
                            </tr>
                            <tr>
                                <th>Nama Supplier</th>
                                <th>: <span id="supplier-name-detail"></span> </th>
                            </tr>
                            <tr>
                                <th>Tanggal Pembelian Barang Dagang</th>
                                <th>: <span id="date-detail"></span> </th>
                            </tr>
                            <tr>
                                <th>Jumlah Pembelian Barang Dagang</th>
                                <th>: <span id="value-detail"></span> </th>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Kode/IMEI</th>
                                    <th>Produk</th>
                                    <th class="text-right">Qty</th>
                                    <th class="text-right">Harga</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody id="purchase-goods-detail-list">

                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                <div class="row justify-between">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('scripts')
    <script src="{{ asset('/js/purchase-goods/index.js') }}">
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
