@component('mail::message')
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('public/admin/assets/images/logo.png') }}" alt="Logo" style="max-width: 100px;">
</div>

<h1 style="margin:0 auto 10px;width:145px">Registration</h1>

<p>Congratulations! Your Account has been created by Retail Store Management Team, Now you can log in with the following credentials:</p>

<p><strong>Email:</strong> {{ $message['email'] }}</p>
<p><strong>Password:</strong> {{ $message['password'] }}</p>

<p style="width: 160px;margin:auto"><a href="{{url('/login')}}" style="padding:5px 10px;color:rgb(253, 253, 253);background:rgb(18, 19, 18);border-radius:5px;text-decoration:none">Click here to Login </a></p>


Thanks,<br>
{{ config('app.name') }}
@endcomponent
