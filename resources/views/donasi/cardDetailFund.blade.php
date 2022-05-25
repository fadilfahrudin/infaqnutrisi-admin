<div class="card border-info">
    <div class="card-body">
        <h5 class="card-title">
            @foreach ($programs->unique('refcode') as $p)
            Detail {{ $p->fundraiser->name }}
            @endforeach
        </h5>
        <table id="datatable" class="table table-bordered table-sm table-hover table-striped" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Program</th>
                    <th>Total Donatur</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($programs as $p)
                    <tr>
                        <td>{{ $no }}</td>
                        <td>{{ $p->program->name }}</td>
                        <td>{{ $p['total_donatur'] }}</td>
                    </tr>
                @php $no++; @endphp
                @endforeach
            </tbody>
        </table>
    </div>
</div>