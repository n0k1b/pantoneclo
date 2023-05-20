<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <title></title>
    <!--[if mso]>
  <noscript>
    <xml>
      <o:OfficeDocumentSettings>
        <o:PixelsPerInch>96</o:PixelsPerInch>
      </o:OfficeDocumentSettings>
    </xml>
  </noscript>
  <![endif]-->
    <style>
        table,
        td,
        div,
        h1,
        p {
            font-family: Arial, sans-serif;
        }
    </style>
</head>

<body style="margin:0;padding:0;">
    <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:{{$data['mail_layout_background_theme_color']}};">
        <tr>
            <td align="center" style="padding:0;">
                <table role="presentation"
                    style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">

                    <tr>
                        <td align="center" style="padding:40px 0 30px 0;background:{{$data['mail_header_theme_color']}}">
                            <img src="http://cartproshop.com/demo/public/images/storefront/logo/0dhZQlZtZI.png" alt="" style="height:40px; width:150px; display:block;" />
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:36px 30px 42px 30px;background:{{$data['mail_body_theme_color']}}">
                            <table role="presentation"
                                style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                <tr>
                                    <td style="padding:0 0 36px 0;color:#153643;">
                                             <p>Dear {{$data['fullname']}} , <br>
                                                {{$data['message']}} : <b>{{$data['reference_no']}}.</b> We attached an invoice with this mail also. Please check.
                                            </p>

                                            <br>

                                            <p>Thanks, <br>
                                                <span>{{ env('MAIL_FROM_NAME') }}</span>
                                            </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:30px;background:{{$data['mail_footer_theme_color']}}">
                        {{-- <td style="padding:30px;background:#00A884"> --}}
                            <table role="presentation"
                                style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;">
                                <tr>
                                    <td style="padding:0;width:50%;" align="left">
                                        <p
                                            style="margin:0;font-size:14px;line-height:16px;font-family:Arial,sans-serif;color:#ffffff;">
                                            &reg; {{$data['store_name']}} {{ date('Y') }}<br />
                                        </p>
                                    </td>
                                    <td style="padding:0;width:50%;" align="right">
                                        <table role="presentation" style="border-collapse:collapse;border:0;border-spacing:0;">
                                            <tr>
                                                <td style="padding:0 0 0 10px;width:38px;">
                                                    <a href="{{$data['storefront_facebook_link']}}" style="color:#ffffff;font-size:10px">Facebook</a>
                                                </td>
                                                <td style="padding:0 0 0 10px;width:38px;">
                                                    <a href="{{$data['storefront_twitter_link']}}" style="color:#ffffff;font-size:10px">Twitter</a>
                                                    </a>
                                                </td>
                                                <td style="padding:0 0 0 10px;width:38px;">
                                                    <a href="{{$data['storefront_instagram_link']}}" style="color:#ffffff;font-size:10px">Instagram</a>
                                                    </a>
                                                </td>
                                                <td style="padding:0 0 0 10px;width:38px;">
                                                    <a href="{{$data['storefront_youtube_link']}}" style="color:#ffffff;font-size:10px">Youtube</a>
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
