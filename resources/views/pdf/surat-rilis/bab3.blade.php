<!-- BAB III - LINGKUP PEKERJAAN -->
<div class="section">
    <div class="title">BAB III</div>
    <div class="section-title">LINGKUP PEKERJAAN</div>

    @if($project->detail)
    <div class="mt-4">
        <h4 style="font-weight: bold; margin-bottom: 5px;">Project Description</h4>
        <p>{{ $project->detail->description }}</p>
    </div>

    <div class="mt-4">
        <h4 style="font-weight: bold; margin-bottom: 5px;">Project Scope</h4>
        <p>{{ $project->detail->scope }}</p>
    </div>

    @if($project->detail->risk_issue)
    <div class="mt-4">
        <h4 style="font-weight: bold; margin-bottom: 5px;">Risk & Issue</h4>
        <p>{{ $project->detail->risk_issue }}</p>
    </div>
    @endif

    @if($project->detail->deliverables)
    <div class="mt-4">
        <h4 style="font-weight: bold; margin-bottom: 5px;">Deliverables</h4>
        <p>{{ $project->detail->deliverables }}</p>
    </div>
    @endif

    @if($project->rkapItems->isNotEmpty())
    <div class="mt-4">
        <h4 style="font-weight: bold; margin-bottom: 10px;">RKAP Items</h4>
        <table class="content-table">
            <thead>
                <tr>
                    <th>Tahun</th>
                    <th>Kode Anggaran</th>
                    <th>Detail Rencana</th>
                    <th>Nilai RKAP</th>
                </tr>
            </thead>
            <tbody>
                @foreach($project->rkapItems as $item)
                <tr>
                    <td>{{ $item->tahun }}</td>
                    <td>{{ $item->kode_anggaran }}</td>
                    <td>{{ $item->detail_rencana }}</td>
                    <td>Rp {{ number_format($item->nilai_rkap, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" style="text-align: right;">Total</th>
                    <th>Rp {{ number_format($project->rkapItems->sum('nilai_rkap'), 2, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif


    @endif

    @if($project->schedules->isNotEmpty())
    <div class="mt-4">
        <h4 style="font-weight: bold; margin-bottom: 10px;">Schedule</h4>
        <table class="content-table">
            <thead>
                <tr>
                    <th>Tahapan</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($project->schedules as $schedule)
                <tr>
                    <td>{{ $schedule->phase }}</td>
                    <td>{{ date('d F Y', strtotime($schedule->start_date)) }}</td>
                    <td>{{ date('d F Y', strtotime($schedule->end_date)) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($project->budgets->isNotEmpty())
    <div class="mt-4">
        <h4 style="font-weight: bold; margin-bottom: 10px;">Budgeting</h4>
        <table class="content-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($project->budgets as $budget)
                <tr>
                    <td>{{ $budget->item }}</td>
                    <td>{{ $budget->description }}</td>
                    <td>Rp {{ number_format($budget->amount, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" style="text-align: right;">Total</th>
                    <th>Rp {{ number_format($project->budgets->sum('amount'), 2, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif

    @if($project->milestones->isNotEmpty())
    <div class="mt-4">
        <h4 style="font-weight: bold; margin-bottom: 10px;">Milestone</h4>
        <table class="content-table">
            <thead>
                <tr>
                    <th>Nama Milestone</th>
                    <th>Target Tanggal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($project->milestones as $milestone)
                <tr>
                    <td>{{ $milestone->name }}</td>
                    <td>{{ date('d F Y', strtotime($milestone->target_date)) }}</td>
                    <td>{{ ucfirst($milestone->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>