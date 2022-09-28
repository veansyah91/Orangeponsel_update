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

<div class="container" id="ledger" data-outlet-id="{{ $outletUser->outlet_id }}">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header ">
                    <div class="row justify-content-between">
                        <div class="col-lg-7 col-12 h3 my-auto">
                            Buku Besar <span id="count-ledger" style="color: gray;font-size:20px;margin-left:10px"></span>
                        </div>
                        <div class="col-lg-5 col-12 text-right">
                            <div class="row">
                                    <div class="btn-group w-100">
                                        <input class="form-control dropdown-toggle dropdown-toggle-split" type="text" data-toggle="dropdown" aria-expanded="false"  data-reference="parent" placeholder="Pilih Akun" id="search-account"
                                        autocomplete="off">
                                        <a class="btn" id="create-account-category-modal" href="{{ route('ledger.account') }}" target="_blank">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                            </svg>
                                        </a>
                                        
                                        <div class="dropdown-menu w-100 overflow-auto" style="max-height:180px" id="account-dropdown-list">
                                            
                                        </div>
                                        <button class="btn btn-outline-secondary" onclick="handleFilterSubmit()">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                                                <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                                            </svg>
                                        </button>
                                        <button class="btn btn-outline-secondary" data-toggle="modal" data-target="#filterModal"
                                        onclick="filterLedgerButton()">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filter" viewBox="0 0 16 16">
                                                <path d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                </div>

                <div class="card-body overflow-auto" style="height: 450px" onscroll="journalTableScroll(this)">
                    <table class="table table-sm fixed-header table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>No. Referensi</th>
                                <th>Keterangan</th>
                                <th class="text-right">Debit (IDR)</th>
                                <th class="text-right">Kredit (IDR)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="ledger-table-body">
                            <tr>
                                <td colspan="7" class="text-center font-italic">
                                    Pilih akun terlebih dahulu
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-dark text-white">
                    <div class="row">
                        <div class="col-12 col-md-8">
                            <div class="row">
                                <div class="col-12 col-md-4 font-weight-bold ">
                                    <div>Saldo Awal</div>
                                    <div class="h4" id="opening-balance">Rp. -</div>
                                </div>
                                <div class="col-12 col-md-4 font-weight-bold ">
                                    <div>Mutasi</div>
                                    <div class="h4" id="mutation">Rp. -</div>
                                    
                                </div>
                                <div class="col-12 col-md-4 font-weight-bold ">
                                    <div>Saldo Akhir</div>
                                    <div class="h4" id="ending-balance">
                                        Rp. -
                                    </div>

                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="row">
                                <div class="col-12 col-md-6 font-weight-bold text-right">
                                    <div>
                                        Total Debit
                                    </div>
                                    <div class="h5" id="total-debit">
                                        Rp. -
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 font-weight-bold text-right">
                                    <div>
                                        Total Kredit
                                    </div>
                                    <div class="h5" id="total-credit">
                                        Rp. -
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal --}}
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
                        <option value="2">MInggu Ini</option>
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
                    <button type="button" class="btn btn-primary" id="filter-submit" data-dismiss="modal" onclick="handleFilterSubmit(event)">Filter</button>
                </div>
                
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/ledger/index.js') }}">
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
