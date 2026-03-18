@component('mail::message')

<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('admin/assets/images/logo.png') }}" alt="Logo" style="max-width: 100px;">
</div>

# Account Activation

Congratulations! Your account has been activated by the Retail Store Management Team. You can now log in to your panel using the following credentials:

- **Email:** {{ $message['email'] }}
- **Password:** {{ $message['password'] }}

@component('mail::button', ['url' => url('/login')])
Click Here to Login
@endcomponent

Thanks,
{{ config('app.name') }}


@endcomponent
