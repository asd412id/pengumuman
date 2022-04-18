<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use IOFactory;

class Controller extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public function index()
	{
		$settings = Setting::all();

		$data = [];

		if (count($settings)) {
			foreach ($settings as $key => $v) {
				$data[$v->setting] = $v->value;
			}
		}

		return view('dashboard', [
			'title' => 'Beranda',
			'data' => $data,
			'user' => auth()->user(),
		]);
	}

	public function settingStore(Request $r)
	{
		$oldLogo = Setting::where('setting', 'logo')->first();
		if ($r->logo != null) {
			if ($r->file('logo')->isValid()) {
				$r->validate([
					'logo' => 'mimes:png,jpg,jpeg|max:2048'
				]);

				if ($oldLogo) {
					Storage::disk('public')->delete($oldLogo->value);
				}

				$logoPath = $r->logo->store('', 'public');

				$insert = new Setting;
				$insert->setting = 'logo';
				$insert->value = $logoPath;
				$insert->save();
			}
		}
		Setting::whereIn('setting', [
			'nama_sekolah',
			'nomor_sertifikat',
			'tanggal_sertifikat',
			'tanggal_pengumuman',
		])->delete();
		foreach ($r->setting as $key => $v) {
			$insert = new Setting();
			$insert->setting = $key;
			$insert->value = $v;
			$insert->save();
		}
		return redirect()->route('dashboard')->with('message', 'Data berhasil disimpan');
	}

	public function userUpdate(Request $r)
	{
		$r->validate([
			'name' => 'required|string',
			'username' => 'required|unique:users,username,' . auth()->user()->id . ',id',
			'password' => 'required',
			'renew_password' => 'same:new_password',
		]);

		$check = Hash::check($r->password, auth()->user()->password);
		if ($check) {
			$user = auth()->user();
			$user->name = $r->name;
			$user->username = $r->username;
			if ($r->new_password) {
				$user->password = bcrypt($r->new_password);
			}
			$user->save();
			return redirect()->route('dashboard')->with('message', 'Data berhasil disimpan');
		}
		return redirect()->back()->withErrors('Password tidak benar');
	}

	public function peserta()
	{
		return view('peserta', [
			'title' => 'Daftar Peserta',
			'data' => Peserta::orderBy('kelas', 'asc')
				->orderBy('name', 'asc')
				->paginate(15),
		]);
	}

	public function downloadTemplate()
	{
		return response()->download(public_path('Template Data Siswa.xlsx'));
	}

	public function import(Request $r)
	{
		if (!$r->file('import')->isValid()) {
			return redirect()->back()->withErrors('File yang diupload tidak sesuai');
		}
		$r->validate([
			'import' => 'required|mimes:ods,xls,xlsx,bin'
		]);

		$spreadsheet = IOFactory::load($r->import->path());
		$sheet = $spreadsheet->getActiveSheet()->toArray();

		$nisn = null;
		$name = null;
		$kelas = null;
		$lulus = null;
		Peserta::truncate();

		foreach ($sheet as $key => $row) {
			if ($key == 0) {
				foreach ($row as $key1 => $col) {
					if (strtolower($col) == 'nisn') {
						$nisn = $key1;
					} elseif (strtolower($col) == 'nama') {
						$name = $key1;
					} elseif (strtolower($col) == 'kelas') {
						$kelas = $key1;
					} elseif (strtolower($col) == 'lulus') {
						$lulus = $key1;
					}
				}
			} else {
				try {
					$insert = new Peserta();
					$insert->nisn = trim($row[$nisn]);
					$insert->name = trim($row[$name]);
					$insert->kelas = trim($row[$kelas]);
					$insert->lulus = !is_null($lulus) ? trim($row[$lulus]) : 1;
					$insert->save();
				} catch (\Throwable $th) {
					//throw $th;
				}
			}
		}

		return redirect()->route('peserta.index')->with('message', 'Data berhasil diimport');
	}

	public function delete(Peserta $peserta)
	{
		$peserta->delete();
		return redirect()->route('peserta.index')->with('message', 'Data berhasil dihapus');
	}

	public function download(Peserta $peserta)
	{
		$sertPath = public_path('sert/sert.jpg');

		$img = Image::make($sertPath);

		$name = $peserta->name;
		$limitLength = 12;
		$baseSize = 113;

		$fontSize = strlen($name) <= $limitLength ? $baseSize : $limitLength / strlen($name) * $baseSize;

		$img->text($name, 1635, 529, function ($font) use ($fontSize) {
			$font->file(public_path('font/Montserrat-ExtraBold.ttf'));
			$font->size($fontSize);
			$font->align('right');
			$font->valign('middle');
			$font->color('#e6b83d');
		});

		$date = Setting::where('setting', 'tanggal_sertifikat')->first();

		if ($date) {
			$date = Carbon::parse($date->value);
		} else {
			$date = Carbon::now();
		}

		$img->text($date->translatedFormat('d F Y'), 1407, 840, function ($font) {
			$font->file(public_path('font/Montserrat-Italic.ttf'));
			$font->size(30);
			$font->align('center');
			$font->valign('middle');
			$font->color('#303231');
		});

		$file_name = $peserta->nisn . '_' . $name . '.jpg';

		$img->save(storage_path() . '/' . $file_name);

		return response()->download(storage_path() . '/' . $file_name)->deleteFileAfterSend();
	}

	public function downloadCert(Request $r)
	{
		$tgl = Setting::where('setting', 'tanggal_pengumuman')->first();

		if ($tgl) {
			$limit = Carbon::parse($tgl->value);

			if ($limit->greaterThan(now())) {
				return redirect()->back()->withErrors('Pengumuman belum dibuka!');
			}
		} else {
			return redirect()->back()->withErrors('Pengumuman belum dibuka!');
		}

		$peserta = Peserta::find($r->pid);
		$sertPath = public_path('sert/sert.jpg');

		$img = Image::make($sertPath);

		$name = $peserta->name;
		$limitLength = 12;
		$baseSize = 113;

		$fontSize = strlen($name) <= $limitLength ? $baseSize : $limitLength / strlen($name) * $baseSize;

		$img->text($name, 1635, 529, function ($font) use ($fontSize) {
			$font->file(public_path('font/Montserrat-ExtraBold.ttf'));
			$font->size($fontSize);
			$font->align('right');
			$font->valign('middle');
			$font->color('#e6b83d');
		});

		$date = Setting::where('setting', 'tanggal_sertifikat')->first();

		if ($date) {
			$date = Carbon::parse($date->value);
		} else {
			$date = Carbon::now();
		}

		$img->text($date->translatedFormat('d F Y'), 1407, 840, function ($font) {
			$font->file(public_path('font/Montserrat-Italic.ttf'));
			$font->size(30);
			$font->align('center');
			$font->valign('middle');
			$font->color('#303231');
		});

		$file_name = $peserta->nisn . '_' . $name . '.jpg';

		$img->save(storage_path() . '/' . $file_name);

		return response()->download(storage_path() . '/' . $file_name)->deleteFileAfterSend();
	}

	public function cekLulus(Request $r)
	{
		$tgl = Setting::where('setting', 'tanggal_pengumuman')->first();

		if ($tgl) {
			$limit = Carbon::parse($tgl->value);

			if ($limit->greaterThan(now())) {
				return redirect()->back()->withErrors('Pengumuman belum dibuka!');
			}
		} else {
			return redirect()->back()->withErrors('Pengumuman belum dibuka!');
		}

		$r->validate([
			'nisn' => 'required'
		]);

		$cek = Peserta::where('nisn', $r->nisn)->first();

		if (!$cek) {
			return redirect()->back()->withErrors('NISN tidak terdaftar pada sistem!');
		}

		return view('welcome', [
			'title' => 'Hasil Pengumuman',
			'data' => $cek
		]);
	}
}
