@component('mail::message')
<style>
    table {
        width: 95%!important;
        overflow-x: auto!important;
        margin-top: 10px; /* Maintain margin for table */
    }
    h1 {
        font-size: 10px; /* Adjust the font size for the header */
        margin: 10px 0; /* Add margin for spacing above and below */
    }
    .greeting {
        margin: 20px 0; /* Add margin for spacing above the greeting */
        font-size: 8px; /* Adjust font size for better readability */
    }
</style>

<!-- Logo -->
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('public/admin/assets/images/logo.png') }}" alt="Logo" style="max-width: 150px;">
</div>

<div style="text-align: center; margin-bottom: 10px;">
    <strong>Order # {{ $orderCode }}</strong>  <!-- Display order code -->
</div>

<table width="100%" cellspacing="0" cellpadding="0" margin-bottom: 10px;>
    <tr>
        <td><strong>Store Manager Name:</strong> {{ $storeManagerName }}</td>
        <td style="text-align: right;"><strong>Store Name:</strong> {{ $storeName }}</td>
    </tr>
    <tr>
        <td><strong>Store Address:</strong> {{ $storeAddress }}</td>
        <td style="text-align: right;"><strong>Store Phone Number:</strong> {{ $storePhone }}</td>
    </tr>
</table>

<!-- Greeting and introduction -->
<div class="greeting">
    <h1>Dear {{ $vendorName }},</h1>
</div>

This is to inform you that the store manager has initiated an audit and found that you have been charging **overcharged prices**.

<!-- Reason Section -->
@if ($description)
@component('mail::panel')
### Reason Provided:
{{ $description }}
@endcomponent
@else
@component('mail::panel')
**No additional details were provided at this time.**
@endcomponent
@endif

If you have any questions or need further clarification, please contact the store manager directly.

<!-- Buttons Section -->
@component('mail::button', ['url' => asset($vendorRecepit)])
View Vendor Receipt
@endcomponent

@component('mail::button', ['url' => asset($storeManagerRecepit)])
View Store Manager Receipt
@endcomponent

<!-- Closing and Signature -->
<div style="margin-top: 30px;">
    <strong>Thanks,</strong><br>
    {{ config('app.name') }}
</div>

@endcomponent
