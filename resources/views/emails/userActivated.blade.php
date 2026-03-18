@component('mail::message')
{{-- <p style="margin:0 auto 10px;width:145px">Store Manager Activated</p> --}}
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('public/admin/assets/images/logo.png') }}" alt="Logo" style="max-width: 100px;">
</div>

<h1>Hi {{ $message['first_name'] }} {{ $message['last_name'] }},</h1>
<p>Congratulations! Your account has been activated by the Retail Store Management Team. You can now login to your panel using your credentials.</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
