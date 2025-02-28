<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Soal1;
use App\Models\Soal2;
use App\Models\Soal3;
use App\Models\Soal4;
use App\Models\Soal5;
use App\Models\Soal6;
use App\Models\Soal7;
use App\Models\Soal8;
use App\Models\Soal9;
use App\Models\Soal10;
use App\Models\Soal11;
use App\Models\Soal12;
use App\Models\Soal13;
use App\Models\Soal14;
use App\Models\Soal15;
use App\Models\Soal16;
use App\Models\Soal17;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    private function calculateTotalNilai($userId)
    {
        $overview = [];
        for ($i = 1; $i <= 17; $i++) {
            $model = "\\App\\Models\\Soal{$i}";
            $exists = class_exists($model) && $model::where('user_id', $userId)->exists();
            $nilai = $exists ? $model::where('user_id', $userId)->value('nilai') ?? 0 : 0;
            $overview["soal{$i}"] = ['nilai' => $nilai];
        }
        return array_sum(array_column($overview, 'nilai'));
    }

    private function updateSoalNilai($soalNumber, $userId)
    {
        $controllerClass = "\\App\\Http\\Controllers\\Soal{$soalNumber}Controller";
        if (class_exists($controllerClass)) {
            $controller = app($controllerClass);
            $soal = ("\\App\\Models\\Soal{$soalNumber}")::where('user_id', $userId)->first();
            if ($soal) {
                $data = $soal->toArray();
                $request = new Request($data);
                $controller->update($request);
            }
        }
    }

    public function users()
    {
        $users = User::all(['id', 'name', 'email', 'created_at', 'last_submission_date', 'is_verified', 'nilai']);
        return response()->json(['data' => $users], 200);
    }

    public function userDetail($userId)
    {
        $user = User::findOrFail($userId);
        
        $soals = [
            'soal1' => Soal1::where('user_id', $userId)->first(),
            'soal2' => Soal2::where('user_id', $userId)->first(),
            'soal3' => Soal3::where('user_id', $userId)->first(),
            'soal4' => Soal4::where('user_id', $userId)->first(),
            'soal5' => Soal5::where('user_id', $userId)->first(),
            'soal6' => Soal6::where('user_id', $userId)->first(),
            'soal7' => Soal7::where('user_id', $userId)->first(),
            'soal8' => Soal8::where('user_id', $userId)->first(),
            'soal9' => Soal9::where('user_id', $userId)->first(),
            'soal10' => Soal10::where('user_id', $userId)->first(),
            'soal11' => Soal11::where('user_id', $userId)->first(),
            'soal12' => Soal12::where('user_id', $userId)->first(),
            'soal13' => Soal13::where('user_id', $userId)->first(),
            'soal14' => Soal14::where('user_id', $userId)->first(),
            'soal15' => Soal15::where('user_id', $userId)->first(),
            'soal16' => Soal16::where('user_id', $userId)->first(),
            'soal17' => Soal17::where('user_id', $userId)->first(),
        ];

        $totalNilai = $this->calculateTotalNilai($userId);
        
        return response()->json([
            'user' => $user,
            'soals' => $soals,
            'totalNilai' => $totalNilai
        ], 200);
    }

    public function getSoal($soalNumber, $userId)
    {
        $modelClass = "App\\Models\\Soal{$soalNumber}";
        if (!class_exists($modelClass)) {
            return response()->json(['message' => 'Soal tidak ditemukan'], 404);
        }
        
        $soal = $modelClass::where('user_id', $userId)->first();
        if (!$soal) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $user = User::findOrFail($userId);
        $soal->status = $user->last_submission_date ? 'submitted' : 'in_progress';
        
        return response()->json(['data' => $soal], 200);
    }

    public function updateSoal(Request $request, $soalNumber, $userId)
    {
        $modelClass = "App\\Models\\Soal{$soalNumber}";
        if (!class_exists($modelClass)) {
            return response()->json(['message' => 'Soal tidak ditemukan'], 404);
        }
        
        $soal = $modelClass::where('user_id', $userId)->first();
        if (!$soal) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        
        $request->validate([
            'nilai' => 'nullable|numeric|min:0|max:100'
        ]);
        
        if ($request->has('nilai')) {
            $soal->nilai = $request->nilai;
            $soal->save();
        }
        
        $totalNilai = $this->calculateTotalNilai($userId);
        
        return response()->json([
            'message' => 'Berhasil mengupdate data',
            'data' => $soal,
            'totalNilai' => $totalNilai
        ], 200);
    }

    public function deleteSoal($soalNumber, $userId)
    {
        $modelClass = "App\\Models\\Soal{$soalNumber}";
        if (!class_exists($modelClass)) {
            return response()->json(['message' => 'Soal tidak ditemukan'], 404);
        }
        
        $soal = $modelClass::where('user_id', $userId)->first();
        if (!$soal) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $user = User::findOrFail($userId);
        if ($user->is_verified) {
            return response()->json(['message' => ' Tidak dapat menghapus soal karena user sudah terverifikasi'], 403);
        }
        
        $fileFields = $this->getFileFields($soalNumber);
        foreach ($fileFields as $field) {
            if ($soal->$field && Storage::disk('public')->exists($soal->$field)) {
                Storage::disk('public')->delete($soal->$field);
            }
        }
        
        $soal->delete();
        
        $totalNilai = $this->calculateTotalNilai($userId);
        
        return response()->json([
            'message' => 'Berhasil menghapus soal',
            'totalNilai' => $totalNilai
        ], 200);
    }

    public function deleteField($soalNumber, $userId, $fieldName)
    {
        $modelClass = "App\\Models\\Soal{$soalNumber}";
        if (!class_exists($modelClass)) {
            return response()->json(['message' => 'Soal tidak ditemukan'], 404);
        }
        
        $soal = $modelClass::where('user_id', $userId)->first();
        if (!$soal) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        
        if (!array_key_exists($fieldName, $soal->getAttributes())) {
            return response()->json(['message' => 'Field tidak valid'], 400);
        }

        $user = User::findOrFail($userId);
        if ($user->is_verified) {
            return response()->json(['message' => 'Tidak dapat menghapus field karena user sudah terverifikasi'], 403);
        }
        
        $fileFields = $this->getFileFields($soalNumber);
        if (in_array($fieldName, $fileFields) && $soal->$fieldName && Storage::disk('public')->exists($soal->$fieldName)) {
            Storage::disk('public')->delete($soal->$fieldName);
        }
        
        $soal->$fieldName = null;
        $soal->save();
        
        $this->updateSoalNilai($soalNumber, $userId);
        
        $soal = $modelClass::where('user_id', $userId)->first();
        $totalNilai = $this->calculateTotalNilai($userId);
        
        return response()->json([
            'message' => "Berhasil menghapus field $fieldName",
            'newNilai' => $soal ? $soal->nilai : 0,
            'totalNilai' => $totalNilai
        ], 200);
    }

    public function verifyUser($userId)
    {
        $user = User::findOrFail($userId);
        if ($user->is_verified) {
            return response()->json(['message' => 'User sudah terverifikasi sebelumnya'], 400);
        }

        $totalNilai = $this->calculateTotalNilai($userId);
        $user->is_verified = true;
        $user->nilai = $totalNilai; // Simpan totalNilai ke kolom nilai di tabel users
        $user->save();

        return response()->json([
            'message' => "User {$user->name} telah diverifikasi dengan nilai akhir $totalNilai",
            'data' => $user
        ], 200);
    }

    private function getFileFields($soalNumber)
    {
        $fileFieldsMap = [
            '1' => ['tingkat_pendidikan'],
            '2' => ['tp3', 'lpmp_diknas', 'guru_lain_ipbi_1', 'guru_lain_ipbi_2', 'guru_lain_ipbi_3', 'guru_lain_ipbi_4', 'training_trainer'],
            '3' => ['bahasa_inggris', 'bahasa_lain1', 'bahasa_lain2', 'bahasa_lain3', 'bahasa_lain4'],
            '4' => [
                'independent_org', 'foreign_school_degree',
                'foreign_school_no_degree_1', 'foreign_school_no_degree_2', 'foreign_school_no_degree_3', 'foreign_school_no_degree_4', 'foreign_school_no_degree_5',
                'domestic_school_no_degree_1', 'domestic_school_no_degree_2', 'domestic_school_no_degree_3', 'domestic_school_no_degree_4', 'domestic_school_no_degree_5'
            ],
            '5' => ['sertifikat_1', 'sertifikat_2', 'sertifikat_3'],
            '6' => ['penghargaan_daerah', 'penghargaan_nasional', 'penghargaan_internasional'],
            '7' => [
                'juara_nasional_dpp', 'juara_non_dpp', 'juara_instansi_lain', 'juara_internasional',
                'peserta_lomba_1', 'peserta_lomba_2', 'peserta_lomba_3', 'peserta_lomba_4', 'peserta_lomba_5',
                'juri_lomba_1', 'juri_lomba_2'
            ],
            '8' => [
                'demo_dpp_dpd1', 'demo_dpp_dpd2', 'demo_dpp_dpd3', 'demo_dpp_dpd4', 'demo_dpp_dpd5',
                'non_ipbi1', 'non_ipbi2', 'non_ipbi3', 'non_ipbi4', 'non_ipbi5',
                'international1', 'international2'
            ],
            '9' => ['pembina_demonstrator', 'panitia', 'peserta'],
            '10' => [
                'ipbi_offline1', 'ipbi_offline2', 'ipbi_offline3',
                'ipbi_online1', 'ipbi_online2', 'ipbi_online3',
                'non_ipbi_offline1', 'non_ipbi_offline2', 'non_ipbi_offline3',
                'non_ipbi_online1', 'non_ipbi_online2', 'non_ipbi_online3',
                'international_offline1', 'international_offline2',
                'international_online1', 'international_online2',
                'host_moderator1', 'host_moderator2', 'host_moderator3', 'host_moderator4', 'host_moderator5'
            ],
            '11' => ['penguji_sertifikasi1', 'penguji_sertifikasi2', 'juri_ipbi1', 'juri_ipbi2', 'juri_non_ipbi1', 'juri_non_ipbi2'],
            '12' => ['jabatan'],
            '13' => [
                'guru_tetap', 'asisten_guru', 'owner_sekolah',
                'guru_tidak_tetap_offline', 'guru_tidak_tetap_online',
                'guru_luar_negeri1', 'guru_luar_negeri2'
            ],
            '14' => ['ngajar_online'],
            '15' => ['ikebana_murid', 'ikebana_guru', 'rangkaian_tradisional', 'lainnya'],
            '16' => ['aktif_merangkai', 'owner_berbadan_hukum', 'owner_tanpa_badan_hukum', 'freelance_designer'],
            '17' => [
                'media_cetak_nasional', 'media_cetak_internasional', 'buku_merangkai_bunga',
                'kontributor_buku1', 'kontributor_buku2', 'kontributor_tv1', 'kontributor_tv2'
            ],
        ];
        return $fileFieldsMap[$soalNumber] ?? [];
    }

    public function viewFile($soalNumber, $userId, $fieldName)
    {
        $modelClass = "App\\Models\\Soal{$soalNumber}";
        if (!class_exists($modelClass)) {
            return response()->json(['message' => 'Soal tidak ditemukan'], 404);
        }
        
        $soal = $modelClass::where('user_id', $userId)->first();
        if (!$soal || !$soal->$fieldName) {
            return response()->json(['message' => 'File tidak ditemukan'], 404);
        }
        
        $path = $soal->$fieldName;
        if (!Storage::disk('public')->exists($path)) {
            return response()->json(['message' => 'File tidak ditemukan di storage'], 404);
        }
        
        return response()->file(storage_path('app/public/' . $path));
    }
}