<!-- RIWAYAT PERSETUJUAN -->
<div class="section">
    <div class="title">RIWAYAT PERSETUJUAN</div>

    <p class="mb-4">Berikut adalah riwayat persetujuan untuk Project Charter {{ $project->code }} -
        {{ $project->title }}:</p>

    <table class="approval-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Approver</th>
                <th>Role / Level</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($approvals as $index => $approval)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $approval->approver->name }}</td>
                <td>
                    {{ ucfirst($approval->role) }}
                    @if($approval->level)
                    - {{ ucfirst(str_replace('_', ' ', $approval->level)) }}
                    @endif
                </td>
                <td>
                    @if($approval->action === 'approve')
                    <span class="badge-approved">✓ Disetujui</span>
                    @elseif($approval->action === 'cancel')
                    <span class="badge-cancelled">✗ Dibatalkan</span>
                    @else
                    <span class="badge-revised">⟳ Revisi</span>
                    @endif
                </td>
                <td>{{ date('d F Y H:i', strtotime($approval->created_at)) }}</td>
                <td>{{ $approval->notes ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature-area">
        <div class="signature-box">
            <p class="label">Disetujui oleh,</p>
            <br><br><br>
            <div class="signature-line">
                <p><strong>{{ $approvals->last()?->approver->name ?? 'Direksi' }}</strong></p>
                <p style="font-size: 10pt; color: #718096;">Jabatan: Direksi</p>
                <p style="font-size: 10pt; color: #718096;">Tanggal:
                    {{ date('d F Y', strtotime($project->released_at ?? now())) }}</p>
            </div>
        </div>
        <div class="signature-box">
            <p class="label">Mengetahui,</p>
            <br><br><br>
            <div class="signature-line">
                <p><strong>{{ $project->creator->name }}</strong></p>
                <p style="font-size: 10pt; color: #718096;">Jabatan: Inisiator Proyek</p>
                <p style="font-size: 10pt; color: #718096;">Tanggal: {{ date('d F Y') }}</p>
            </div>
        </div>
    </div>
</div>