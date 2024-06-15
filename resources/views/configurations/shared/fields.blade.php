@php
    $mode = $mode ?? 'edit';
    $readonly = $mode == 'show';
@endphp

<div class="flex flex-wrap space-x-8">
    <div class="grow mt-6 space-y-4">





        <x-field.input name="ticket_price" label="Ticket price" :readonly="$readonly"
        value="{{ old('ticket_price', $configuration->ticket_price) }}"/>
        <x-field.input name="registered_customer_ticket_discount" label="Customer ticket discount" :readonly="$readonly"
        value="{{ old('registered_customer_ticket_discount', $configuration->registered_customer_ticket_discount) }}"/>

    </div>
</div>
