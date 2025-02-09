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
                        <p style="font-size: 20px; margin: 0;"><b>System Access Request</b></p>
                    </td>
                </tr>
            </table>
            <!--end::SubHeader-->
            
            <!--begin::Main-->
            <table role="presentation" cellpadding="0" cellspacing="15" style="width: 100%; background-color: #fff; border-bottom: 2px solid #BEBEBE;">
                <tr>
                    @if(!is_null($data->author_employee_id))
                    <td>
                        <table role="presentation" cellpadding="0" cellspacing="0" style="width: 100%; background-color: #fff;">
                            <tr>
                                <td style="width: 25%; padding: 3px;"><b>Request Code: </b></td>
                                <td style="width: 4%; text-align: center; padding: 3px;">:</td>
                                <td style="padding: 3px;">{{ $data->reference_id }}</td>
                            </tr>
                            <tr>
                                <td style="width: 25%; padding: 3px;"><b>Deparment</b></td>
                                <td style="width: 4%; text-align: center; padding: 3px;">:</td>
                                <td style="padding: 3px;">{{ $data->author_department }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 3px;"><b>Section</b></td>
                                <td style="text-align: center; padding: 3px;">:</td>
                                <td style="padding: 3px;">{{ $data->author_section }}</td>
                            </tr>
                        </table>
                    </td>
                    @elseif(!is_null($data->author_dealer_id))
                    <td>
                        <table role="presentation" cellpadding="0" cellspacing="0" style="width: 100%; background-color: #fff;">
                            <tr>
                                <td style="width: 25%; padding: 3px;"><b>Request Code: </b></td>
                                <td style="width: 4%; text-align: center; padding: 3px;">:</td>
                                <td style="padding: 3px;">{{ $data->reference_id }}</td>
                            </tr>
                            <tr>
                                <td style="width: 25%; padding: 3px;"><b>Dealer</b></td>
                                <td style="width: 4%; text-align: center; padding: 3px;">:</td>
                                <td style="padding: 3px;">{{ $data->author_dealer }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 3px;"><b>Satellite</b></td>
                                <td style="text-align: center; padding: 3px;">:</td>
                                <td style="padding: 3px;">{{ $data->author_dealer_satellite }}</td>
                            </tr>
                        </table>
                    </td>
                    @else
                    <td>
                        <table role="presentation" cellpadding="0" cellspacing="0" style="width: 100%; background-color: #fff;">
                            <tr>
                                <td style="width: 25%; padding: 3px;"><b>Organization</b></td>
                                <td style="width: 4%; text-align: center; padding: 3px;">:</td>
                                <td style="padding: 3px;">{{ $data->organization }}</td>
                            </tr>
                        </table>
                    </td>
                    @endif

                    <td style="text-align: center; padding: 20px 0 10px 0;">
                        <table role="presentation" cellpadding="0" cellspacing="0" style="width: 100%; background-color: #fff;">
                            <tr>
                                <td style="width: 25%; padding: 3px;"><b>User fullname</b></td>
                                <td style="width: 4%; text-align: center; padding: 3px;">:</td>
                                <td style="padding: 3px;">{{ Str::title($data->user) }}</td>
                            </tr>
                            <tr>
                                <td style="width: 25%; padding: 3px;"><b>Created By</b></td>
                                <td style="width: 4%; text-align: center; padding: 3px;">:</td>
                                <td style="padding: 3px;">{{ Str::title($data->author) }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 3px;"><b>Created Date</b></td>
                                <td style="text-align: center; padding: 3px;">:</td>
                                <td style="padding: 3px;">{{ $data->created_date }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td>
                        <table role="presentation" cellpadding="0" border="1" cellspacing="0" style="width: 100%; background-color: #fff;">
                            <thead>
                                <tr>
                                    <th style="padding: 3px;">System</th>
                                    <th style="padding: 3px;">Access</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data->accesses as $access)
                                <tr>
                                    <td style="padding: 3px;">{{ $access->system }}</td>
                                    <td style="padding: 3px;">{{ $access->user_type }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
            
            <table role="presentation" cellpadding="0" cellspacing="15" style="width: 100%; background-color: #fff; padding: 0 15px;">
                <tr>
                    <!-- <td style="height: 40px; background-color: #1b84ff; text-align: center; vertical-align: middle;">
                        <a href="{{ config('app.url') . ':3000/approvals/confirm/' . $data->option->confirm }}" style="width: auto; color: #fff; text-decoration: none;">
                            <b>Manage</b>
                        </a>
                    </td> -->
                    @if(count($data->accesses) > 1)
                    <td style="height: 40px; background-color: #00A65A; text-align: center; vertical-align: middle;">
                        <a href="{{ config('app.url') . ':3000/approvals/confirm/' . $data->option->confirm }}" style="width: auto; color: #fff; text-decoration: none;">
                            <b>Approve All</b>
                        </a>
                    </td>
                    @else
                    <td style="height: 40px; background-color: #00A65A; text-align: center; vertical-align: middle;">
                        <a href="{{ config('app.url') . ':3000/approvals/confirm/' . $data->option->confirm }}" style="width: auto; color: #fff; text-decoration: none;">
                            <b>Approve</b>
                        </a>
                    </td>
                    @endif
                    <td style="height: 40px; background-color: #DD4B39; text-align: center; vertical-align: middle;">
                        <a href="{{ config('app.url') . ':3000/approvals/reject/' . $data->option->reject }}" style="width: auto; color: #fff; text-decoration: none;">
                            <b>Reject</b>
                        </a>
                    </td>
                    <!-- <td style="height: 40px; background-color: #4f9fd8; text-align: center; vertical-align: middle;">
                        <a href="" style="width: auto; color: #fff; text-decoration: none;">
                            <b>For Approval</b>
                        </a>
                    </td> -->
                </tr>
            </table>

            <table role="presentation" cellpadding="0" cellspacing="15" style="width: 100%; background-color: #fff; padding: 5px;">
                <tr>
                    <td style="width: 100%; color: #000000; font-size: 12px; text-align: center;">
                        Note : If you want to customized your approval please login to the system.
                    </td>
                </tr>
                <tr>
                    <td style="width: 100%; color: #AA4A44; font-size: 12px; text-align: center;">
                        This email is exclusively for you (Approver) and should not be forwarded to anyone else. The content of this email is confidential and intended solely for your use.
                    </td>
                </tr>
                <tr>
                    <td style="width: 100%; text-align: center;">
                        You can visit <a href="#" style="color: #000; text-decoration: none;">{{ config('app.name') }}</a> for more information.
                    </td>
                </tr>
            </table>
            <!--end::Main-->
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
