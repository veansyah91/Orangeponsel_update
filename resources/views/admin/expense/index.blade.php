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
        margin-bottom: 2rem;
    }
</style>
@endpush

@section('content')

<div class="container" id="expense-container" data-outlet-id="{{ $outletUser['outlet_id'] }}">
    <div class="row justify-content-center">
        <div class="col-md-10 col-12">
            <div class="card">
                <div class="card-header h3">
                    <div class="row">
                        <div class="col-12 col-md-6" >
                            Pengeluaran<span id="count-data" style="color: gray;font-size:20px;margin-left:10px"></span>
                        </div>
                        <div class="col-12 col-md-6 text-md-right text-center mt-2 mt-md-0">
                            <div class="row">
                                <div class="col-12 col-md-9">
                                    <form onsubmit="submitSearchExpense(event)">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Cari..." aria-label="Cari..." 
                                            id="search-expense">
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
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#createExpenseModal" onclick="addData()">Tambah</button>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                </div>
                <div class="card-body table-responsive" style="height: 450px" >
                    <table class="table table-sm mt-2 table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>No. Ref</th>
                                <th>Item</th>
                                <th>Kas</th>
                                <th class="text-right">Jumlah</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="expense-list-table">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createExpenseModal" tabindex="-1" aria-labelledby="createExpenseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="createExpenseModalLabel">Tambah Data Pengeluaran</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group row">
                            <label for="no-ref" class="col-sm-4 col-form-label">Nomor</label>
                            <div class="col-sm-8">
                              <input type="text" readonly class="form-control" id="no-ref">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group row">
                            <label for="item" class="col-md-4 col-form-label">Tanggal</label>
                            <div class="col-md-8">
                                <div class="datepicker date input-group p-0 shadow-sm">
                                    <input type="text" placeholder="Pilih Tanggal" class="form-control date-input-type" id="date" data-order="0" onchange="changeDateInputFunc(this)" autocomplete="false">
                                    <div class="input-group-append"><span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar" viewBox="0 0 16 16">
                                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                      </svg></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label for="item-expense">Item</label>
                            <div class="input-group mb-3">
                                <div class="dropdown w-100">
                                    <input 
                                        id="item-expense"
                                        class="form-control" 
                                        type="text" 
                                        data-toggle="dropdown" aria-expanded="false"  data-reference="parent" placeholder="Item"
                                        autocomplete="off"
                                        onkeyup="expenseDropdownKeyup(this)"
                                        onclick="expenseDropdownFocus(this)"
                                        onchange="expenseDropdownChange(this)"
                                    >

                                    <div class="dropdown-menu w-100 overflow-auto" id="expense-list" style="max-height:180px">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label for="select-cash">Kas</label>
                            <div class="input-group mb-3">
                                <select id="select-cash" class="custom-select w-100" onchange="selectCashier(this)">
                                    <option value="-1">-- Pilih Kas --</option>
                                    <option value="0" class="text-danger font-weight-bold">Tidak Tunai</option>
                                    @foreach ($cashAccounts as $cashAccount)
                                        <option value="{{ $cashAccount->id }}">{{ $cashAccount->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="value">Nilai</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control text-right" id="value" inputmode="numeric" inputmode="numeric" onkeyup="setCurrencyFormat(this)">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 d-none" id="supplier-container">
                        <div class="form-group">
                            <label for="supplier">Supplier</label>
                            <div class="input-group mb-3">
                                <input 
                                        id="supplier"
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
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="description">Deskripsi (optional)</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="description" onchange="handleChangeDescription(event)">
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

<div class="modal fade" id="detailExpenseModal" tabindex="-1" aria-labelledby="detailExpenseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="detailExpenseModalLabel">Detail Pengeluaran</h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td style="width: 25%">Tanggal</td>
                                <th>: <span id="date-detail"></span> </th>
                            </tr>
                            <tr>
                                <td style="width: 25%">No. Ref</td>
                                <th>: <span id="no-ref-detail"></span> </th>
                            </tr>
                            <tr>
                                <td style="width: 25%">Nama Kas</td>
                                <th>: <span id="cash-name-detail"></span> </th>
                            </tr>
                            <tr  id="supplier-detail-row">
                                <td style="width: 25%">Nama Supplier</td>
                                <th>: <span id="supplier-name-detail"></span> </th>
                            </tr>
                            <tr>
                                <td style="width: 25%">Jenis Pengeluaran</td>
                                <th>: <span id="expense-name-detail"></span> </th>
                            </tr>
                            <tr>
                                <td style="width: 25%">Jumlah Pengeluaran</td>
                                <th>: <span id="value-detail"></span> </th>
                            </tr>
                            
                            <tr>
                                <td style="width: 25%">Deskripsi</td>
                                <th>: <span id="description-detail"></span> </th>
                            </tr>
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
    <script src="{{ asset('js/expense/index.js') }}">
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
