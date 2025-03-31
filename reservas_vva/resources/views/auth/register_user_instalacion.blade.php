@section('title', 'Registrarse')
<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/{{ request()->slug_instalacion ?? '' }}">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST">
            @csrf
            
            <!-- Name -->
            <div>
                <x-label for="name" :value="__('Nombre y apellidos')" />

                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <div class="mt-4">
                <x-label for="tlfno" :value="__('Teléfono')" />

                <x-input id="tlfno" class="block mt-1 w-full" type="number" name="tlfno" autofocus required/>
            </div>

            {{-- <div class="mt-4">
                <x-label for="direccion" :value="__('Direccion')" />

                <x-input id="direccion" class="block mt-1 w-full" type="text" name="direccion"/>
            </div> --}}

            <div class="mt-4">
                <x-label for="codigo_postal" :value="__('Código postal')" />

                <x-input id="codigo_postal" class="block mt-1 w-full" type="text" name="codigo_postal" autofocus />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Contraseña (Mínimo 8 caracteres)')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('Confirma Contraseña (Mínimo 8 caracteres)')" />

                <x-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required />
            </div>

            <div class="mt-4 form-check" style="display: flex; align-items: center; gap:5px">
                <input id="terminos" class=" form-check-input rounded" type="checkbox" name="terminos" required />
                <label for="terminos" class="form-check-label" >Acepto los <a href="/{{request()->slug_instalacion}}/terminos-condiciones" target="_blank" style="text-decoration: underline" >términos y condiciones</a></label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ request()->slug_instalacion ? '/' . request()->slug_instalacion . '/login' : '/login' }}">
                    {{ __('¿Ya estás registrado? Inicia sesión') }}
                </a>

                <x-button class="ml-4">
                    {{ __('Registrarse') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
