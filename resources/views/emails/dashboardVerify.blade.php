<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name='viewport' content='width=device-width, initial-scale=1'>

        <title>Field Jobs Tracker</title>
        <style>

            table {
                border: solid thin #d4d0d0;
                background-color: #FFFFFF;
                max-width: 700px;
                width: 100%;
                font-size: 16px;
                text-align: center;
                border-collapse: collapse;
                margin: 100px auto;
            }
            th {
                border-bottom: solid thin #d4d0d0;
                padding: 20px;
            }
            #header {
                background-color: #213B50;
                color: #FFFFFF;
                font-size: 20px;
                padding: 15px 0;
            }
            #welcome-user {
                color: #213B50;

            }
            span {
                color: #6A626A;
                font-weight: bold;
                font-size: 18px;
            }
            #anchorTh {
                border-bottom: none;
            }

            a {
                color: #40729C;
            }

        </style>
        </head>
        <body>
            <table>
               <tr>
                  <th id="header">{{ $website_name }}</th>
               </tr>
               <tr>
                  <th id="welcome-user">Hello {{ $name }}, this is  an email to verify the dashboard account.</th>
               </tr>

               <tr>
                  <th id="message">
                     Please click on the link below and this will verify your email address on the dashboard.
                   </th>
               </tr>
               <tr>
                  <th id="anchorTh"><a href="{{ route('getAdminVerify', ['email' => $email, 'verifyToken' => $verifyToken]) }}"> Create Account </a></th>
               </tr>
            </table>

    </body>
 </html>
