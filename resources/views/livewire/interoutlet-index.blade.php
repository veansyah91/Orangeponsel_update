<div>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header h3">
                    <div class="row">
                        <div class="col-md-4 my-auto">Transaksi Antar Outlet</div>
                        
                        @role('SUPER ADMIN')
                        <div class="col-md-4 offset-md-4 text-right">
                            <select class="custom-select" wire:model="selectOutlet" wire:click="changeOutlet()">
                                @foreach ($outlets as $outlet)
                                    <option value="{{ $outlet->id }}">{{ $outlet->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endrole

                    </div>
                </div>
            
                <div class="card-body">
                
                        <div class="row">
                            <div class="col-sm-3">
                                @foreach ($otherOutlet as $outlet)
                                <div class="row">
                                    <div class="col">
                                        <a 
                                            @if ($defaultOtherOutletId == $outlet->id)
                                                class="dropdown-item active" 
                                            @else
                                                class="dropdown-item" 
                                            @endif

                                            href="#" 
                                            wire:click="showOutletDetail({{ $outlet->id }})"
                                        >
                                            {{ $outlet->nama }}
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            <div class="col-sm-9">
                                <livewire:inter-outlet-detail :selectOutlet="$otherOutlet[0]->id" :outletId="$outletId"/>
                            </div>
                        </div>
                    
                </div>
            
                
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:load', function () {
        
        // const detailOutlet = Array.from(document.getElementsByClassName('detail-outlet'))

        // detailOutlet[0].style.backgroundColor = '#007BFF'
        // detailOutlet[0].style.color = 'white'

        // detailOutlet.forEach(outlet => {
        //     outlet.addEventListener('click', function(){

        //         detailOutlet.forEach(outlet => {
        //             outlet.style.backgroundColor = 'white'
        //             outlet.style.color = 'black'
        //         })

        //         outlet.style.backgroundColor = '#007BFF'
        //         outlet.style.color = 'white'
        //     })
        // })
    })
</script>