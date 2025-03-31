@section('title', 'Iniciar sesión')
<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/{{ request()->slug_instalacion ?? '' }}">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form id="form-login" method="POST" action="@if (isset(request()->slug_instalacion)) {{ route('login', ['slug_instalacion' => request()->slug_instalacion]) }} @else {{ route('login', ['slug_instalacion' => 'manager']) }} @endif">
            @csrf

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Contraseña')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Recordarme') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4" style="gap:15px">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" style="display:block" href="@if (isset(request()->slug_instalacion)) {{ route('register_user_instalacion', ['slug_instalacion' => request()->slug_instalacion]) }} @endif">
                        {{ __('Registrarme') }}
                    </a>
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="@if (isset(request()->slug_instalacion)) {{ route('forgot_password_instalacion', ['slug_instalacion' => request()->slug_instalacion]) }}  @else {{ route('password.request') }}@endif">
                        {{ __('¿Has olvidado tu contraseña?') }}
                    </a>
                @endif

                <x-button id="boton-login">
                    {{ __('Iniciar sesión') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function () {


        $('#form-login').on('submit', function() {
            $('#boton-login').prop('disabled', true);
            $('#boton-login').text('Iniciando');
        });

        $('#email').on('focus',function(){  
            $("#boton-login").removeAttr("disabled");
            $('#boton-login').text('Iniciar sesión');
        });

        $('#password').on('focus',function(){  
            $("#boton-login").removeAttr("disabled");
            $('#boton-login').text('Iniciar sesión');
        });

    });
</script>
