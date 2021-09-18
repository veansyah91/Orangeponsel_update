<div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header h3">
                        Riwayat Penagihan
                    </div>
                    <div class="card body">
                        <center>
                            <table class="table table-responsive table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">Nomor HP</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Terlambat</th>
                                        <th class="text-center">Penangguhan</th>
                                        <th class="text-center">Keterangan</th>
                                        <th class="text-center">Tanggal Penagihan</th>
                                        <th class="text-center">Kolektor</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($data->isNotEmpty())
                                        @foreach ($data as $d)
                                            <tr>
                                                <td class="text-center">{{ $d->no_hp }}</td>
                                                <td class="text-center">{{ $d->nama }}</td>
                                                <td class="text-center">{{ $d->terlambat }} hari</td>
                                                <td class="text-center">{{ $d->tenggang }}</td>
                                                <td class="text-center">{{ $d->keterangan }}</td>
                                                <td class="text-center">{{ $d->tanggal_penagihan }}</td>
                                                <td class="text-center">{{ $d->collector }}</td>
                                            </tr>
                                        @endforeach                                        
                                    @else
                                    <tr>
                                        <td class="text-center" colspan="5">
                                            <i>Belum Ada Data</i>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </center>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
