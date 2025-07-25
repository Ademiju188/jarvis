<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Improvement Visit</title>
    <style>
        @page {
            margin: 0.75in;
            size: A4;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #222;
            font-size: 12px;
        }
        .header {
            position: relative;
            margin-bottom: 20px;
        }
        .logo {
            position: absolute;
            top: 0;
            right: 0;
            width: 90px;
            height: 45px;
        }
        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .title-section {
            margin-bottom: 10px;
        }
        .school-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .document-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .consultant-info {
            font-size: 12px;
            margin-bottom: 2px;
        }
        .date-info {
            font-size: 12px;
        }
        .confidentiality {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin: 18px 0 10px 0;
            padding: 6px;
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .main-table th, .main-table td {
            border: 1px solid #222;
            padding: 7px 6px;
            vertical-align: top;
            font-size: 12px;
        }
        .main-table th {
            background: #f8f9fa;
            font-weight: bold;
            width: 32%;
        }
        .main-table .section-header {
            background: #00a6c7;
            color: #fff;
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            padding: 6px 0;
        }
        .footer {
            background: #f8f9fa;
            padding: 8px 0;
            font-size: 10px;
            color: #666;
            width: 100%;
            margin-top: 30px;
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
        }
        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }
        .footer-table td {
            padding: 0;
        }
        .footer-left { text-align: left; font-weight: bold; width: 33%; }
        .footer-center { text-align: center; width: 34%; }
        .footer-right { text-align: right; width: 33%; }
        .page-number { margin-left: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="{{ public_path('logo.png') }}" alt="Jarvis Education Logo">
        </div>
        <div class="title-section">
            <div class="school-name">{{ $visit->school->name }}</div>
            <div class="document-title">School Improvement Visit</div>
            <div class="consultant-info">Consultant: {{ $visit->consultant_name }}</div>
            <div class="date-info">Date: {{ \Carbon\Carbon::parse($visit->visit_date)->format('d/m/Y') }}</div>
        </div>
    </div>
    <div class="confidentiality">Private & Confidential</div>
    <table class="main-table">
        <tr>
            <th style="text-align: left;">Headteacher</th>
            <td>{{ $visit->school->headteacher->name }}</td>
        </tr>
        <tr>
            <th style="text-align: left;">Focus of the visit</th>
            <td>{!! $visit->context !!}</td>
        </tr>
        <tr>
            <th style="text-align: left;">Context of the visit and activities undertaken</th>
            <td>{!! $visit->activities_undertaken !!}</td>
        </tr>
        <tr>
            <th style="text-align: left;">Progress towards actions from last visit (if applicable)</th>
            <td>{!! $visit->progress ?? '' !!}</td>
        </tr>
        <tr>
            <td colspan="2" class="section-header">Summary of Main Points</td>
        </tr>
        <tr>
            <td colspan="2">{!! $visit->key_findings !!}</td>
        </tr>
        <tr>
            <td colspan="2" class="section-header">Next Steps</td>
        </tr>
        <tr>
            <td colspan="2">{!! $visit->recommendations !!}</td>
        </tr>
        <tr>
            <th style="text-align: left;">Date of next visit</th>
            <td>
                @if($visit->next_visit_date)
                    {{ \Carbon\Carbon::parse($visit->next_visit_date)->format('d/m/Y') }}
                @else
                    TBC
                @endif
            </td>
        </tr>
    </table>
    <div class="footer">
        <table class="footer-table">
            <tr>
                <td class="footer-left">Jarvis Education Ltd |</td>
                <td class="footer-center">www.jarviseducation.co.uk</td>
                <td class="footer-right">hello@jarviseducation.co.uk</td>
            </tr>
        </table>
    </div>
</body>
</html>
