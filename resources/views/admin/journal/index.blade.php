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

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header ">
                    <div class="row justify-content-between">
                        <div class="col-lg-6 h3 my-auto">
                            Jurnal <span id="count-journal" style="color: gray;font-size:20px;margin-left:10px"></span>
                        </div>
                        <div class="col-lg-6 text-right">
                            <div class="row justify-content-end">
                                <div class="col-6">
                                    <form onsubmit="searchJournalButton(event)">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Cari..." aria-label="Cari..." 
                                            id="search-journal">
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
                                <div class="col-6">
                                    <button class="btn btn-outline-secondary" data-toggle="modal" data-target="#filterModal"
                                    onclick="filterJournalButton()">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filter" viewBox="0 0 16 16">
                                            <path d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z"/>
                                        </svg>
                                    </button>
                                    <button class="btn btn-outline-secondary" onclick="showJournalDefault()">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                                            <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                                        </svg>
                                    </button>
                                    <button class="btn btn-primary" data-outlet-id="{{ $outletUser->outlet_id }}" id="create-journal-button"
                                    data-toggle="modal" data-target="#createJournalModal">Buat Jurnal</button>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                </div>

                <div class="card-body overflow-auto table-responsive" style="height: 450px" id="journal-table" onscroll="journalTableScroll(this)">
                    <table class="table fixed-header table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>No. Referensi</th>
                                <th>Deskripsi</th>
                                <th>Detail</th>
                                <th  class="text-right">Nilai (IDR)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="journal-table-body">
                            <tr class="journal-table-row">
                                <td colspan="4" class="text-center">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="createJournalModal" tabindex="-1" aria-labelledby="createJournalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" id="createJournalModalContent">
            <div class="modal-header">
                <h5 class="modal-title" id="createJournalModalLabel">Tambah Jurnal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id-journal">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <div class="form-group">
                            <label for="date">Tanggal</label>
                            <div class="datepicker date input-group p-0 shadow-sm">
                                <input type="text" placeholder="Pilih Tanggal" class="form-control date-input-type" id="date-input" data-order="0" onchange="changeDateInputFunc(this)" autocomplete="false">
                                <div class="input-group-append"><span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar" viewBox="0 0 16 16">
                                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                  </svg></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-5">
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <input type="text" class="form-control" id="description-input" onchange="changeDescriptionFunc()">
                        </div>
                    </div>

                    <div class="col-12 col-lg-3 -mt-2">
                        <div class="form-group">
                            <label for="reference-no">No Referensi</label>
                            <input type="text" class="form-control" id="reference-no-input">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group mb-4">
                            <label for="detail-input">Detail (optional)</label>
                            <input type="text" class="form-control" id="detail-input" onchange="changeDetailFunc(this)">
                        </div>
                    </div>
                </div>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th style="width: 40%">Akun</th>
                            <th class="text-right" style="width: 25%">Debet</th>
                            <th class="text-right" style="width: 25%">Kredit</th>
                            <th style="width: 10%"></th>
                        </tr>
                    </thead>
                    <tbody id="account-table-body">
                        <tr class="account-table-row">
                            <td>
                                <div class="dropdown">
                                    <input 
                                        class="form-control account-input-dropdown" 
                                        type="text" 
                                        data-toggle="dropdown" aria-expanded="false"  data-reference="parent" placeholder="Akun"
                                        autocomplete="off"
                                        onkeyup="accountDropdownKeyup(this)"
                                        onclick="accountDropdownFocus(this)"
                                    >

                                    <div class="dropdown-menu w-100 overflow-auto account-list" style="max-height:180px">
                                        
                                    </div>
                                </div>
                                
                            </td>
                            <td class="text-right">
                                <input type="text" class="form-control text-right debit-input" onkeyup="debitInputKeyup(this)">
                            </td>
                            <td class="text-right">
                                <input type="text" class="form-control text-right credit-input">
                            </td>
                            <td class="text-center">
                                <button class="btn btn-danger btn-sm" onclick="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                        <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>

                        <tr class="account-table-row">
                            <td>
                                <div class="dropdown">
                                    <input class="form-control dropdown-toggle dropdown-toggle-split account-input-dropdown" type="text" data-toggle="dropdown" aria-expanded="false"  data-reference="parent" placeholder="Akun"
                                    autocomplete="off"
                                    onkeyup="accountDropdownKeyup(this)"
                                    onchange="balanceDebitCredit()">
                                    <div class="dropdown-menu w-100 overflow-auto account-list" style="max-height:180px" >
                                        
                                    </div>
                                </div>
                                
                            </td>
                            <td class="text-right">
                                <input type="text" class="form-control text-right debit-input">
                            </td>
                            <td class="text-right">
                                <input type="text" class="form-control text-right credit-input">
                            </td>
                            <td class="text-center">
                                <button class="btn btn-danger btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                        <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tbody>
                        <tr>
                            <td class="text-center" colspan="4">
                                <button class="btn btn-secondary w-100" id="add-account-row">
                                    Tambah
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-right">Total</th>
                            <th class="text-right" >Rp. <span id="total-debit-value"></span></th>
                            <th class="text-right" >Rp. <span id="total-credit-value"></span></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary d-none" id="add-journal-button" onclick="submitJournalFunction()">Tambah</button>
                <button type="button" class="btn btn-primary d-none" id="edit-journal-button" onclick="submitEditJournalFunction()" 
                data-dismiss="modal"
                >Ubah</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailJournalModal" tabindex="-1" aria-labelledby="detailJournalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" id="detailJournalModalContent">
            <div class="modal-header">
                <h5 class="modal-title" id="detailJournalModalLabel">Detail Jurnal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <table class="font-weight-bold table table-borderless">
                            <tr>
                                <td>Tanggal</td>
                                <td id="journal-detail-date">: 2022-08-19</td>
                            </tr>
                            <tr>
                                <td>Deskripsi</td>
                                <td id="journal-detail-description">: Jurnal Umum</td>
                            </tr>
                            <tr>
                                <td>Nomor Referensi</td>
                                <td id="journal-detail-reference-no">: 2022-08-19</td>
                            </tr>
                        </table>
                    </div>
                </div>
                

                <table class="table table-bordered mt-2">
                    <thead>
                        <tr class="bg-info text-white">
                            <th>Kode Akun</th>
                            <th>Nama Akun</th>
                            <th class="text-right">Debit</th>
                            <th class="text-right">Kredit</th>
                        </tr>
                    </thead>
                    <tbody id="journal-detail-body">
                        <tr>
                            <td>10000111</td>
                            <td>Kas</td>
                            <td class="text-right">Rp. 100.000</td>
                            <td class="text-right">Rp. 0</td>
                        </tr>
                        <tr>
                            <td>10000111</td>
                            <td>Kas</td>
                            <td class="text-right">Rp. 0</td>
                            <td class="text-right">Rp. 100.000</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="bg-info text-white">
                            <th colspan="2">Total</th>
                            <th class="text-right" id="journal-detail-debit">Rp. 100.000</th>
                            <th class="text-right" id="journal-detail-credit">Rp. 100.000</th>
                        </tr>
                    </tfoot>
                </table>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Filter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="filter-date">Tanggal</label>
                    <select class="custom-select" id="filter-date" onchange="changeDateFilter(this)">
                        <option selected class="text-center" value="0">------ Pilih Waktu ------</option>
                        <option value="1">Hari Ini</option>
                        <option value="2">Minggu Ini</option>
                        <option value="3">Bulan Ini</option>
                        <option value="4">Tahun Ini</option>
                        <option value="5">Custom</option>
                    </select>
                </div>
                
                <div class="row border-top d-none" id="custom-date-filter">
                    <div class="col-12 col-md-6 mt-2">
                        <div class="form-group">
                            <label for="filter-start-date">Dari</label>
                            <div class="datepicker date input-group p-0 shadow-sm">
                                <input type="text" placeholder="Pilih Tanggal" class="form-control date-input-type" id="filter-start-date" onchange="changeDateInputFunc(this)" autocomplete="off">
                                <div class="input-group-append"><span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar" viewBox="0 0 16 16">
                                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                </svg></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mt-2">
                        <div class="form-group">
                            <label for="filter-end-date">Ke</label>
                            <div class="datepicker date input-group p-0 shadow-sm">
                                <input type="text" placeholder="Pilih Tanggal" class="form-control date-input-type" 
                                id="filter-end-date"
                                onchange="changeDateInputFunc(this)" autocomplete="off">
                                <div class="input-group-append"><span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar" viewBox="0 0 16 16">
                                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                </svg></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                <div class="row justify-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>

                    <button class="btn btn-outline-primary mx-2" type="button" id="reset-filter" data-dismiss="modal" onclick="handleResetFilter()">
                        Reset
                    </button>
                    <button type="button" class="btn btn-primary" id="filter-submit" data-dismiss="modal" onclick="handleFilterSubmit(event)">Filter</button>
                </div>
                
            </div>
        </div>
    </div>
</div>


@endsection

@push('scripts')
    <script src="{{ asset('js/journal/index.js') }}">
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
