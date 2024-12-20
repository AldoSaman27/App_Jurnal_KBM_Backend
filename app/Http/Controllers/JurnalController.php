<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class JurnalController extends Controller
{
    public function __construct() {
        $this->middleware("auth:sanctum", ["except"]);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Jurnal $jurnal, $nip, $bulan, $tahun)
    {
        // Buat instance Carbon dengan tanggal awal bulan
        $tanggal = Carbon::createFromDate($tahun, $bulan, 1);
        // Ambil jumlah hari dalam bulan ini
        $jumlahHari = $tanggal->daysInMonth;
    
        // Format tanggal mulai dan tanggal akhir dalam format Y-m-d H:i:s
        $startDate = Carbon::createFromFormat('Y-m-d', "$tahun-$bulan-01")->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', "$tahun-$bulan-$jumlahHari")->endOfDay();
        
        // Query untuk mendapatkan data Jurnal berdasarkan rentang tanggal
        $jurnal = Jurnal::where("nip", $nip)
                        ->whereBetween("hari_tanggal", [$startDate, $endDate])
                        ->orderBy("hari_tanggal", "asc")
                        ->get();
    
        // Mengembalikan respons JSON dengan data Jurnal
        return response()->json([
            "message" => "Jurnal index success",
            "jurnal" => $jurnal,
        ]);
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "nip" => "required|string",
            "hari_tanggal" => "required|string",
            "jam_pembelajaran" => "required|string",
            "kelas" => "required|string",
            "kehadiran" => "required|string",
            "uraian_kegiatan" => "required|string",
            "materi" => "nullable|string",
            "tujuan_pembelajaran" => "nullable|string",
            "foto_kegiatan" => "required|image",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "Invalid field!",
                "errors" => $validator->errors(),
            ], 422);
        }
        
        $user = User::where("nip", $request->nip)->first();
        if (!$user) return response()->json(["message" => "NIP ($request->nip) tidak di temukan!"], 404);

        if ($request->hasFile('foto_kegiatan')) {
            $file = $request->file('foto_kegiatan');
            $extension = $file->getClientOriginalExtension();
            @$fileName = date('Ymd') . '_' . uniqid() . '.' . $extension;
            $file->storeAs('activity-photos', $fileName);
        }
        
        $jurnal = Jurnal::create([
            "nip" => $request->nip,
            "hari_tanggal" => $request->hari_tanggal,
            "jam_pembelajaran" => $request->jam_pembelajaran,
            "kelas" => $request->kelas,
            "kehadiran" => $request->kehadiran,
            "uraian_kegiatan" => $request->uraian_kegiatan,
            "materi" => $request->materi,
            "tujuan_pembelajaran" => $request->tujuan_pembelajaran,
            "foto_kegiatan" => $fileName,
        ]);

        return response()->json([
            "message" => "Jurnal store success",
            "jurnal" => $jurnal,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Jurnal $jurnal, $id)
    {
        $jurnal = Jurnal::where("id", $id)->first();
        if (!$jurnal) return response()->json(["message" => "Jurnal (id: $id) tidak di temukan!"], 404);

        return response()->json([
            "message" => "Jurnal show success",
            "jurnal" => $jurnal,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jurnal $jurnal)
    {
        // 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jurnal $jurnal, $id)
    {
        $validator = Validator::make($request->all(), [
            "nip" => "required|string",
            "hari_tanggal" => "required|string",
            "jam_pembelajaran" => "required|string",
            "kelas" => "required|string",
            "kehadiran" => "required|string",
            "uraian_kegiatan" => "required|string",
            "materi" => "nullable|string",
            "tujuan_pembelajaran" => "nullable|string",
            "foto_kegiatan" => "nullable|image",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "Invalid field!",
                "error" => $validator->errors(), 
            ], 422);
        }

        $jurnal = Jurnal::where("id", $id)->first();
        if (!$jurnal) return response()->json(["message" => "Jurnal (id: $id) tidak di temukan!"], 404);

        if ($request->hasFile('foto_kegiatan')) {
            $file = $request->file('foto_kegiatan');
            $extension = $file->getClientOriginalExtension();
            @$fileName = date('Ymd') . '_' . uniqid() . '.' . $extension;
            $file->storeAs('activity-photos', $fileName);

            $jurnal->update(["foto_kegiatan" => $fileName]);
        }

        $jurnal->update([
            "nip" => $request->nip,
            "hari_tanggal" => $request->hari_tanggal,
            "jam_pembelajaran" => $request->jam_pembelajaran,
            "kelas" => $request->kelas,
            "kehadiran" => $request->kehadiran,
            "uraian_kegiatan" => $request->uraian_kegiatan,
            "materi" => $request->materi,
            "tujuan_pembelajaran" => $request->tujuan_pembelajaran,
        ]);

        return response()->json([
            "message" => "Jurnal update success",
            "jurnal" => $jurnal,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jurnal $jurnal, $id)
    {
        $jurnal = Jurnal::where("id", $id)->delete();
        if ($jurnal) {
            return response()->json(["message" => "Jurnal destroy success"]);
        } else {
            return response()->json(["message" => "Jurnal (id: $id) tidak di temukan!"], 404);
        }
    }

    /**
     * Download the specified resource from storage.
     */
    public function download($nip, $bulan, $tahun, $semester, $tahun_pembelajaran)
    {
        // Buat instance Carbon dengan tanggal awal bulan
        $tanggal = Carbon::createFromDate($tahun, $bulan, 1);
        // Ambil jumlah hari dalam bulan ini
        $jumlahHari = $tanggal->daysInMonth;
    
        // Format tanggal mulai dan tanggal akhir dalam format Y-m-d H:i:s
        $startDate = Carbon::createFromFormat('Y-m-d', "$tahun-$bulan-01")->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', "$tahun-$bulan-$jumlahHari")->endOfDay();
        
        // Query untuk mendapatkan data Jurnal berdasarkan rentang tanggal
        $jurnal = Jurnal::where("nip", $nip)
                        ->whereBetween("hari_tanggal", [$startDate, $endDate])
                        ->orderBy("hari_tanggal", "asc")
                        ->get();

        $user = User::where("nip", $nip)->first();

        $formattedDate = Carbon::createFromFormat('Y-m-d', "$tahun-$bulan-01")->translatedFormat('F Y');

        $pdf = Pdf::loadView('jurnal', ['user' => $user, 'jurnal' => $jurnal, 'semester' => $semester, 'tahun_pembelajaran' => $tahun_pembelajaran]);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('Jurnal' . ' ' . $formattedDate . '.pdf');
    }
}
