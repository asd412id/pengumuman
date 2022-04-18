<x-app-layout>
	<x-slot name="title">{{ __($title) }}</x-slot>
	<x-slot name="header">
		<h2 class="text-xl font-semibold leading-tight text-gray-800">
			{{ __($title) }}
		</h2>
	</x-slot>

	<div class="py-6">
		<div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
			<div class="flex mb-2 gap-1" x-data>
				<form action="{{ route('peserta.import') }}" method="post" class="hidden" x-ref="import_submit"
					enctype="multipart/form-data">
					@csrf
					<input type="file" name="import" x-ref="import" accept=".ods,.xls,.xlsx,.bin"
						x-on:change="$refs.import_submit.submit()">
				</form>
				<x-button class="gap-2" x-on:click="$refs.import.click()">
					<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
						stroke-width="2">
						<path stroke-linecap="round" stroke-linejoin="round"
							d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
					</svg>
					IMPORT DATA
				</x-button>
				<form action="{{ route('template.download') }}" method="post">
					@csrf
					<x-button class="gap-2 bg-green-700 hover:bg-green-600">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
							stroke="currentColor" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round"
								d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
						</svg>
						DOWLOAD TEMPLATE
					</x-button>
				</form>
			</div>
			<div class="overflow-hidden bg-white border-b border-gray-200 rounded-lg shadow-sm">
				<table class="min-w-full leading-normal">
					<thead>
						<tr>
							<th
								class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase bg-gray-100 border-b-2 border-gray-200">
								NO.
							</th>
							<th
								class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase bg-gray-100 border-b-2 border-gray-200">
								NISN
							</th>
							<th
								class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase bg-gray-100 border-b-2 border-gray-200">
								NAMA
							</th>
							<th
								class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase bg-gray-100 border-b-2 border-gray-200">
								KELAS
							</th>
							<th
								class="flex justify-end px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase bg-gray-100 border-b-2 border-gray-200">
								AKSI
							</th>
						</tr>
					</thead>
					<tbody>
						@forelse ($data as $key => $item)
						<tr>
							<td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
								<p class="text-gray-900 whitespace-no-wrap">{{ $data->firstItem()+$key }}.</p>
							</td>
							<td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
								<p class="text-gray-900 whitespace-no-wrap">{{ $item->nisn }}</p>
							</td>
							<td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
								<p class="text-gray-900 whitespace-no-wrap">
									{{ $item->name }}
								</p>
							</td>
							<td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
								<p class="text-gray-900 whitespace-no-wrap">
									{{ $item->kelas }}
								</p>
							</td>
							<td class="flex justify-end px-5 py-5 text-sm bg-white border-b border-gray-200">
								<p class="flex gap-1 text-gray-900 whitespace-no-wrap">
									<a href="{{ route('peserta.download',['peserta' => $item]) }}" class="font-bold text-teal-600"
										title="Download Sertifikat">
										<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
											stroke="currentColor" stroke-width="2">
											<path stroke-linecap="round" stroke-linejoin="round"
												d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
										</svg>
									</a>
									<a href="{{ route('peserta.delete',['peserta' => $item]) }}" class="font-bold text-red-600"
										title="Hapus Data">
										<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
											stroke="currentColor" stroke-width="2">
											<path stroke-linecap="round" stroke-linejoin="round"
												d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
										</svg>
									</a>
								</p>
							</td>
						</tr>
						@empty
						<tr>
							<td colspan="5" class="p-5 italic text-center">Data tidak tersedia!</td>
						</tr>
						@endforelse
					</tbody>
				</table>
			</div>
			<div class="flex justify-end mt-2">
				{!! $data->links() !!}
			</div>
		</div>
	</div>
</x-app-layout>