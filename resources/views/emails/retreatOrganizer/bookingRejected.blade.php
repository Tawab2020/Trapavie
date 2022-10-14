<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Booking Rejected on Tripavie</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font -->
    <link href="http://fonts.cdnfonts.com/css/gilroy-bold?styles=20876,20877,20878,20879,20880" rel="stylesheet">
</head>

<body style="font-family: 'Gilroy-Regular', sans-serif; font-size: 15px; font-weight: 400;">
    <style>
        .font-bold {
            font-family: 'Gilroy-Bold', sans-serif;
        }

        .font-medium {
            font-family: 'Gilroy-Medium', sans-serif;
        }

        .font-light {
            font-family: 'Gilroy-Light', sans-serif;

        }
    </style>

    <!-- Hero Start -->
    <div style="margin-top: 50px;">
        <table cellpadding="0" cellspacing="0"
            style="font-family: 'Gilroy-Regular', sans-serif; font-size: 15px; font-weight: 400; max-width: 600px; border: none; margin: 0 auto; border-radius: 6px; overflow: hidden; background-color: #fff; box-shadow: 0 0 3px rgba(60, 72, 88, 0.15);">
            <thead>
                <tr
                    style="background-color: #f6f6f6; padding: 3px 0; line-height: 68px; text-align: center; color: #fff; font-size: 24px; font-weight: 700; letter-spacing: 1px;">
                    <th scope="col">
                        <img src="{{ asset('assets/admin/images/logo.png') }}" height="50"
                            style="vertical-align: middle;" alt="Tripavie Logo">
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td style="padding: 0 24px 15px;">
                        <h2 class="font-bold" style="margin-bottom: 0;">Hi {{ $retreatOrganizer->fname }},</h2>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0 24px 15px;">
                        <p>We are sorry, but the booking you requested for {{ $booking['venue_log']['name'] }} was
                            rejected by the
                            venue host.</p>
                        <p>You can contact the venue host and ask for more information regarding this action.</p>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 15px 24px;">
                        <a href="http://127.0.0.1:8000/venues/{{ $booking['venue_id'] }}" class="font-medium"
                            style="padding: 8px 20px; outline: none; text-decoration: none; font-size: 16px; letter-spacing: 0.5px; transition: all 0.3s; border-radius: 6px; background-color: #2eca8b; border: 1px solid #07cc7d; color: #ffffff;">Contact
                            Host</a>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 15px 24px 0;">
                        Thank you for choosing Tripavie.
                    </td>
                </tr>

                <tr>
                    <td style="padding: 15px 24px 15px;">
                        Tripavie Support, <br>
                        Tripavie.
                    </td>
                </tr>

                <tr>
                    <td style="padding: 16px 8px; background-color: #f8f9fc; text-align: center;">
                        ©
                        {{ now()->year }},
                        Tripavie.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- Hero End -->
</body>

</html>
