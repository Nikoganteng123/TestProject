<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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

    public function users()
    {
        $users = User::all(['id', 'name', 'email', 'created_at']);

        return response()->json([
            'data' => $users
        ], 200);
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

        $totalNilai = 0;
        foreach ($soals as $soal) {
            if ($soal && isset($soal->nilai)) {
                $totalNilai += $soal->nilai;
            }
        }

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
            $soal->update(['nilai' => $request->nilai]);
        }

        return response()->json([
            'message' => 'Berhasil mengupdate data',
            'data' => $soal
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

        $fileFields = $this->getFileFields($soalNumber);

        foreach ($fileFields as $field) {
            if ($soal->$field && Storage::disk('public')->exists($soal->$field)) {
                Storage::disk('public')->delete($soal->$field);
            }
        }

        $soal->delete();

        return response()->json(['message' => 'Berhasil menghapus data'], 200);
    }

    private function getFileFields($soalNumber)
    {
        // Sementara, isi berdasarkan yang kamu berikan + asumsi
        $fileFieldsMap = [
            '1' => ['tingkat_pendidikan'],
            '2' => ['tp3', 'lpmp_diknas', 'guru_lain_ipbi_1', 'guru_lain_ipbi_2', 'guru_lain_ipbi_3', 'guru_lain_ipbi_4', 'training_trainer'],
            '3' => ['bahasa_inggris', 'bahasa_lain1', 'bahasa_lain2', 'bahasa_lain3', 'bahasa_lain4'], // Isi dengan kolom file untuk soal3
            '4' => [
                'independent_org',
                'foreign_school_degree',
                'foreign_school_no_degree_1',
                'foreign_school_no_degree_2',
                'foreign_school_no_degree_3',
                'foreign_school_no_degree_4',
                'foreign_school_no_degree_5',
                'domestic_school_no_degree_1',
                'domestic_school_no_degree_2',
                'domestic_school_no_degree_3',
                'domestic_school_no_degree_4',
                'domestic_school_no_degree_5'
            ], // Isi dengan kolom file untuk soal4
            '5' => ['sertifikat_1', 'sertifikat_2', 'sertifikat_3'], // Isi dengan kolom file untuk soal5
            '6' => [
                'penghargaan_daerah',
                'penghargaan_nasional',
                'penghargaan_internasional'
            ], // Isi dengan kolom file untuk soal6
            '7' => [
                'juara_nasional_dpp',
                'juara_non_dpp',
                'juara_instansi_lain',
                'juara_internasional',
                'peserta_lomba_1',
                'peserta_lomba_2',
                'peserta_lomba_3',
                'peserta_lomba_4',
                'peserta_lomba_5',
                'juri_lomba_1',
                'juri_lomba_2'
            ],
            '8' => [
                'demo_dpp_dpd1',
                'demo_dpp_dpd2',
                'demo_dpp_dpd3',
                'demo_dpp_dpd4',
                'demo_dpp_dpd5',
                'non_ipbi1',
                'non_ipbi2',
                'non_ipbi3',
                'non_ipbi4',
                'non_ipbi5',
                'international1',
                'international2'
            ], // Isi dengan kolom file untuk soal8
            '9' => [
                'pembina_demonstrator',
                'panitia',
                'peserta'
            ], // Isi dengan kolom file untuk soal9
            '10' => [
                'ipbi_offline1',
                'ipbi_offline2',
                'ipbi_offline3',
                'ipbi_online1',
                'ipbi_online2',
                'ipbi_online3',
                'non_ipbi_offline1',
                'non_ipbi_offline2',
                'non_ipbi_offline3',
                'non_ipbi_online1',
                'non_ipbi_online2',
                'non_ipbi_online3',
                'international_offline1',
                'international_offline2',
                'international_online1',
                'international_online2',
                'host_moderator1',
                'host_moderator2',
                'host_moderator3',
                'host_moderator4',
                'host_moderator5'
            ], // Isi dengan kolom file untuk soal10
            '11' => [
                'penguji_sertifikasi1',
                'penguji_sertifikasi2',
                'juri_ipbi1',
                'juri_ipbi2',
                'juri_non_ipbi1',
                'juri_non_ipbi2'
            ], // Isi dengan kolom file untuk soal11
            '12' => ['jabatan'], // Isi dengan kolom file untuk soal12
            '13' => [
                'guru_tetap',
                'asisten_guru',
                'owner_sekolah',
                'guru_tidak_tetap_offline',
                'guru_tidak_tetap_online',
                'guru_luar_negeri1',
                'guru_luar_negeri2'
            ], // Isi dengan kolom file untuk soal13
            '14' => ['ngajar_online'], // Isi dengan kolom file untuk soal14
            '15' => [
                'ikebana_murid',
                'ikebana_guru',
                'rangkaian_tradisional',
                'lainnya'
            ], // Isi dengan kolom file untuk soal15
            '16' => [
                'aktif_merangkai',
                'owner_berbadan_hukum',
                'owner_tanpa_badan_hukum',
                'freelance_designer'
            ], // Isi dengan kolom file untuk soal16
            '17' => [
                'media_cetak_nasional',
                'media_cetak_internasional',
                'buku_merangkai_bunga',
                'kontributor_buku1',
                'kontributor_buku2',
                'kontributor_tv1',
                'kontributor_tv2'
            ], // Isi dengan kolom file untuk soal17
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

        // Periksa apakah field ada dan merupakan file
        if (!array_key_exists($fieldName, $soal->getAttributes())) {
            return response()->json(['message' => 'Field tidak valid'], 400);
        }

        // Jika field adalah file, hapus dari storage
        $fileFields = $this->getFileFields($soalNumber);
        if (in_array($fieldName, $fileFields) && $soal->$fieldName && Storage::disk('public')->exists($soal->$fieldName)) {
            Storage::disk('public')->delete($soal->$fieldName);
        }

        // Set field ke null di database
        $soal->$fieldName = null;
        $soal->save();

        return response()->json(['message' => "Berhasil menghapus field $fieldName"], 200);
    }
}