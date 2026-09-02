<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Surat Rilis Project Charter - {{ $project->code }}</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Times New Roman', serif;
        font-size: 12pt;
        line-height: 1.6;
        color: #1a202c;
        padding: 20px;
    }

    .header {
        text-align: center;
        border-bottom: 3px double #2d3748;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }

    .header .logo {
        max-height: 80px;
        margin-bottom: 10px;
    }

    .header h1 {
        font-size: 18pt;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 5px;
    }

    .header .subtitle {
        font-size: 12pt;
        font-weight: normal;
    }

    .title {
        text-align: center;
        font-size: 16pt;
        font-weight: bold;
        text-transform: uppercase;
        margin: 30px 0 20px;
        padding: 10px;
        border: 2px solid #2d3748;
    }

    .section {
        margin-bottom: 30px;
        page-break-inside: avoid;
    }

    .section-title {
        font-size: 14pt;
        font-weight: bold;
        color: #2d3748;
        border-bottom: 2px solid #2d3748;
        padding-bottom: 5px;
        margin-bottom: 15px;
    }

    .content-table {
        width: 100%;
        border-collapse: collapse;
        margin: 10px 0;
    }

    .content-table td,
    .content-table th {
        padding: 8px 12px;
        border: 1px solid #cbd5e0;
    }

    .content-table th {
        background-color: #f7fafc;
        font-weight: bold;
        text-align: left;
    }

    .content-table .label {
        font-weight: bold;
        width: 30%;
        background-color: #f7fafc;
    }

    .approval-table {
        width: 100%;
        border-collapse: collapse;
        margin: 10px 0;
        font-size: 11pt;
    }

    .approval-table th {
        background-color: #2d3748;
        color: white;
        padding: 10px 12px;
        text-align: left;
    }

    .approval-table td {
        padding: 8px 12px;
        border-bottom: 1px solid #e2e8f0;
    }

    .badge-approved {
        color: #38a169;
        font-weight: bold;
    }

    .badge-cancelled {
        color: #e53e3e;
        font-weight: bold;
    }

    .badge-revised {
        color: #d69e2e;
        font-weight: bold;
    }

    .page-break {
        page-break-before: always;
    }

    .footer {
        margin-top: 40px;
        padding-top: 20px;
        border-top: 2px solid #e2e8f0;
        text-align: center;
        font-size: 10pt;
        color: #718096;
    }

    .signature-area {
        margin-top: 40px;
        display: flex;
        justify-content: space-between;
    }

    .signature-box {
        width: 45%;
    }

    .signature-box .label {
        font-weight: bold;
        margin-bottom: 5px;
    }

    .signature-line {
        margin-top: 60px;
        border-top: 1px solid #2d3748;
        padding-top: 5px;
    }

    .highlight {
        background-color: #fefcbf;
        padding: 2px 5px;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .mt-4 {
        margin-top: 20px;
    }

    .mb-4 {
        margin-bottom: 20px;
    }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        @if($logo)
        <img src="{{ public_path($logo) }}" alt="Logo Perusahaan" class="logo">
        @endif
        <h1>Surat Rilis Project Charter</h1>
        <div class="subtitle">Nomor: {{ $project->code }} / PC / {{ date('Y') }}</div>
        <div class="subtitle">Tanggal: {{ date('d F Y', strtotime($project->released_at ?? now())) }}</div>
    </div>

    <!-- Main Content -->
    @include('pdf.surat-rilis.bab1', ['project' => $project])

    <div class="page-break"></div>
    @include('pdf.surat-rilis.bab2', ['project' => $project])

    <div class="page-break"></div>
    @include('pdf.surat-rilis.bab3', ['project' => $project])

    <div class="page-break"></div>
    @include('pdf.surat-rilis.riwayat', ['project' => $project, 'approvals' => $approvals])

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini adalah dokumen resmi yang diterbitkan oleh sistem</p>
        <p>Project Charter Management System v1.0</p>
    </div>
</body>

</html>