<div class="col-md-12">
    <div class="table-responsive">
        <table id="dataTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre completo</th>
                    <th>CI</th>
                    <th>Fecha nac.</th>
                    <th>Telefono</th>
                    @if (auth()->user()->hasRole('admin'))
                        <th style="text-align: center">Gimnacio</th>
                    @endif                    
                    <th style="text-align: right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>
                        <table>
                            @php
                                $image = asset('images/default.jpg');
                                if($item->photo){
                                    $image = asset('storage/'.str_replace('.', '-cropped.', $item->photo));
                                }
                                $now = \Carbon\Carbon::now();
                                $birthdate = new \Carbon\Carbon($item->birthdate);
                                $age = $birthdate->diffInYears($now);
                            @endphp
                            {{-- <tr>
                                <td> --}}
                                    <img src="{{ $image }}" alt="{{ $item->first_name }} {{ $item->last_name }}" style="width: 60px; height: 60px; border-radius: 30px; margin-right: 10px">
                                {{-- </td>
                                <td> --}}
                                    {{ $item->first_name }} {{ $item->last_name }}
                                {{-- </td>
                            </tr> --}}
                        </table>
                    </td>
                    <td>{{ $item->ci }}</td>
                    <td>{{ date('d/m/Y', strtotime($item->birthdate)) }} <br> <small>{{ $age }} años</small> </td>
                    <td>{{ $item->phone }}</td>
                    @if (auth()->user()->hasRole('admin'))
                        <td style="text-align: center">{{$item->busine->name}}</td>
                    @endif
                    <td class="no-sort no-click bread-actions text-right">
                        @if (auth()->user()->hasPermission('read_people'))
                            <a href="{{ route('voyager.people.show', ['id' => $item->id]) }}" title="Ver" class="btn btn-sm btn-warning view">
                                <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                            </a>
                        @endif
                        @if (auth()->user()->hasPermission('edit_people'))
                            <a href="{{ route('voyager.people.edit', ['id' => $item->id]) }}" title="Editar" class="btn btn-sm btn-primary edit">
                                <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                            </a>
                        @endif
                        {{-- @if (auth()->user()->hasPermission('delete_people'))
                            <button title="Borrar" class="btn btn-sm btn-danger delete" onclick="deleteItem('{{ route('contracts.destroy', ['contract' => $item->id]) }}')" data-toggle="modal" data-target="#delete-modal">
                                <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                            </button>
                        @endif --}}
                    </td>
                </tr>
                @empty
                    <tr class="odd">
                        <td valign="top" colspan="6" class="dataTables_empty">No hay datos disponibles en la tabla</td>
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

        // $('.btn-rotation').click(function(e){
        //     e.preventDefault();
        //     let url = $(this).data('url');
        //     $('#rotation-form').attr('action', url);
        // });

        // $('.btn-irremovability').click(function(e){
        //     e.preventDefault();
        //     let url = $(this).data('url');
        //     $('#irremovability-form').attr('action', url);
        // });

    });
</script>