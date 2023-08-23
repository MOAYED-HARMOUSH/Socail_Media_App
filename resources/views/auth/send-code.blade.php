@component('vendor.mail.html.message')

<h1>We have recieved your request</h1>
<p>You can use the following code to resolve your account:</p>

@component('vendor.mail.html.panel')

{{$code}}

@endcomponent

<p>The allowed duration of the code is one hour from the time the message </p>
@endcomponent
