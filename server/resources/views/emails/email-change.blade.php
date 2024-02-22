@component('mail::message')
<h1>Email Change Request</h1>
<div class="">
    <h3>Hi, {{ $username }}</h3>
    <p>Your account email address has been requested to change to {{ $email }}</p>
    <p>If you do not recognize this request, please contact us immediately.</p>
</div>

Best Regards,<br>
Shin
@endcomponent
