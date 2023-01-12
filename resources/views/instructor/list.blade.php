<div class="col-md-12">
    <div class="table-responsive">
        <table id="dataTableStyle" class="table table-hover">
            <thead>
                <tr>
                    <th style="text-align: center">Id</th>
                    <th style="text-align: center">Instructor</th>
                    <th style="text-align: center">Descripción</th>
                    <th style="text-align: center">Estado</th>                                        
                    <th style="text-align: center">Registrado Por</th>                                   
                    {{-- <th style="text-align: right">Acciones</th> --}}
                </tr>
            </thead>
            @php
                // dd($cashier);
            @endphp
            <tbody>
                @forelse ($data as $item)
                    <tr>
                        
                        <td style="text-align: center">{{ $item->id }}</td>                                            
                        <td style="text-align: center">{{ $item->people->first_name }} {{ $item->people->last_name }}</td>                                            
                        <td style="text-align: center">{{ $item->description }}</td>    
                        <td style="text-align: center">
                            @if ($item->status)
                                <label class="label label-success">Activo</label>
                            @else
                                <label class="label label-warning">Inactivo</label>
                            @endif
                        </td>
                        <td style="text-align: center">
                            <p>Registrado Por: {{$item->user->name}}</p>
                            {{date('d/m/Y H:i:s', strtotime($item->created_at))}}<br><small>{{\Carbon\Carbon::parse($item->created_at)->diffForHumans()}}.</small>
                        </td>
                        {{-- <td style="text-align: right">
                            <button title="Borrar" class="btn btn-sm btn-danger delete" onclick="deleteItem('{{ route('clients.destroy', ['client' => $item->id]) }}')" data-toggle="modal" data-target="#delete-modal">
                                <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                            </button>      
                        </td> --}}
                        
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center">Lista Vacia</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="col-md-12">
    <div class="col-md-4" style="overflow-x:auto">
        @if(count($data)>0)
            <p class="text-muted">Mostrando del {{$data->firstItem()}} al {{$data->lastItem()}} de {{$data->total()}} registros.</p>
        @endif
    </div>
    <div class="col-md-8" style="overflow-x:auto">
        <nav class="text-right">
            {{ $data->links() }}
        </nav>
    </div>
</div>

<script>
   
   var page = "{{ request('page') }}";
    $(document).ready(function(){
        $('.page-link').click(function(e){
            e.preventDefault();
            let link = $(this).attr('href');
            if(link){
                page = link.split('=')[1];
                list(page);
            }
        });
    });
</script>