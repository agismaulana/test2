<?php

// package
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
// models
use App\Models\Pegawai;
use App\Models\Kasbon;
// Jobs
use App\Jobs\KasbonJob;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Endpoint
Route::get('/pegawai', function() {
    // Data
    $data_pagination = Pegawai::simplePaginate(10);
    $data_all = Pegawai::all();
    $data_per_data = [];

    // Foreach Data
    foreach($data_all as $datas) {
        $tanggal_masuk = $datas['tanggal_masuk'];
        $tanggal_masuk = explode('-', $tanggal_masuk);
        $tanggal_masuk = $tanggal_masuk[2].'/'.$tanggal_masuk[1].'/'.$tanggal_masuk[0];

        $pegawai = [
            'nama' => strtoupper($datas['nama']),
            'tanggal_masuk' => $tanggal_masuk,
            'total_gaji' => number_format($datas['total_gaji'], 0, '.', ''),
        ];
        array_push($data_per_data, $pegawai);
    }

    return response()->json(['error'=>false, 'format_pagination' => $data_pagination, 'format_per_data' => $data_per_data], 200);
})->name('pegawai.index');

Route::post('/pegawai', function(Request $request) {
    $validator = Validator::make($request->all(), [
        'nama' => 'required|max:10',
        'tanggal_masuk' => 'required|date',
        'total_gaji' => 'required',
    ]);

    if($validator->fails()) {
        return redirect()->route('pegawai.index')->with(['error' => true, 'message' => 'Something Went Error!!']);
    };

    Pegawai::create($request->all());
    return redirect()->route('pegawai.index')->with(['error' => false, 'message' => 'Successfully Created!!!']);
})->name('pegawai.store');

Route::get('/kasbon', function() {
    $data_kasbon = Kasbon::with('pegawai')->whereNull('tanggal_disetujui')->get();
    $data_pagination = Kasbon::with('pegawai')->whereNull('tanggal_disetujui')->paginate(10);
    $data_per_data = [];

    foreach($data_kasbon as $datas) {
        $tanggal_ajukan = $datas['tanggal_diajukan'];
        $tanggal_ajukan = explode('-', $datas['tanggal_diajukan']);
        $year_now = 2021;
        $month_now = 11;

        if($year_now == $tanggal_ajukan[0] && $month_now == $tanggal_ajukan[1]) {
            $kasbon = [
                'tanggal_diajukan' => $tanggal_ajukan[2].'/'.$tanggal_ajukan[1].'/'.$tanggal_ajukan[0],
                'tanggal_disetujui' => $datas->tanggal_disetujui,
                'nama_pegawai' => $datas->pegawai->nama_pegawai,
                'total_kasbon' => number_format($datas['total_kasbon'], 0, '.', ''),
            ];

            array_push($data_per_data, $kasbon);            
        }
    }

    return response()->json(['error'=>false, 'format_pagination' => $data_pagination, 'format_per_data' => $data_per_data], 200);

})->name('kasbon.index');

Route::patch('/kasbon/setujui/{id}', function(Request $request) {
    $validator = Validator::make($request->all(), [
        'id' => 'required',
    ]);

    if($validator->fails()) {
        return redirect()->route('kasbon.index')->with(['error' => true, 'message' => 'Something Went Error!!']);
    }

    Kasbon::where('id', $request->id)->update(['tanggal_disetujui' => date('Y-m-d')]);

    return redirect()->route('kasbon.index')->with(['error' => false,'message'=>'successfully Updated!']);
})->name('kasbon.update');

Route::post('/kasbon/setujui-massal', function(Request $request) {
    $validator = Validator::make($request->all(), [
        'id' => 'required|array',
        'id.*' => 'required|distinct',
    ]);

    if($validator->fails()) {
        return redirect()->route('kasbon.index')->with(['error' => true, 'message' => 'Something Went Error!!']);   
    }

    for($i = 0; $i < count($request->id); $i++) {
        dispatch(new KasbonJob($request->id[$i]));
    }

    return redirect()->route('kasbon.index')->with(['error' => false,'message'=>'successfully Updated!']);
})->name('kasbon.update_massal');