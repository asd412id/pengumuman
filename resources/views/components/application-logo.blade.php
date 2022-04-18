@php
$logo = App\Models\Setting::where('setting','logo')->first();
@endphp
<img src="{{ asset('files/'.@$logo->value) }}" alt="" {{ $attributes }}>