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


            @if ($data->notification_type == 'Completed')
            <!--begin::SubHeader-->
            <table role="presentation" cellpadding="0" cellspacing="0" style="width: 100%; background-color: #fff; padding: 15px;">
                <tr>
                    <td style="text-align: center; vertical-align: center;">
                        <p style="font-size: 20px; margin: 0;"><b>Congratulations!</b></p>
                    </td>
                </tr>
            </table>
            <!--end::SubHeader-->
            
            <!--begin::Main-->
            <table role="presentation" cellpadding="0" cellspacing="15" style="width: 100%; background-color: #fff; padding: 0 15px;">
                <tr>
                    <td style="text-align: center; padding: 20px 0 10px 0;">You may now login to our CRGMPC Central using below credentials</td>
                </tr>
            </table>
            <!--end::Main-->

            <table role="presentation" cellpadding="0" cellspacing="15" style="width: 100%; background-color: #fff; border-bottom: 2px solid #BEBEBE;">
                <tr>
                    <td>
                        <table role="presentation" cellpadding="0" cellspacing="0" style="width: 100%; background-color: #fff;">
                            <tr>
                                <td style="width: 25%; padding: 3px;"><b>Username</b></td>
                                <td style="width: 4%; text-align: center; padding: 3px;">:</td>
                                <td style="padding: 3px;">{{ $data->username }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 3px;"><b>Password</b></td>
                                <td style="text-align: center; padding: 3px;">:</td>
                                <td style="padding: 3px;">{{ $data->password }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 3px;"><b>Link</b></td>
                                <td style="text-align: center; padding: 3px;">:</td>
                                <td style="padding: 3px;"> <a href="#"> Web site link </a> </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>



            @else

            <!--begin::SubHeader-->
            <table role="presentation" cellpadding="0" cellspacing="0" style="width: 100%; background-color: #fff; padding: 15px;">
                <tr>
                    <td style="text-align: center; padding: 20px 0 10px 0;">
                        The Request <b>{{ $data->reference_id }}</b> has been 

                        @if ($data->notification_type == 'Approved')

                            <span style="color: #00A65A">{{ Str::lower($data->notification_type) }}</span> 

                        @else

                            <span style="color: #DD4B39">{{ Str::lower($data->notification_type) }}</span> 

                        @endif

                    </td>
                </tr>

                @if ($data->notification_type == 'Rejected')

                    <tr>
                        <td style="text-align: center; padding: 20px 0 5px 0;"><b>Reason</b> : {{ $data->reason }}</td>
                    </tr>

                @endif

            </table>
            <!--end::SubHeader-->

            @endif
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
