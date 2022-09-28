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

<div class="container" id="purchase-return-container" data-outlet-id="{{ $outletUser['outlet_id'] }}">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header h3">
                    <div class="row">
                        <div class="col-12 col-md-6" >
                            Retur Pembelian<span id="count-data" style="color: gray;font-size:20px;margin-left:10px"></span>
                        </div>
                        <div class="col-12 col-md-6 text-md-right text-center mt-2 mt-md-0">
                            <div class="row">
                                <div class="col-12 col-md-9">
                                    <form onsubmit="submitSearchPurchaseReturn(event)">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Cari..." aria-label="Cari..." 
                                            id="search-purchase-return">
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
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#createPurchaseReturnModal" onclick="addData()">Tambah</button>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                </div>
                <div class="card-body table-responsive" style="height: 450px" >
                    <table class="table table-sm mt-2 table-hover">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Tanggal Pengiriman</th>
                                <th>No. Invoice</th>
                                <th>Nama</th>
                                <th class="text-right">Jumlah</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="purchase-return-list-table">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createPurchaseReturnModal" tabindex="-1" aria-labelledby="createPurchaseReturnModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="createPurchaseReturnModalLabel">Tambah Data Retur Pembelian</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group row">
                            <label for="invoice_number" class="col-sm-4 col-form-label">Nomor</label>
                            <div class="col-sm-8">
                              <input type="text" readonly class="form-control" id="invoice_number">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
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
                    <div class="col-md-4 col-12">
                        <div class="form-group row">
                            <label for="status-approvement" class="col-sm-4 col-form-label">Status</label>
                            <div class="col-sm-8">
                                <select id="status-approvement" class="custom-select w-full" onchange="selectSetStatusApprovement(this)">
                                    <option value="menunggu">Menunggu</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                </div>

                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="date-delivery">Tanggal Pengiriman</label>
                            <div class="datepicker date input-group p-0 shadow-sm">
                                <input type="text" placeholder="Pilih Tanggal" class="form-control date-input-type" id="date-delivery" data-order="0" onchange="changeDateInputFunc(this)" autocomplete="false">
                                <div class="input-group-append"><span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar" viewBox="0 0 16 16">
                                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                  </svg></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="date-accepted-on-supplier">Tanggal Diterima Supplier (optional)</label>
                            <div class="datepicker date input-group p-0 shadow-sm">
                                <input type="text" placeholder="Pilih Tanggal" class="form-control date-input-type" id="date-accepted-on-supplier" data-order="0" onchange="changeDateInputFunc(this)" autocomplete="false">
                                <div class="input-group-append"><span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar" viewBox="0 0 16 16">
                                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                  </svg></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="date-receipt">Tanggal Persetujuan Retur</label>
                            <div class="datepicker date input-group p-0 shadow-sm">
                                <input type="text" placeholder="Pilih Tanggal" class="form-control date-input-type" id="date-receipt" data-order="0" onchange="changeDateInputFunc(this)" autocomplete="false">
                                <div class="input-group-append"><span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar" viewBox="0 0 16 16">
                                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                  </svg></span>
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
                                    <th class="text-right">Harga</th>
                                    <th class="text-right">Total</th>
                                    <th class="text-right">Total Disetujui</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="purchase-return-input-list">
                                
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
                                            <th>Grand Total</th>
                                            <th>
                                                <div class="col-12 text-right">
                                                    <input type="text" class="form-control text-right" id="grand-total-approvement">
                                                </div>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Kasir</th>
                                            <th>
                                                <div class="col-12 text-right">
                                                    <select id="select-cash" class="custom-select w-full" onchange="selectCashier(this)">
                                                        <option value="0">-- Pilih Kas --</option>
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

<div class="modal fade" id="detailPurchaseReturnModal" tabindex="-1" aria-labelledby="detailPurchaseReturnModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="detailPurchaseReturnModalLabel">Detail Retur Pembelian</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row -mb-1">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="no-ref-detail">No Ref : </label>
                            <input type="text" readonly class="form-control-plaintext font-weight-bold" id="no-ref-detail" value="PR/OP2/20220924015">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="supplier-name-detail">Nama Supplier : </label>
                            <input type="text" readonly class="form-control-plaintext font-weight-bold" id="supplier-name-detail" value="OPPO">
                        </div>
                    </div>
                    <div class="col-12 col-md-4" id="cash-detail-container">
                        <div class="form-group">
                            <label for="cash-detail">Kas : </label>
                            <input type="text" readonly class="form-control-plaintext font-weight-bold" id="cash-detail" value="OPPO">
                        </div>
                    </div>
                </div>
                <div class="row border-top pt-2">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="date-delivery-detail">Tanggal Pengiriman : </label>
                            <input type="text" readonly class="form-control-plaintext font-weight-bold" id="date-delivery-detail" value="PR/OP2/20220924015">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="date-accepted-on-supplier-detail">Tanggal Diterima Supplier : </label>
                            <input type="text" readonly class="form-control-plaintext font-weight-bold" id="date-accepted-on-supplier-detail" value="OPPO">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="date-receipt-detail">Tanggal Persetujuan Retur : </label>
                            <input type="text" readonly class="form-control-plaintext font-weight-bold" id="date-receipt-detail" value="OPPO">
                        </div>
                    </div>
                </div>
                <div class="row border-top pt-2">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="value-detail">Nilai Pengajuan : </label>
                            <input type="text" readonly class="form-control-plaintext font-weight-bold text-right" id="value-detail" value="PR/OP2/20220924015">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="value-approvement-detail">Nilai Disetujui : </label>
                            <input type="text" readonly class="form-control-plaintext font-weight-bold text-right" id="value-approvement-detail" value="OPPO">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="status-detail">Status : </label>
                            <input type="text" readonly class="form-control-plaintext font-weight-bold text-center" id="status-detail" value="OPPO">
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
                                    <th class="text-right">Harga</th>
                                    <th class="text-right">Total</th>
                                    <th class="text-right">Total Disetujui</th>
                                </tr>
                            </thead>
                            <tbody id="purchase-return-detail-list">
                                
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
    <script src="{{ asset('js/purchase-return/index.js') }}">
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
