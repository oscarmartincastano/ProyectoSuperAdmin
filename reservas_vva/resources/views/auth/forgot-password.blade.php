@section('title', 'Olvido de contraseña')

<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/{{ request()->slug_instalacion ?? '' }}">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('¿Has olvidado tu contraseña? No hay problema. Haznos saber tu dirección de email y te enviaremos un mensaje en el que podrás poner una contraseña nueva.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="https://{{$_SERVER["HTTP_HOST"]}}/{{ request()->slug_instalacion}}/forgot-password">
            @csrf

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('Enviar email') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
