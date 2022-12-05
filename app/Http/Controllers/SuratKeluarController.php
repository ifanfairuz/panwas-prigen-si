<?php

namespace App\Http\Controllers;

use App\Actions\Dropbox\DropboxAction;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use League\Flysystem\FilesystemException;
use League\Flysystem\UnableToWriteFile;

class SuratKeluarController extends Controller
{
    /**
     * @var \App\Actions\Dropbox\DropboxAction
     */
    protected $dropbox;

    public function __construct()
    {
        $this->dropbox = app(DropboxAction::class, ['context' => 'surat_keluar']);
    }

    /**
     * surat keluar page
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $users = SuratKeluar::all();
        return Inertia::render('SuratKeluar/Index', [
            'datas' => $users
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
            $user = SuratKeluar::findOrFail($id);
            return Inertia::render('SuratKeluar/View', [
                'data' => $user->toArray()
            ]);
        } catch (\Exception $e) {
            return redirect()->route('surat.keluar.index');
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
            return Inertia::render('SuratKeluar/Add');
        } catch (\Exception $e) {
            return redirect()->route('surat.keluar.index');
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
            $input = $request->only(['nomor', 'tanggal', 'dari', 'alamat', 'perihal', 'tempat', 'keterangan', 'doc']);
            Validator::make($input, [
                'nomor' => ['required', 'string', 'max:255'],
                'tanggal' => ['required', 'date'],
                'dari' => ['required', 'string', 'max:255'],
                'alamat' => ['required', 'string', 'max:255'],
                'perihal' => ['required', 'string', 'max:255'],
                'tempat' => ['nullable', 'string', 'max:255'],
                'keterangan' => ['nullable', 'string'],
                'doc' => ['nullable', 'sometimes', 'file', 'max:10000', 'mimes:doc,docx,pdf'],
            ])->validate();

            DB::transaction(function () use ($input) {
                return tap(SuratKeluar::create($input), function (SuratKeluar $s) use ($input) {
                    if (@$input['doc']) {
                        $path = $this->dropbox->upload($input['doc']);
                        $s->forceFill(['doc' => $path])->save();
                    }
                });
            });

            return response()->redirectWithBanner('surat.keluar.index', 'Surat disimpan!');
        } catch (ValidationException $exception) {
            DB::rollBack();
            throw $exception;
        } catch (FilesystemException | UnableToWriteFile $exception) {
            DB::rollBack();
            return response()->renderErrorBanner('SuratKeluar/Add', "Gagal unggah dokumen - {$exception->getMessage()}");
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
            return response()->renderErrorBanner('SuratKeluar/Add', "Gagal - {$exception->getMessage()}");
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
            $user = SuratKeluar::findOrFail($id);
            return Inertia::render('SuratKeluar/Edit', [
                'data' => $user->toArray()
            ]);
        } catch (\Exception $e) {
            return redirect()->route('surat.keluar.index');
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
            $input = $request->only(['nomor', 'tanggal', 'dari', 'alamat', 'perihal', 'tempat', 'keterangan', 'doc']);
            Validator::make($input, [
                'nomor' => ['required', 'string', 'max:255'],
                'tanggal' => ['required', 'date'],
                'dari' => ['required', 'string', 'max:255'],
                'alamat' => ['required', 'string', 'max:255'],
                'perihal' => ['required', 'string', 'max:255'],
                'tempat' => ['nullable', 'string', 'max:255'],
                'keterangan' => ['nullable', 'string'],
                'doc' => ['nullable', 'sometimes', 'file', 'max:10000', 'mimes:doc,docx,pdf'],
            ])->validate();
            $model = SuratKeluar::findOrFail($id);

            $update = [
                'nomor' => $input['nomor'],
                'tanggal' => $input['tanggal'],
                'dari' => $input['dari'],
                'alamat' => $input['alamat'],
                'perihal' => $input['perihal'],
                'tempat' => $input['tempat'],
                'keterangan' => $input['keterangan'],
            ];
            DB::transaction(function () use ($model, $update, $input) {
                return tap($model->forceFill($update)->save() ? $model : $model, function (SuratKeluar $s) use ($input) {
                    if (@$input['doc']) {
                        $old_file = $s->doc;
                        $path = $this->dropbox->upload($input['doc']);
                        $saved = $s->forceFill(['doc' => $path])->save();
                        if ($old_file && $saved) DropboxAction::deleteFile($old_file);
                    }
                });
            });

            return response()->redirectWithBanner('surat.keluar.index', 'Surat diupdate!');
        } catch (ValidationException $exception) {
            DB::rollBack();
            throw $exception;
        } catch (FilesystemException | UnableToWriteFile $exception) {
            DB::rollBack();
            return response()->renderErrorBanner('SuratKeluar/Edit', "Gagal unggah dokumen - {$exception->getMessage()}");
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->renderErrorBanner('SuratKeluar/Edit', "Gagal - {$exception->getMessage()}");
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
            $model = SuratKeluar::findOrFail($id);
            DB::transaction(function () use ($model) {
                return tap($model->delete() ? $model : $model, function (SuratKeluar $s) {
                    if ($s->doc) DropboxAction::deleteFile($s->doc);
                });
            });

            return response()->redirectWithBanner('surat.keluar.index', 'Surat dihapus!');
        } catch (ValidationException $exception) {
            DB::rollBack();
            throw $exception;
        } catch (FilesystemException | UnableToWriteFile $exception) {
            DB::rollBack();
            return response()->renderErrorBanner('SuratKeluar/Index', "Gagal hapus dokumen - {$exception->getMessage()}");
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->renderErrorBanner('SuratKeluar/Index', "Gagal - {$exception->getMessage()}");
        }
    }
}
