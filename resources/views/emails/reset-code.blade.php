<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; background-color: #0f0f0f; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #0f0f0f; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 420px; background-color: #1a1a1a; border-radius: 20px; overflow: hidden;">
                    
                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding: 40px 30px 20px;">
                            <img src="https://sona.fernandovasquez.tech/assets/sonaLogoAnimated.svg" alt="Sona" width="60" height="60" style="border-radius: 14px;">
                        </td>
                    </tr>

                    <!-- Title -->
                    <tr>
                        <td align="center" style="padding: 0 30px 10px;">
                            <h1 style="margin: 0; font-size: 22px; font-weight: 600; color: #ffffff;">
                                Password Reset
                            </h1>
                        </td>
                    </tr>

                    <!-- Description -->
                    <tr>
                        <td align="center" style="padding: 0 30px 30px;">
                            <p style="margin: 0; font-size: 14px; color: rgba(255,255,255,0.55); line-height: 1.6;">
                                Use the following code to reset your password. This code expires in 15 minutes.
                            </p>
                        </td>
                    </tr>

                    <!-- Code -->
                    <tr>
                        <td align="center" style="padding: 0 30px 30px;">
                            <div style="background-color: #252525; border-radius: 14px; padding: 20px 30px; display: inline-block;">
                                <span style="font-size: 32px; font-weight: 700; letter-spacing: 8px; color: #ffffff; font-family: monospace;">
                                    {{ $code }}
                                </span>
                            </div>
                        </td>
                    </tr>

                    <!-- Warning -->
                    <tr>
                        <td align="center" style="padding: 0 30px 30px;">
                            <p style="margin: 0; font-size: 12px; color: rgba(255,255,255,0.35); line-height: 1.5;">
                                If you didn't request this code, you can safely ignore this email. Someone may have entered your email address by mistake.
                            </p>
                        </td>
                    </tr>

                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 30px;">
                            <div style="height: 1px; background-color: rgba(255,255,255,0.08);"></div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="padding: 20px 30px 30px;">
                            <p style="margin: 0; font-size: 11px; color: rgba(255,255,255,0.25);">
                                Sona — Vinyl Music Player
                            </p>
                            <p style="margin: 4px 0 0; font-size: 11px; color: rgba(255,255,255,0.25);">
                                Developed by Devfer
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>