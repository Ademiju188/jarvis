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
            color: #333;
            line-height: 1.3;
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
            width: 80px;
            height: 40px;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .title-section {
            margin-bottom: 15px;
        }

        .school-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .document-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .consultant-info {
            font-size: 11px;
            margin-bottom: 3px;
        }

        .date-info {
            font-size: 11px;
        }

        .confidentiality {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin: 20px 0;
            padding: 8px;
            border: 2px solid #333;
        }

        .form-container {
            margin-bottom: 20px;
        }

        .form-row {
            display: flex;
            border-bottom: 1px solid #333;
            min-height: 25px;
        }

        .form-row:last-child {
            border-bottom: none;
        }

        .form-label {
            width: 40%;
            padding: 6px;
            font-weight: bold;
            background: #f8f9fa;
            border-right: 1px solid #333;
            display: flex;
            align-items: center;
            font-size: 11px;
        }

        .form-input {
            width: 60%;
            padding: 6px;
            border: none;
            outline: none;
            min-height: 15px;
            font-size: 11px;
        }

        .form-input.large {
            min-height: 40px;
            vertical-align: top;
        }

        .section-header {
            background: #0f766e;
            color: white;
            padding: 8px;
            font-weight: bold;
            text-align: center;
            width: 100%;
            margin: 0;
            font-size: 12px;
        }

        .footer {
            position: fixed;
            bottom: 0.75in;
            left: 0.75in;
            right: 0.75in;
            background: #f8f9fa;
            padding: 8px;
            font-size: 10px;
            color: #666;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-left {
            font-weight: bold;
        }

        .footer-center {
            text-align: center;
        }

        .footer-right {
            text-align: right;
        }

        .page-number {
            margin-left: 10px;
        }

        .content-wrapper {
            margin-bottom: 60px; /* Space for footer */
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
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

        <div class="confidentiality">
            Private & Confidential
        </div>

        <div class="form-container">
            <div class="form-row">
                <div class="form-label">Headteacher</div>
                <div class="form-input">{{ $visit->school->headteacher_name }}</div>
            </div>

            <div class="form-row">
                <div class="form-label">Focus of the visit</div>
                <div class="form-input">{{ $visit->context }}</div>
            </div>

            <div class="form-row">
                <div class="form-label">Context of the visit and activities undertaken</div>
                <div class="form-input large">{{ $visit->activities_undertaken }}</div>
            </div>

            <div class="form-row">
                <div class="form-label">Progress towards actions from last visit (if applicable)</div>
                <div class="form-input large">{{ $visit->key_findings }}</div>
            </div>

            <div class="form-row">
                <div class="section-header">Summary of Main Points</div>
            </div>

            <div class="form-row">
                <div class="form-label"></div>
                <div class="form-input large">{{ $visit->key_findings }}</div>
            </div>

            <div class="form-row">
                <div class="section-header">Next Steps</div>
            </div>

            <div class="form-row">
                <div class="form-label"></div>
                <div class="form-input large">{{ $visit->recommendations }}</div>
            </div>

            <div class="form-row">
                <div class="form-label">Date of next visit</div>
                <div class="form-input">
                    @if($visit->next_visit_date)
                        {{ \Carbon\Carbon::parse($visit->next_visit_date)->format('d/m/Y') }}
                    @else
                        TBC
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="footer-left">Jarvis Education Ltd |</div>
        <div class="footer-center">www.jarviseducation.co.uk</div>
        <div class="footer-right">hello@jarviseducation.co.uk<span class="page-number">1</span></div>
    </div>
</body>
</html>
