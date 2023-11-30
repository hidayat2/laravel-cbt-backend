<?php

namespace App\Http\Controllers;

use App\Models\Soal;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreSoalRequest;

class SoalController extends Controller
{
    public function index(Request $request)
    {
        $soals = \App\Models\Soal::paginate(10);
        // $users = DB::table('soals')
        // ->when($request->input('pertanyaan'), function ($query, $name) {
        //     return $query->where('pertanyaan', 'like', '%' . $name . '%');
        // })
        // ->orderBy('id', 'desc')
        // ->paginate(10);
        // $users = DB::table('soals')
        // ->when($request->input('name'), function ($query) use ($request) {
        //     return $query->where('name', 'like', '%' . $request->input('name') . '%');
        // })
        // ->orderBy('id', 'desc')
        // ->paginate(10);
        return view('pages.soals.index', compact('soals'));
    }

    public function create()
    {
        return view('pages.soals.create');

    }

    // public function store(Request $request)
    public function store(StoreSoalRequest $request)
    {

    //dd($request->all());
        $data = $request->all();
        //dd($data);
        \App\Models\Soal::create($data);
        return redirect()->route('soal.index')->with('success','Soal Successfully created');
    }
}
