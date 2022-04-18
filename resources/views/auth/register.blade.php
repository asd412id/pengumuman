<x-guest-layout>
	<x-auth-card>
		<x-slot name="logo">
			<a href="/">
				<x-application-logo class="w-20 h-20 text-gray-500 fill-current" />
			</a>
		</x-slot>

		<!-- Validation Errors -->
		<x-auth-validation-errors class="mb-4" :errors="$errors" />

		<form method="POST" action="{{ route('register') }}">
			@csrf

			<!-- Name -->
			<div>
				<x-label for="name" :value="__('Name')" />

				<x-input id="name" class="block w-full mt-1" type="text" name="name" :value="old('name')" required autofocus />
			</div>

			<!-- Username -->
			<div class="mt-4">
				<x-label for="username" :value="__('Username')" />

				<x-input id="username" class="block w-full mt-1" type="text" name="username" :value="old('username')"
					required />
			</div>

			<!-- Password -->
			<div class="mt-4">
				<x-label for="password" :value="__('Password')" />

				<x-input id="password" class="block w-full mt-1" type="password" name="password" required
					autocomplete="new-password" />
			</div>

			<!-- Konfirmasi Password -->
			<div class="mt-4">
				<x-label for="password_confirmation" :value="__('Konfirmasi Password')" />

				<x-input id="password_confirmation" class="block w-full mt-1" type="password" name="password_confirmation"
					required />
			</div>

			<div class="flex items-center justify-end mt-4">
				<a class="text-sm text-gray-600 underline hover:text-gray-900" href="{{ route('login') }}">
					{{ __('Sudah terdaftar?') }}
				</a>

				<x-button class="ml-4">
					{{ __('Daftar') }}
				</x-button>
			</div>
		</form>
	</x-auth-card>
</x-guest-layout>