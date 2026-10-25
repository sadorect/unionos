<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $appName }} invitation</title>
</head>
<body style="margin:0;padding:0;background-color:#f1f5f9;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#0f172a;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f1f5f9;padding:32px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:520px;background-color:#ffffff;border-radius:14px;overflow:hidden;border:1px solid #e2e8f0;">
                    <tr>
                        <td style="padding:28px 32px 8px;">
                            <p style="margin:0;font-size:14px;font-weight:600;letter-spacing:0.04em;text-transform:uppercase;color:#6366f1;">{{ $appName }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 32px;">
                            <h1 style="margin:8px 0 12px;font-size:22px;line-height:1.3;color:#0f172a;">
                                @if ($inviterName)
                                    {{ $inviterName }} invited you to their couple space
                                @else
                                    You've been invited to a couple space
                                @endif
                            </h1>
                            <p style="margin:0 0 20px;font-size:15px;line-height:1.6;color:#475569;">
                                {{ $appName }} is a private space for the two of you to check in, reflect, and stay connected. Accept the invitation below to join.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:4px 32px 24px;">
                            <a href="{{ $acceptUrl }}" style="display:inline-block;background-color:#6366f1;color:#ffffff;text-decoration:none;font-size:15px;font-weight:600;padding:13px 28px;border-radius:10px;">
                                Accept invitation
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 32px 8px;">
                            <p style="margin:0 0 6px;font-size:13px;line-height:1.6;color:#64748b;">
                                Or paste this link into your browser:
                            </p>
                            <p style="margin:0 0 20px;font-size:13px;line-height:1.6;word-break:break-all;">
                                <a href="{{ $acceptUrl }}" style="color:#6366f1;">{{ $acceptUrl }}</a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 32px 28px;border-top:1px solid #f1f5f9;">
                            <p style="margin:16px 0 0;font-size:12px;line-height:1.6;color:#94a3b8;">
                                This invitation is single-use and expires on {{ $expiresAt->format('M j, Y \a\t g:i A T') }}. If you weren't expecting it, you can safely ignore this email.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
