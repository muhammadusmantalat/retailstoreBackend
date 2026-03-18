@component('mail::message')

<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('public/admin/assets/images/logo.png') }}" alt="Logo" style="max-width: 100px;">
</div>

<p>We have received a request to reset your password. Here is your OTP code:</p>

<div style="text-align: center; margin: 20px 0;">
    <strong style="font-size: 24px;">{{ $otp }}</strong>
</div>

Thanks,<br>
{{ config('app.name') }}
@endcomponent


