<!DOCTYPE html>
<html>
<body style="font-family: sans-serif; color: #132A13; padding: 24px; background: #f9f9f9;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; padding: 32px; border: 1px solid #e5e7eb;">
        <h2 style="color: #4F772D; margin-top: 0;">New Contact Form Submission</h2>
        <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
            <tr><td style="padding: 8px 0; font-weight: 600; width: 120px;">Name:</td><td>{{ $submission->name }}</td></tr>
            <tr><td style="padding: 8px 0; font-weight: 600;">Email:</td><td><a href="mailto:{{ $submission->email }}" style="color: #4F772D;">{{ $submission->email }}</a></td></tr>
            <tr><td style="padding: 8px 0; font-weight: 600;">Phone:</td><td>{{ $submission->phone ?? '—' }}</td></tr>
            <tr><td style="padding: 8px 0; font-weight: 600;">IP Address:</td><td>{{ $submission->ip_address ?? '—' }}</td></tr>
            <tr><td style="padding: 8px 0; font-weight: 600;">Submitted:</td><td>{{ $submission->created_at->format('d M Y H:i') }}</td></tr>
        </table>
        <hr style="margin: 20px 0; border: none; border-top: 1px solid #90A955;">
        <p style="font-weight: 600; margin-bottom: 8px;">Message:</p>
        <p style="white-space: pre-wrap; background: #f3f9ee; padding: 16px; border-radius: 8px; border-left: 3px solid #4F772D; font-size: 14px; line-height: 1.6;">{{ $submission->message }}</p>
    </div>
</body>
</html>
