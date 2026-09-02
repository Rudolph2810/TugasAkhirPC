<!-- BAB II - TUJUAN PROYEK -->
<div class="section">
    <div class="title">BAB II</div>
    <div class="section-title">TUJUAN PROYEK</div>

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
            <td class="label">Lokasi Proyek</td>
            <td>{{ $project->location }}</td>
        </tr>
    </table>

    <div class="mt-4">
        <h4 style="font-weight: bold; margin-bottom: 5px;">Dasar Pelaksanaan</h4>
        <p>Proyek ini dilaksanakan berdasarkan kesepakatan antara {{ $project->client }} dengan pihak kami, dengan
            memperhatikan seluruh ketentuan dan persyaratan yang telah disepakati bersama.</p>
    </div>

    @if($project->billingPlans->isNotEmpty())
    <div class="mt-4">
        <h4 style="font-weight: bold; margin-bottom: 10px;">Termin Pembayaran</h4>
        <table class="content-table">
            <thead>
                <tr>
                    <th>Termin</th>
                    <th>Persentase</th>
                    <th>Tanggal Rencana Tagih</th>
                    <th>Nominal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($project->billingPlans as $billing)
                <tr>
                    <td>{{ $billing->termin }}</td>
                    <td>{{ $billing->percentage }}%</td>
                    <td>{{ date('d F Y', strtotime($billing->planned_date)) }}</td>
                    <td>Rp {{ number_format($billing->amount, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" style="text-align: right;">Total</th>
                    <th>Rp {{ number_format($project->billingPlans->sum('amount'), 2, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif
</div>