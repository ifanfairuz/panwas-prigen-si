<?php

namespace App\Http\Controllers;

use App\Actions\Dropbox\DropboxAction;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use League\Flysystem\FilesystemException;
use League\Flysystem\UnableToWriteFile;

class SuratMasukController extends Controller
{
    /**
     * @var \App\Actions\Dropbox\DropboxAction
     */
    protected $dropbox;

    public function __construct()
    {
        $this->dropbox = app(DropboxAction::class, ['context' => 'surat_masuk']);
    }

    /**
     * surat masuk page
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $users = SuratMasuk::all();
        return Inertia::render('SuratMasuk/Index', [
            'datas' => $users
        ]);
    }

    /**
     * view surat masuk page
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
     */
    public function view(Request $request, $id)
    {
        try {
            $user = SuratMasuk::findOrFail($id);
            return Inertia::render('SuratMasuk/View', [
                'data' => $user->toArray()
            ]);
        } catch (\Exception $e) {
            return redirect()->route('surat.masuk.index');
        }
    }

    /**
     * add surat masuk page
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function add(Request $request)
    {
        try {
            return Inertia::render('SuratMasuk/Add');
        } catch (\Exception $e) {
            return redirect()->route('surat.masuk.index');
        }
    }

    /**
     * create surat masuk
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
                return tap(SuratMasuk::create($input), function (SuratMasuk $s) use ($input) {
                    if (@$input['doc']) {
                        $path = $this->dropbox->upload($input['doc']);
                        $s->forceFill(['doc' => $path])->save();
                    }
                });
            });

            return response()->redirectWithBanner('surat.masuk.index', 'Surat disimpan!');
        } catch (ValidationException $exception) {
            DB::rollBack();
            throw $exception;
        } catch (FilesystemException | UnableToWriteFile $exception) {
            DB::rollBack();
            return response()->renderErrorBanner('SuratMasuk/Add', "Gagal unggah dokumen - {$exception->getMessage()}");
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
            return response()->renderErrorBanner('SuratMasuk/Add', "Gagal - {$exception->getMessage()}");
        }
    }

    /**
     * edit surat masuk page
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $id)
    {
        try {
            $user = SuratMasuk::findOrFail($id);
            return Inertia::render('SuratMasuk/Edit', [
                'data' => $user->toArray()
            ]);
        } catch (\Exception $e) {
            return redirect()->route('surat.masuk.index');
        }
    }

    /**
     * update surat masuk
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
            $model = SuratMasuk::findOrFail($id);

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
                return tap($model->forceFill($update)->save() ? $model : $model, function (SuratMasuk $s) use ($input) {
                    if (@$input['doc']) {
                        $old_file = $s->doc;
                        $path = $this->dropbox->upload($input['doc']);
                        $saved = $s->forceFill(['doc' => $path])->save();
                        if ($old_file && $saved) DropboxAction::deleteFile($old_file);
                    }
                });
            });

            return response()->redirectWithBanner('surat.masuk.index', 'Surat diupdate!');
        } catch (ValidationException $exception) {
            DB::rollBack();
            throw $exception;
        } catch (FilesystemException | UnableToWriteFile $exception) {
            DB::rollBack();
            return response()->renderErrorBanner('SuratMasuk/Edit', "Gagal unggah dokumen - {$exception->getMessage()}");
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->renderErrorBanner('SuratMasuk/Edit', "Gagal - {$exception->getMessage()}");
        }
    }

    /**
     * delete surat masuk
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request, $id)
    {
        try {
            $model = SuratMasuk::findOrFail($id);
            DB::transaction(function () use ($model) {
                return tap($model->delete() ? $model : $model, function (SuratMasuk $s) {
                    if ($s->doc) DropboxAction::deleteFile($s->doc);
                });
            });

            return response()->redirectWithBanner('surat.masuk.index', 'Surat dihapus!');
        } catch (ValidationException $exception) {
            DB::rollBack();
            throw $exception;
        } catch (FilesystemException | UnableToWriteFile $exception) {
            DB::rollBack();
            return response()->renderErrorBanner('SuratMasuk/Index', "Gagal hapus dokumen - {$exception->getMessage()}");
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->renderErrorBanner('SuratMasuk/Index', "Gagal - {$exception->getMessage()}");
        }
    }
}
