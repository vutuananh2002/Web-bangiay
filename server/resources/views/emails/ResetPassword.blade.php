@component('mail::message')
<h1>Reset your password</h1>
<div>
    <h3>Hello, {{ $username }}</h3>
    <p>
        We're sending you this email because you requested a password reset.
        Click on this button below to create a new password, this link will expire in 1 hour:
    </p>
</div>

@component('mail::button', ['url' => $url])
Set a new password
@endcomponent

<div>
    <p>
        If you didn't request a password reset, you can ignore this email.
        Your password will not be changed.
    </p>
</div>

Best Regards,<br>
Shin<br>

<small>
    If you're having trouble clicking the "Set a new password" button, 
    copy and paste the token below into the reset password form:
    {{ $token }}
</small>
@endcomponent
