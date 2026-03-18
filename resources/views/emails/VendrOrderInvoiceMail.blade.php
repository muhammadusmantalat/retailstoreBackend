@component('mail::message')
<style>
    table {
        width: 95%!important;
        overflow-x: auto!important;
    }
</style>
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('public/admin/assets/images/logo.png') }}" alt="Logo" style="max-width: 100px;">
</div>

<div style="text-align: center;">
    <strong>Your Order Invoice</strong>
</div>

<table width="100%" cellspacing="0" cellpadding="0" style="margin-top: 30px;">
    <tr>
        <td style="width: 50%;"><strong>Date:</strong> {{ $emailData['order']->date }}</td>
        <td style="width: 50%; text-align: right;"><strong>Invoice #</strong> {{ $emailData['order']->invoice_number }}</td>
    </tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0" style="margin-top: 10px;">
    <tr>
        <td><strong>Store Manager Name:</strong> {{ $emailData['order']->store_manager_name }}</td>
        <td style="text-align: right;"><strong>Store Name:</strong> {{ $emailData['order']->store_name }}</td>
    </tr>
    <tr>
        <td><strong>Store Address:</strong> {{ $emailData['order']->store_address }}</td>
        <td style="text-align: right;"><strong>Store Phone Number:</strong> {{ $emailData['order']->store_phone_no }}</td>
    </tr>
    <tr>
        <td><strong>Wholesaler Name:</strong> {{ $emailData['order']->vendor_name }}</td>
    </tr>
</table>

<table width="100%" cellspacing="0" cellpadding="10" style="margin-top: 20px; border-collapse: collapse;">
    <thead>
        <tr>
            <th style="text-align: left;">Product Name</th>
            <th style="text-align: center;">Quantity</th>
            <th style="text-align: right;">Price</th>
            <th style="text-align: right;">Total Price</th>
            <th style="text-align: right;">Vendor Discount</th>
            <th style="text-align: right;">Discount Amount</th>
            <th style="text-align: right;">Price After Discount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($emailData['productDetails'] as $product)
        <tr>
            <td style="text-align: left;">{{ $product['product_name'] }}</td>
            <td style="text-align: center;">{{ $product['quantity'] }}</td>
            <td style="text-align: right;">${{ number_format($product['price'], 2) }}</td>
            <td style="text-align: right;">${{ number_format($product['sub_total'], 2) }}</td>
            <td style="text-align: right;">{{ number_format($product['discount_price'], 2) }}%</td>
            <td style="text-align: right;">${{ number_format($product['discount_amount'], 2) }}</td>
            <td style="text-align: right;">${{ number_format($product['sub_total_after_discount'], 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div style="margin-top: 10px;">
    <strong>Total Amount: ${{ number_format(array_sum(array_column($emailData['productDetails'], 'sub_total')), 2) }}</strong>
</div>

{{-- <div style="margin-top: 10px;">
    <strong>Vendor Discount: {{ number_format(array_column($emailData['productDetails'], 'discount_price')) }}%</strong>
</div> --}}

<div style="margin-top: 10px;">
    <strong>Discount Amount: ${{ number_format(array_sum(array_column($emailData['productDetails'], 'discount_amount')), 2) }}</strong>
</div>

<div style="margin-top: 10px;">
    <strong>Payable Amount After Discount: ${{ number_format(array_sum(array_column($emailData['productDetails'], 'sub_total_after_discount')), 2) }}</strong>
</div>

<div style="margin-top: 30px;">
    <strong>Thanks,</strong><br>
    {{ config('app.name') }}
</div>

@endcomponent
