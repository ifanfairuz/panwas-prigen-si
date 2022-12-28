<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class PetugasController extends Controller
{
    /**
     * petugas page
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $petugas = Petugas::orderBy('id')->get();
        return Inertia::render('Petugas/Index', [
            'datas' => $petugas
        ]);
    }

    /**
     * view petugas page
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
     */
    public function view(Request $request, $id)
    {
        try {
            $data = Petugas::findOrFail($id);
            return Inertia::render('Petugas/View', [
                'data' => $data->toArray()
            ]);
        } catch (\Exception $e) {
            return redirect()->route('petugas.index');
        }
    }

    /**
     * add petugas page
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function add(Request $request)
    {
        try {
            return Inertia::render('Petugas/Add');
        } catch (\Exception $e) {
            return redirect()->route('petugas.index');
        }
    }

    /**
     * create petugas
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        try {
            $input = $request->all();
            Validator::make($input, [
                'nama' => ['required', 'string', 'max:255'],
                'jabatan' => ['required', 'string', 'max:255'],
                'tempat_lahir' => ['nullable', 'string', 'max:255'],
                'tanggal_lahir' => ['required', 'date'],
                'alamat' => ['required', 'string', 'max:500'],
                'pendidikan' => ['nullable', 'string', 'max:255'],
                'jk' => ['required', 'string', 'max:1'],
                'agama' => ['required', 'string', 'max:255'],
                'pangkat' => ['required', 'string', 'max:255'],
                'tingkat' => ['required', 'string', 'max:255'],
                'nik' => ['required', 'string', 'max:255'],
                'nip' => ['nullable', 'string', 'max:255'],
                'npwp' => ['nullable', 'string', 'max:255'],
                'no_hp' => ['nullable', 'string', 'max:255'],
                'email' => ['nullable', 'string', 'max:255'],
            ])->validate();
            
            Petugas::create([
                'nama' => $input['nama'],
                'jabatan' => $input['jabatan'],
                'tempat_lahir' => $input['tempat_lahir'],
                'tanggal_lahir' => $input['tanggal_lahir'],
                'alamat' => $input['alamat'],
                'pendidikan' => $input['pendidikan'],
                'jk' => $input['jk'],
                'agama' => $input['agama'],
                'pangkat' => $input['pangkat'],
                'tingkat' => $input['tingkat'],
                'nik' => $input['nik'],
                'nip' => $input['nip'],
                'npwp' => $input['npwp'],
                'no_hp' => $input['no_hp'],
                'email' => $input['email'],
            ]);

            return response()->redirectWithBanner('petugas.index', 'Petugas disimpan!');
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            return response()->renderErrorBanner('Petugas/Add', "Gagal - {$exception->getMessage()}");
        }
    }

    /**
     * edit petugas page
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $id)
    {
        try {
            $petugas = Petugas::findOrFail($id);
            return Inertia::render('Petugas/Edit', [
                'data' => $petugas->toArray()
            ]);
        } catch (\Exception $e) {
            return redirect()->route('petugas.index');
        }
    }

    /**
     * update petugas
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function update(Request $request, $id)
    {
        try {
            $input = $request->all();
            $petugas = Petugas::findOrFail($id);
            Validator::make($input, [
                'nama' => ['required', 'string', 'max:255'],
                'jabatan' => ['required', 'string', 'max:255'],
                'tempat_lahir' => ['nullable', 'string', 'max:255'],
                'tanggal_lahir' => ['required', 'date'],
                'alamat' => ['required', 'string', 'max:500'],
                'pendidikan' => ['nullable', 'string', 'max:255'],
                'jk' => ['required', 'string', 'max:1'],
                'agama' => ['required', 'string', 'max:255'],
                'pangkat' => ['required', 'string', 'max:255'],
                'tingkat' => ['required', 'string', 'max:255'],
                'nik' => ['required', 'string', 'max:255'],
                'nip' => ['nullable', 'string', 'max:255'],
                'npwp' => ['nullable', 'string', 'max:255'],
                'no_hp' => ['nullable', 'string', 'max:255'],
                'email' => ['nullable', 'string', 'max:255'],
            ])->validate();
            
            $petugas->forceFill([
                'nama' => $input['nama'],
                'jabatan' => $input['jabatan'],
                'tempat_lahir' => $input['tempat_lahir'],
                'tanggal_lahir' => $input['tanggal_lahir'],
                'alamat' => $input['alamat'],
                'pendidikan' => $input['pendidikan'],
                'jk' => $input['jk'],
                'agama' => $input['agama'],
                'pangkat' => $input['pangkat'],
                'tingkat' => $input['tingkat'],
                'nik' => $input['nik'],
                'nip' => $input['nip'],
                'npwp' => $input['npwp'],
                'no_hp' => $input['no_hp'],
                'email' => $input['email'],
            ])->save();

            return response()->redirectWithBanner('petugas.index', 'Petugas diupdate!');
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            return response()->renderErrorBanner('Petugas/Edit', "Gagal - {$exception->getMessage()}");
        }
    }

    /**
     * delete petugas
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request, $id)
    {
        $petugas = Petugas::find($id);
        $petugas && $petugas->delete();
        session()->flash('flash.banner', 'Petugas dihapus!');
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('petugas.index');
    }
}
