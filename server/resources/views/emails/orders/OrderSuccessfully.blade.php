@component('mail::message')
<h1>Order Information</h1>
<div>
    <h3>Hi, {{ $username }}</h3>
    <div>
        <p>
            Thank you so much for shopping with us!.
            We will get started on your order right away.
            When we confirm your order, we will send an notification email.
        </p>
        <p>
            In the meantime, if any questions come up, please do not hesitate to message us.
            Any of our customer service agents will always be happy to help.
        </p>
    </div>
</div>

<div>
    <h2>Hear what you order!</h2>
    <div style="display: flex; justify-content: space-between; align-items: center">
        <small>TransactionID: {{ $transactionId }}</small>
        <small>Order Date: {{ $orderDate }}</small>
    </div>
</div>

@component('mail::table')
| Item      | Quantity      | Price         |
| :---------| :-----------: | ------------: |
@foreach ($items as $item)
| {{ $item->name }}         | {{ $item->pivot->quantity }} |       {{ number_format($item->price, 0, '', ',') }} VND |
@endforeach
@endcomponent

<h2>Total Price: {{ number_format($totalPrice, 0, '', ',') }} VND</h2>

<div align="center">
    <h2 align="center">Sent To</h2>
    <h4>Name: {{ $receiverName }}</h4>
    <h4>Phone Number: {{ $receiverPhoneNumber }}</h4>
    <h4>Address: {{ $receiverAddress }}</h4>
</div>

@component('mail::button', ['url' => $url, 'color' => 'green'])
View Order Status
@endcomponent

Best Regards,<br>
Shin
@endcomponent
