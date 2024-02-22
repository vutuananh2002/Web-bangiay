@component('mail::message')
<h1>Verify This Email Address</h1>
<div>
    <h3>Hi, {{ $username }}</h3>
    <p>Please click the button below to verify your email address.</p>
    <p>If you did not sign up to our service, please ignore this email.</p>
</div>

@component('mail::button', ['url' => $url])
Verify Email
@endcomponent

Best Regards,<br>
Shin
@endcomponent
