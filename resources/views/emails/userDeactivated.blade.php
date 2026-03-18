@component('mail::message')
{{-- <p style="margin:0 auto 10px;width:145px">Store Manager Deactivated</p> --}}
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('public/admin/assets/images/logo.png') }}" alt="Logo" style="max-width: 100px;">
</div>

<h1>Hi {{ $message['first_name'] }} {{ $message['last_name'] }},</h1>
<p>We regret to inform you that your account has been deactivated by the Retail Store Management Team.
<h1>Reason:</h1>
<p>{{ $message['reason'] }}</p>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
