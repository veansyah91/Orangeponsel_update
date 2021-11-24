<div>
    <div class="container-fluid">
        @if ($showCreate)
            <livewire:credit-application-invoice-create :partnerId="$partnerId"/>
        @elseif ($showUpdate)
            <livewire:credit-application-invoice-update :partnerId="$partnerId"/>
        @else
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card">
                        <div class="card-header h3">
                            Pengambilan Kredit (Belum Diajukan)
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-primary" wire:click="showCreate()">Tambah Data</button>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <table class="table table-responsive table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Diinput Oleh</th>
                                                <th>Nama Konsumen</th>
                                                <th>No HP</th>
                                                <th>Kode/Imei HP</th>
                                                <th>Tipe HP</th>
                                                <th>Harga</th>
                                                <th>Email</th>
                                                <th>Password</th>
                                                <th>Status</th>
                                                <th>Outlet</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($creditInvoiceApplications -> isNotEmpty())
                                                @foreach ($creditInvoiceApplications as $creditInvoiceApplication)
                                                    <tr>
                                                        <td>{{ $creditInvoiceApplication->user_name }}</td>
                                                        <td>{{ $creditInvoiceApplication->nama }}</td>
                                                        <td>{{ $creditInvoiceApplication->no_hp }}</td>
                                                        <td>{{ Product::show($creditInvoiceApplication->product_id)->kode }}</td>
                                                        <td>{{ Product::show($creditInvoiceApplication->product_id)->tipe }}</td>
                                                        <td>Rp. {{ number_format($creditInvoiceApplication->harga,0,",",".") }}</td>
                                                        <td>{{ $creditInvoiceApplication->email ? $creditInvoiceApplication->email : '-' }}</td>
                                                        <td>{{ $creditInvoiceApplication->password ? $creditInvoiceApplication->password : '-' }}</td>
                                                        <td class="text-danger">Belum Diklaim</td>
                                                        <td>{{ Outlet::getOutlet($creditInvoiceApplication->outlet_id)->nama }}</td>
                                                        <td>
                                                            <div x-data="{ hapus: false, general: true }">
                                                                <div x-show='general'>                                                                  
                                                                    @role('SUPER ADMIN')
                                                                        <button class="btn btn-sm btn-danger" @click="hapus=true;general=false">hapus</button>
                                                                    @endrole
            
                                                                    @role('ADMIN|FRONT LINER')
                                                                        @if (User::getOutletUser(Auth::user()->id)->outlet_id == $creditInvoiceApplication->outlet_id)
                                                                            <button class="btn btn-sm btn-success" wire:click='showUpdate({{ $creditInvoiceApplication->id }})'>ubah</button>
                                                                            {{-- <button class="btn btn-sm btn-danger" @click="hapus=true;general=false">hapus</button> --}}
                                                                        @endif
                                                                    @endrole
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="11" class="text-center">
                                                        <i>Belum Ada Pengambilan Unit Handphone</i>
                                                    </td>
                                                </tr>
                                            @endif
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
        @endif

        
    </div>
    
</div>
