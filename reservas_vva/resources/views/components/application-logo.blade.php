@if (file_exists(public_path() . '/img/'. request()->slug_instalacion .'.png'))
    <img src="/img/{{ request()->slug_instalacion }}.png" style="max-width:245px">
@else
    <img src="/img/tallerempresarial.png" style="max-width:245px">
@endif