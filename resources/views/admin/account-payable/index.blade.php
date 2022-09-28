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

<div class="container" id="account-payable-container" data-outlet-id="{{ $outletUser['outlet_id'] }}">
    <div class="row justify-content-center">
        <div class="col-md-10 col-12">
            <div class="card">
                <div class="card-header h3">
                    <div class="row">
                        <div class="col-12 col-md-6" >
                            Daftar Hutang <span id="count-data" style="color: gray;font-size:20px;margin-left:10px"></span>
                        </div>
                        <div class="col-12 col-md-6 text-md-right text-center mt-2 mt-md-0">
                            <form onsubmit="submitSearchAccountPayable(event)">
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
                    </div>
                    
                </div>
                <div class="card-body table-responsive" style="height: 450px" >
                    <div class="row">
                        <div class="col-12 text-right">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="paid-status" onclick="handleShowPaidStatus()">
                                <label class="form-check-label" for="paid-status">
                                    Tampilkan Yang Lunas
                                </label>
                            </div>
                        </div>
                    </div>
                    <table class="table table-sm mt-2">
                        <thead>
                            <tr>
                                <th>Nama Supplier</th>
                                <th class="text-right">Sisa (IDR)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="account-payable-list-table">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailAccountPayableModal" tabindex="-1" aria-labelledby="detailAccountPayableModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="detailAccountPayableModalLabel">Rincian Hutang</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 h5 font-weight-bold">
                        Belum Lunas
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
    <script src="{{ asset('js/account-payable/index.js') }}">
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
