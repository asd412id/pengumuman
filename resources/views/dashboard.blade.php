<x-app-layout>
	<x-slot name="title">{{ __($title) }}</x-slot>
	<x-slot name="header">
		<h2 class="text-xl font-semibold leading-tight text-gray-800">
			{{ __($title) }}
		</h2>
	</x-slot>

	<div class="py-6">
		<div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
			<div class="flex gap-5 flex-col p-5 overflow-hidden bg-white border-b border-gray-200 rounded-lg shadow-sm">
				@if ($errors->any())
				<div class="text-center w-full font-bold p-3 rounded-md bg-red-100 text-red-600">
					<x-auth-validation-errors />
				</div>
				@endif
				<div class="flex gap-5">
					<form action="{{ route('setting.store') }}" method="post"
						class="flex flex-col w-6/12 gap-2 p-5 border border-gray-500 rounded-md" enctype="multipart/form-data">
						@csrf
						<div class="flex flex-col">
							<x-label for="nama_sekolah">Nama Sekolah</x-label>
							<x-input id="nama_sekolah" type="text" name="setting[nama_sekolah]" :value="@$data['nama_sekolah']"
								placeholder="Masukkan nama sekolah" />
						</div>
						<div class="flex flex-col">
							<x-label for="nomor_sertifikat">Nomor Sertifikat</x-label>
							<x-input id="nomor_sertifikat" type="text" name="setting[nomor_sertifikat]"
								:value="@$data['nomor_sertifikat']" placeholder="Masukkan nomor sertifikat" />
						</div>
						<div class="flex flex-col">
							<x-label for="tanggal_sertifikat">Tanggal Sertifikat</x-label>
							<x-input id="tanggal_sertifikat" type="date" name="setting[tanggal_sertifikat]"
								:value="@$data['tanggal_sertifikat']" placeholder="Masukkan tanggal sertifikat" />
						</div>
						<div class="flex flex-col">
							<x-label for="tanggal_pengumuman">Tanggal & Jam Pengumuman</x-label>
							<x-input id="tanggal_pengumuman" type="datetime-local" name="setting[tanggal_pengumuman]"
								:value="@$data['tanggal_pengumuman']" placeholder="Masukkan tanggal & jam pengumuman" />
						</div>
						<div class="flex flex-col gap-2">
							<div class="flex flex-col">
								<x-label for="logo">Logo</x-label>
								<x-input id="logo" type="file" name="logo" accept=".jpg,.jpeg,.png" />
							</div>
							@if (isset($data['logo']))
							<img src="{{ asset('files/'.$data['logo']) }}" alt="" class="w-36 h-36 rounded-md">
							@endif
						</div>
						<div class="flex justify-end">
							<x-button type="submit">SIMPAN PENGATURAN</x-button>
						</div>
					</form>
					<form action="{{ route('user.update') }}" method="post"
						class="flex flex-col w-6/12 gap-2 p-5 border border-gray-500 rounded-md">
						@csrf
						<div class="flex flex-col">
							<x-label for="name">Nama Lengkap</x-label>
							<x-input id="name" type="text" name="name" :value="$user->name" placeholder="Masukkan nama lengkap" />
						</div>
						<div class="flex flex-col">
							<x-label for="username">Username</x-label>
							<x-input id="username" type="text" name="username" :value="$user->username"
								placeholder="Masukkan username" />
						</div>
						<div class="flex flex-col">
							<x-label for="password">Password lama</x-label>
							<x-input id="password" type="password" name="password" placeholder="Masukkan password lama" />
						</div>
						<div class="flex flex-col">
							<x-label for="new_password">Password Baru</x-label>
							<x-input id="new_password" type="password" name="new_password" placeholder="Masukkan password baru" />
						</div>
						<div class="flex flex-col">
							<x-label for="renew_password">Ulang Password Baru</x-label>
							<x-input id="renew_password" type="password" name="renew_password"
								placeholder="Masukkan ulang password baru" />
						</div>
						<div class="flex justify-end">
							<x-button type="submit">UBAH DATA ADMIN</x-button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>