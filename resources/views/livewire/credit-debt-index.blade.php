<div>
    <div class="container">    
        @if ($delaySet)

            <livewire:credit-collect-update />     
            
        @elseif($detail)

            @if ($pengambilan_lama)
                <livewire:credit-application-old-detail  />
            @else
                <livewire:credit-application-detail  />
            @endif  

        @else
            <div class="row justify-content-center">
                @if (session()->has('success'))
                    <center>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </center>
                @endif

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row justify-content-between">
                                <div class="col-lg-6 col-12 text-center text-lg-left h3 my-auto p-2">Penagihan Kredit</div>
                                <div class="col-lg-2 col-12 text-center text-lg-right my-auto p-2">
                                    @if ($data->isNotEmpty())
                                        @php
                                            $last_edited = explode(' ', $data[0]->updated_at)
                                        @endphp
                                    @endif
                                    

                                    <button class="btn btn-primary w-100 " wire:click="update" 

                                    @if ($data->isNotEmpty())
                                        @if ($last_edited[0] == Date('Y-m-d'))
                                            disabled
                                        @endif        
                                    @endif

                                    >
                                        Update
                                    </button>                                    
                                    
                                    @if ($data->isNotEmpty())
                                        <div>
                                            <small>terkakhir update {{ $last_edited[0] }}</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                        </div>

                        <div class="card-body">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th class="text-center">Nomor HP</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Terlambat</th>
                                        <th class="text-center"></th>
                                    </tr>
                                    
                                </thead>
                                <tbody>
                                @if ($data->isNotEmpty())
                                    
                                @else
                                    <tr>
                                        <td class="text-center" colspan="3">
                                            <i>Belum Ada Data</i>
                                        </td>
                                    </tr>
                                @endif
                                    @foreach ($data as $d)
                                        <tr>
                                            <td class="text-center">{{ $d->no_hp }}</td>
                                            <td class="text-center">{{ $d->nama }}</td>
                                            <td class="text-center">{{ $d->terlambat }} Hari</td>
                                            <td class="text-center">
                                                <button class="btn btn-dark btn-sm" wire:click="pending({{ $d->id }})">
                                                    tangguhkan
                                                </button>
                                                <button class="btn btn-success btn-sm" wire:click="detail({{ $d->id }})">
                                                    detail
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $data->links() }}
                        </div>
                    </div>
                </div>
            </div>            
        @endif
    </div>
</div>
