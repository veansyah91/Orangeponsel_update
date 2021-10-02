<div>
    <div class="row justify-content-center">
        <div class="col-12 col-md-10">
            <div class="card">
                <div class="card-header">
                    <div class="row justify-content-between">
                        <div class="col-12 col-md-6 h3">
                            Detail Nota
                        </div>
                        <div class="col-12 col-md-6 text-right">
                            <button class="btn btn-sm btn-secondary" wire:click="backButton">kembali</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="detail-info">
                        <div class="row mt-2">
                            <div class="col-4 font-weight-bold">
                                Nomor Nota  
                            </div>
                            <div class="col-8">
                                : {{ $itemEntry['nomor_nota'] }}
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-4 font-weight-bold">
                                Tanggal Masuk 
                            </div>
                            <div class="col-8">
                                : {{ $itemEntry['tanggal_masuk'] }}
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="detail-product">
                        <div class="form-input">
                            <form action=""></form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
