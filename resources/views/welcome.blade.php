<x-guest-layout>
	<x-slot name="title">{{ isset($title)?$title:'Selamat Datang' }}</x-slot>

	@php
	$settings = App\Models\Setting::all();
	$setting = [];
	foreach ($settings as $key => $v) {
	$setting[$v->setting] = $v->value;
	}
	@endphp

	<!-- This example requires Tailwind CSS v2.0+ -->
	<div class="relative bg-white overflow-hidden">
		<div class="max-w-7xl mx-auto">
			<div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32 min-h-screen">
				<svg class="hidden lg:block absolute right-0 inset-y-0 h-full w-48 text-white transform translate-x-1/2"
					fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
					<polygon points="50,0 100,0 50,100 0,100" />
				</svg>

				<div>
					<div class="relative pt-6 px-4 sm:px-6 lg:px-8"></div>
				</div>

				<main class="mt-5 mx-auto max-w-7xl px-4 sm:mt-5 sm:px-6 md:mt-5 lg:mt-5 lg:px-16 xl:mt-5">
					<div class="flex justify-center mb-5">
						<a href="{{ url('/') }}">
							<x-application-logo class="h-32 w-auto" />
						</a>
					</div>
					<div class="text-center">
						<h1 class="text-3xl md:text-5xl tracking-tight font-extrabold text-gray-900">
							<span class="block">Pengumuman Kelulusan</span>
							<span class="block text-red-600">{{ @$setting['nama_sekolah']??config('app.name') }}</span>
						</h1>

						@if (isset($setting['tanggal_pengumuman']) &&
						Carbon\Carbon::parse($setting['tanggal_pengumuman'])->lessThanOrEqualTo(now()))
						<div
							class="text-2xl md:text-3xl p-3 bg-teal-50 border-teal-700 rounded-md tracking-tight font-extrabold text-teal-700 mt-5">
							TELAH DIBUKA</div>

						@if (!isset($data))
						<form action="{{ route('lulus.cek') }}" method="post" class="mt-6 flex flex-col gap-2">
							@csrf
							@if ($errors->any())
							<x-auth-validation-errors />
							@endif
							<x-input type="text" autocomplete="off" class="text-center" name="nisn" placeholder="MASUKKAN NISN"
								required />
							<div class="mt-2 sm:flex sm:justify-center lg:justify-center">
								<div class="rounded-md shadow">
									<button type="submit"
										class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700 md:py-4 md:text-lg md:px-10">
										CEK KELULUSAN</button>
								</div>
							</div>
						</form>
						@else
						<div
							class="text-xl p-3 {{ $data->lulus=='1'?'bg-emerald-50 border-emerald-700 text-emerald-700':'bg-rose-50 border-rose-700 text-rose-700' }} rounded-md tracking-tight font-extrabold mt-1">
							NAMA: {{ strtoupper($data->name) }}<br>NISN: {{ strtoupper($data->nisn) }}<br>KELAS: {{
							strtoupper($data->kelas) }}<br>
							{{ $data->lulus == '1'?'SELAMAT, ANDA DINYATAKAN LULUS!':'MAAF, ANDA TIDAK DINYATAKAN LULUS!' }}
						</div>
						@if ($data->lulus=='1')
						<form action="{{ route('lulus.download') }}" method="post" class="flex justify-center mt-2">
							<input type="hidden" name="pid" value="{{ $data->id }}">
							@csrf
							<button type="submit"
								class="px-6 py-2 border border-transparent text-base font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700 md:py-2 md:text-lg md:px-10">
								DOWNLOAD BUKTI KELULUSAN</button>
						</form>
						@endif
						@endif
						@else
						@if (isset($setting['tanggal_pengumuman']))
						@php
						$date = Carbon\Carbon::parse($setting['tanggal_pengumuman']);
						@endphp
						<p
							class="mt-3 text-base text-rose-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-2xl lg:mx-0 bg-rose-50 p-1 rounded-md">
							Pengumuman baru dapat diakses pada tanggal {{
							$date->translatedFormat('d F Y') }} mulai pukul {{
							$date->translatedFormat('H.i') }}
							WITA
						</p>
						@else
						<p
							class="mt-3 text-base text-rose-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-2xl lg:mx-0 bg-rose-50 p-1 rounded-md">
							Pengumuman tidak dapat diakses saat ini
						</p>
						@endif
						@endif
					</div>
				</main>
			</div>
		</div>
		<div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
			<img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full"
				src="{{ asset('photo-1551434678-e076c223a692.avif') }}" alt="">
		</div>
	</div>
</x-guest-layout>