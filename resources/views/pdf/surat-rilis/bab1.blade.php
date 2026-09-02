<!-- BAB I - TINJAUAN PROYEK -->
<div class="section">
    <div class="title">BAB I</div>
    <div class="section-title">TINJAUAN PROYEK</div>

    <table class="content-table">
        <tr>
            <td class="label">Nama Proyek</td>
            <td>{{ $project->title }}</td>
        </tr>
        <tr>
            <td class="label">Kode Proyek</td>
            <td>{{ $project->code }}</td>
        </tr>
        <tr>
            <td class="label">Client</td>
            <td>{{ $project->client }}</td>
        </tr>
        <tr>
            <td class="label">Lokasi Proyek</td>
            <td>{{ $project->location }}</td>
        </tr>
        <tr>
            <td class="label">Segmen Bisnis</td>
            <td>{{ $project->businessSegment->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Nilai Kontrak</td>
            <td>Rp {{ number_format($project->contract_value, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Periode Pelaksanaan</td>
            <td>{{ date('d F Y', strtotime($project->start_date)) }} -
                {{ date('d F Y', strtotime($project->end_date)) }}</td>
        </tr>
    </table>

    @if($project->detail)
    <div class="mt-4">
        <h4 style="font-weight: bold; margin-bottom: 5px;">Deskripsi Proyek</h4>
        <p>{{ $project->detail->description }}</p>
    </div>

    <div class="mt-4">
        <h4 style="font-weight: bold; margin-bottom: 5px;">Latar Belakang</h4>
        <p>{{ Str::limit($project->detail->description, 300, '...') }}</p>
    </div>
    @endif
</div>