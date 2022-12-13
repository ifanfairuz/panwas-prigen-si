<?php

namespace App\Http\Controllers;

use App\Models\KomponenKeuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class KomponenKeuanganController extends Controller
{
    /**
     * surat keluar page
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $datas = KomponenKeuangan::orderBy('kode')->get();
        return Inertia::render('KomponenKeuangan/Index', [
            'datas' => $datas
        ]);
    }

    /**
     * view surat keluar page
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
     */
    public function view(Request $request, $id)
    {
        try {
            $data = KomponenKeuangan::with(['surat_masuk', 'petugases'])->findOrFail($id);
            return Inertia::render('KomponenKeuangan/View', [
                'data' => $data->toArray()
            ]);
        } catch (\Exception $e) {
            return redirect()->route('keuangan.komponen.index');
        }
    }

    /**
     * add surat keluar page
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function add(Request $request)
    {
        try {
            return Inertia::render('KomponenKeuangan/Add');
        } catch (\Exception $e) {
            throw $e;
            return redirect()->route('keuangan.komponen.index');
        }
    }

    /**
     * create surat keluar
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        try {
            $input = $request->all();
            Validator::make($input, [
                'kode' => ['required', 'string', 'max:30', Rule::unique('komponen_keuangans', 'kode')],
                'label' => ['required', 'string', 'max:255'],
                'keterangan' => ['nullable', 'sometimes', 'string'],
            ])->validate();

            KomponenKeuangan::create($input);

            return response()->redirectWithBanner('keuangan.komponen.index', 'Komponen Keuangan disimpan!');
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            dd($exception);
            return response()->renderErrorBanner('KomponenKeuangan/Add', "Gagal - {$exception->getMessage()}");
        }
    }

    /**
     * edit surat keluar page
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $id)
    {
        try {
            $data = KomponenKeuangan::findOrFail($id);

            return Inertia::render('KomponenKeuangan/Edit', [
                'data' => $data->toArray()
            ]);
        } catch (\Exception $e) {
            return redirect()->route('keuangan.komponen.index');
        }
    }

    /**
     * update surat keluar
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function update(Request $request, $id)
    {
        try {
            $input = $request->all();
            Validator::make($input, [
                'kode' => ['required', 'string', 'max:30', Rule::unique('komponen_keuangans', 'kode')->ignore($id, 'kode')],
                'label' => ['required', 'string', 'max:255'],
                'keterangan' => ['nullable', 'sometimes', 'string'],
            ])->validate();
            $model = KomponenKeuangan::findOrFail($id);

            $update = [
                'kode' => $input['kode'],
                'label' => $input['label'],
                'keterangan' => $input['keterangan'],
            ];
            $model->forceFill($update)->save();

            return response()->redirectWithBanner('keuangan.komponen.index', 'Komponen Keuangan diupdate!');
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            return response()->renderErrorBanner('KomponenKeuangan/Edit', "Gagal - {$exception->getMessage()}");
        }
    }

    /**
     * delete surat keluar
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request, $id)
    {
        try {
            $model = KomponenKeuangan::findOrFail($id);
            $model->delete();

            return response()->redirectWithBanner('keuangan.komponen.index', 'Komponen Keuangan dihapus!');
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            return response()->renderErrorBanner('KomponenKeuangan/Index', "Gagal - {$exception->getMessage()}");
        }
    }
}
