<?php

namespace App\Http\Controllers\Api;

use App\Models\Soal;
use App\Models\Ujian;
use Illuminate\Http\Request;
use App\Models\UjianSoalList;
use App\Http\Controllers\Controller;
use App\Http\Resources\SoalResource;

class UjianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function createUjian(Request $request)
    {
        $soalAngka = Soal::where('kategori','Numeric')->inRandomOrder()->limit(20)->get();
        $soalVerbal = Soal::where('kategori','Verbal')->inRandomOrder()->limit(20)->get();

        $soalLogika = Soal::where('kategori','Logika')->inRandomOrder()->limit(20)->get();
        //craete ujian
        $ujian = Ujian::create([
            'user_id' => $request->user()->id,
        ]);

        foreach($soalAngka as $soal)
        {
            UjianSoalList::create([
                'ujian_id' => $ujian->id,
                'soal_id'  => $soal->id,
            ]);

        }

        foreach($soalVerbal as $soal)
        {
            UjianSoalList::create([
                'ujian_id' => $ujian->id,
                'soal_id'  => $soal->id,
            ]);

        }

        foreach($soalLogika as $soal)
        {
            UjianSoalList::create([
                'ujian_id' => $ujian->id,
                'soal_id'  => $soal->id,
            ]);

        }

        return response()->json([
            'message' => 'Ujian Berhasil Dibuat',
            'data'    => $ujian,
        ]);
    }

    //git list soal by kategori
    public function getListSoalByKategori(Request $request)
    {
        $ujian = Ujian::where('user_id', $request->user()->id)->first();
        $ujianSoalList = UjianSoalList::where('ujian_id', $ujian->id)->get();
        $soalIds = $ujianSoalList->pluck('soal_id');
        //dd($soalIds);
        // $ujianSoalListId = [];
        // foreach($ujianSoalList as $soal){
        //     array_push($ujianSoalListId, $soal->soal_id);
        // }

        $soal = Soal::whereIn('id', $soalIds)->where('kategori', $request->kategori)->get();

        // $soal = Soal::where('kategori', $kategori)->whereNotIn('id', $ujianSoalListId)->inRandomOrder()->first();

        return response()->json([
            'message' => 'Berhasil Mendapatkan Soal',
            // 'data'    => $soal,
            'data'    => SoalResource::collection($soal),
        ]);
    }


    //jawaban soal
    public function jawabSoal(Request $request)
    {
        $validateData = $request->validate([
            'soal_id'  => 'required',
            'jawaban' => 'required'

        ]);


        $ujian = Ujian::where('user_id', $request->user()->id)->first();
        $ujianSoalList = UjianSoalList::where('ujian_id', $ujian->id)->where('soal_id', $validateData['soal_id'])->first();
         $soal = Soal::where('id', $validateData['soal_id'])->first();
        //cek jawaban
         if($soal->kunci == $validateData['jawaban']){
            //$ujianSoalList->kebenaran = true;
            // $ujianSoalList->save();
            $ujianSoalList->update(
                [
                    'kebenaran' =>true
                ]
            );
         } else {
            //$ujianSoalList->kebenaran = false;
            //$ujianSoalList->save();
            $ujianSoalList->update(
                [
                    'kebenaran' =>false
                ]
            );
         }

        return response()->json([
            'message' => 'Berhasil simpan Jawaban',
            // 'data'    => $soal,
            // 'data'    => SoalResource::collection($soal),
            'jawaban'   => $ujianSoalList->kebenaran,
        ]);
    }

}
