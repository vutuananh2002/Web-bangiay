@component('mail::message')
<h1>Confirm Your Change Email</h1>

<div class="">
    <h3>Hi, {{ $username }}</h3>
    <p>Please click the button below to verify your email address. This link will expire in 1 hour</p>
    <p>If you do not recognize this request, please ignore this email.</p>
</div>

@component('mail::button', ['url' => $url])
Verify Email
@endcomponent

Best Regards,<br>
Shin
@endcomponent
