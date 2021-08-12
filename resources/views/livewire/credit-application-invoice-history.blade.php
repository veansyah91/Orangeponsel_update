<div>
        @if ($showDetail)
            <livewire:credit-application-detail />
        @else
            <div class="row justify-content-center mt-2">
                <div class="col-lg-10">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="h3">
                                Riwayat
                            </div> 
                            <div>
                                <div class="form-group row my-auto">
                                    <label for="cari" class="col-sm-1 col-form-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                        </svg>
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control-plaintext" placeholder="Cari nama" id="cari" wire:model="searchName">
                                    </div>
                                </div>
                            </div>                      
                        </div>
                        <div class="card-body">

                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <table class="table table-responsive table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Di ACC Oleh</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Tanggal Pengambilan</th>
                                                <th class="text-center">Nama Konsumen</th>
                                                <th class="text-center">No HP</th>
                                                <th class="text-center">Tipe Hp</th>
                                                <th class="text-center">Harga</th>
                                                <th class="text-center">Outlet</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($histories->isNotEmpty())
                                                @foreach ($histories as $history)
                                                    <tr>
                                                        <td class="text-center">{{ $history->sales_name }}</td>
                                                        @php
                                                            $colorStatus = '';
                                                            if ($history->status == 'reject') {
                                                                $colorStatus = ' text-danger';
                                                            } elseif ($history->status == 'accept') {
                                                                $colorStatus = ' text-success';
                                                            } elseif ($history->status == 'taken') {
                                                                $colorStatus = ' text-primary';
                                                            }
                                                        @endphp
                                                        
                                                        <td class="text-center{{ $colorStatus }}">{{ $history->status }}</td>
                                                        @php
                                                            $date = explode(" ", CreditPartner::getPrice($history->id)['created_at']);
                                                        @endphp 
                                                        <td class="text-center">{{ $date[0] }}</td>

                                                        <td class="text-center">{{ $history->nama }}</td>
                                                        <td class="text-center">{{ $history->no_hp }}</td>
                                                        <td class="text-center">{{ $history->merk }}</td>

                                                        @if (CreditPartner::getPrice($history->id))
                                                            <td class="text-center">
                                                                Rp. {{ number_format(CreditPartner::getPrice($history->id)['harga']) }}
                                                            </td>
                                                        @else
                                                            <td class="text-center">
                                                                
                                                            </td>
                                                        @endif
                                                        
                                                        <td class="text-center">{{ $history->nama_outlet }}</td>
                                                        <td class="text-center">
                                                            <button class="btn btn-success btn-sm" wire:click="showDetailButton({{ $history->id }})">
                                                                detail
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach                                        
                                            @else
                                                <tr>
                                                    <td colspan="10">
                                                        <i>Data Belum Ada</i>
                                                    </td>
                                                </tr>
                                            @endif
                                            
                                        </tbody>
                                    </table>
                                    {{ $histories->links() }}

                                    @php
                                        
                                        $days = 1001;

                                        $start_date = new DateTime();
                                        $end_date = (new $start_date)->add(new DateInterval("P{$days}D") );
                                        $dd = date_diff($start_date,$end_date);

                                    @endphp
                                    Selisih: {{ $dd->y." years ".$dd->m." months ".$dd->d." days" }}
                                </div>
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
        @endif

        
    
</div>
