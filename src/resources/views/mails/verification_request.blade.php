<!doctype html>
<html>
    <head>
        <meta name="viewport" content="width=device-width" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>{{ config('app.name') }} Notification</title>
        <style>
            body {
                font-family: Tahoma, Verdana, Segoe, sans-serif;
                font-size: 14px;
                background-color: #F5F8FA;
                padding: 20px;
            }
            @font-face {
                font-family: 'Usuzi';
                font-style: normal;
                font-weight: normal;
                src: url({{ storage_path('fonts\Usuzi.ttf') }}) format('truetype');
            }
        </style>
    </head>
    <body>
        <div style="width: 100%; background-color: #fff; padding: 15px;">

            <!--begin::Header-->
            <table role="presentation" cellpadding="0" cellspacing="0" style="width: 100%; background-color: #fff; border-bottom: 3px solid #000; padding: 0 15px;">
                <tr>
                    <td>
                        <span style="font-family: Usuzi; font-size: 45px; color: #ed1c24;">CRGMPC</span>
                    </td>
                    <td style="text-align: right; vertical-align: bottom;">
                        <p style="font-size: 12px; margin: 0;"><i><b>Life Is Nothing Without Rice</b></i></p>
                    </td>
                </tr>
            </table>
            <!--end::Header-->

            <!--begin::SubHeader-->
            <table role="presentation" cellpadding="0" cellspacing="0" style="width: 100%; background-color: #fff; padding: 15px;">
                <tr>
                    <td style="text-align: center; vertical-align: center;">
                        <p style="font-size: 20px; margin: 0;"><b>Verify your email address</b></p>
                    </td>
                </tr>
                
            </table>
            <!--end::SubHeader-->


            
            <!--begin::Main-->
            <table role="presentation" cellpadding="0" cellspacing="15" style="width: 100%; background-color: #fff; padding: 0 15px;">
                <tr>
                    <td style="text-align: center; padding: 20px 0 10px 0;">Please confirm that you want to use this as your CRGMPC Central account email address <b>(Request code: {{ $data->reference_id }})</b></td>
                </tr>
            </table>
            <!--end::Main-->

            <table role="presentation" cellpadding="0" cellspacing="15" style="width: 100%; background-color: #fff; padding: 0 15px;">
                <tr>
                    <td width="20%"></td>
                   
                    <td style="height: 40px; width: 100px; background-color: #00A65A; text-align: center; vertical-align: middle;">
                        <a href="{{ config('app.url') . ':3000/email/verify/' . $data->option->verify}}" style="text-decoration: none">
                            <b>Verify Email Address</b>
                        </a>
                    </td>
                    <td width="20%"></td>
                </tr>
            </table>
        </div>

        <!--begin::Footer-->
        <table role="presentation" cellpadding="0" cellspacing="15" style="width: 100%; padding: 5px;">
            <tr>
                <td style="width: 100%; color: #808080; font-size: 12px; text-align: center;">
                    {{ config('app.name') }}
                </td>
            </tr>
            <tr>
                <td style="width: 100%; color: #808080; font-size: 12px; text-align: center;">
                    This is a system generated message, please do not reply.
                </td>
            </tr>
        </table>
        <!--end::Footer-->
    </body>
</html>
