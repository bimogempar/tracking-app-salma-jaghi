<?php

namespace App\Http\Controllers;

use App\Models\data_surat;
use Illuminate\Http\Request;

use DataTables;

class DataSuratController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->has('cari')) {
            $suratall = data_surat::where('alamat_penerima', 'LIKE', '%' . $request->cari . '%')->get();
        } else {
            $suratall = data_surat::all();
        }
        $suratall = data_surat::latest()->paginate(10);
        //return view('layout.index', [
        //'data_surat' => \App\Models\data_surat::latest()->paginate(5)]);
        return view('layout.index', compact('suratall'));
    }

    public function getSurats()
    {
        $surat = data_surat::select('data_surats.*');
        return Datatables::of($surat)
            ->addColumn('bukti', function ($s) {
                $url = '/storage/' . $s->bukti;
                $idimg = $s->id;
                return '<img src="' . $url . '" border="0" width="100" class="img-rounded" data-toggle="modal"
                data-target="#exampleModal' . $idimg . '" align="center" />';
            })
            ->addColumn('file', function ($s) {
                $url = '/storage/' . $s->file;
                return '<a target="blank" href="' . $url . '" class="btn btn-sm btn-primary">Download</a>';
            })
            ->rawColumns(['bukti', 'file'])
            ->make(true);
        // ->toJson();
    }

    public function getSuratKeluar()
    {
        $surat = data_surat::select('data_surats.*');
        return Datatables::of($surat)
            ->addColumn('bukti', function ($s) {
                $url = '/storage/' . $s->bukti;
                return '<img src="' . $url . '" border="0" width="100" class="popup img-rounded" align="center" />';
            })
            ->addColumn('file', function ($s) {
                $url = '/storage/' . $s->file;
                return '<a target="blank" href="' . $url . '" class="btn btn-sm btn-primary">View</a>';
            })
            ->addColumn('aksi', function ($s) {
                $url = $s->id;
                return '
                <div class="d-flex flex-row">
                    <a href="/admin/data-surat/' . $url . '/verifikasi" class="btn btn-sm btn-success">Verifikasi</a>
                    <a href="/admin/data-surat/' . $url . '/edit" class="btn btn-sm btn-primary ml-3">Edit</a>
                    <form action="/admin/data_surat/' . $url . '/delete" method="POST">
                    ' . csrf_field() . '
                    ' . method_field("DELETE") . '
                        <button class="btn btn-danger ml-3" type="submit" onclick="return confirm(\'Are You Sure Want to Delete?\')">Hapus</button>
                    </form>
                </div>
                ';
            })
            ->rawColumns(['bukti', 'file', 'aksi'])
            ->make(true);
    }

    public function table(Request $request)
    {
        if ($request->has('cari')) {
            $data_surat = \App\Models\data_surat::where('alamat_penerima', 'LIKE', '%' . $request->cari . '%')->get();
        } else {
            $data_surat = \App\Models\data_surat::all();
        }
        return view('layout.table.table_data_surat_keluar', [
            'data_surat' => \App\Models\data_surat::latest()->paginate(5)
        ]);
    }

    public function create(Request $request)
    {
        return view('layout.form.form_input_data_surat');
    }

    public function store(Request $request)
    {
        $attr = request()->validate([
            'no_agenda' => 'required',
            'no_surat' => 'required',
            'tanggal' => 'required',
            'alamat_penerima' => 'required',
            'perihal' => 'required',
            'file' => 'file|mimes:pdf',
            'arsip' => 'required',
        ]);

        $attr = $request->all();

        if ($request->file()) {
            $fileName = time() . '_' . $request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('files/datafiles', $fileName);
        }
        $file = request()->file('file') ? request()->file('file')->storeAs("files/datafiles", $fileName, 'public') : null;
        $attr['file'] = $file;
        $attr['name'] = $fileName;
        $attr['file_path'] = $filePath;

        //create new post
        \App\Models\data_surat::create($attr);

        session()->flash('success', 'Data Surat berhasil di upload');

        return redirect('admin/data-surat');
    }

    public function edit(data_surat $data_surat)
    {
        return view('layout.form.form_edit_data_surat', compact('data_surat'));
    }

    public function update(data_surat $data_surat, request $request)
    {
        //validate the field
        $attr = request()->validate([
            'no_agenda' => 'required',
            'no_surat' => 'required',
            'tanggal' => 'required',
            'alamat_penerima' => 'required',
            'perihal' => 'required',
            'file' => 'file|mimes:pdf',
        ]);

        if (request()->file('file')) {
            \Storage::delete($data_surat->file);
            $fileName = time() . '_' . $request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('files/datafiles', $fileName);

            $file = request()->file('file') ? request()->file('file')->storeAs("files/datafiles", $fileName, 'public') : null;
            $attr['file'] = $file;
            $attr['name'] = $fileName;
            $attr['file_path'] = $filePath;
        } else {
            $file = $data_surat->file;
        }

        $attr['file'] = $file;

        $data_surat->update($attr);

        session()->flash('success', 'Data Surat berhasil diedit');

        return redirect('admin/data-surat');
    }

    // public function download($id){
    //     if (request()->file('file')) {
    //         $file = request()->file('file')->get("files/datafiles");
    //         \Storage::download($data_surat->file);
    //     }
    // }

    public function destroy(data_surat $data_surat)
    {
        \Storage::delete($data_surat->file);
        $data_surat->delete();
        session()->flash('success', 'Data Surat berhasil dihapus');
        return redirect('admin/data-surat');
    }
}
