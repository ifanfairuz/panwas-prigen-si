<?php

namespace App\Http\Controllers;

use App\Actions\Document\DocumentManager;
use App\Actions\Dropbox\DropboxAction;
use App\Models\Petugas;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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
    /**
     * @var \App\Actions\Document\DocumentManager
     */
    protected $document;

    public function __construct()
    {
        $this->dropbox = app(DropboxAction::class, ['context' => 'surat_keluar']);
        $this->document = app(DocumentManager::class, ['context' => 'surat_keluar']);
    }

    /**
     * surat keluar page
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function generate(Request $request)
    {
        try {
            $from = $request->input("from", "add");
            $input = json_decode($request->input("param", "{}"), true);
            $model = new SuratKeluar($input);
            $filename = md5($input['nomor']) . ".docx";
            if (@$input['petugases_id'] && is_array($input['petugases_id'])) {
                $model->petugases_id = $input['petugases_id'];
            }
            $generated = $this->document->generateSuratKeluar($model, $filename);

            return back()->with('flash.generated', $generated);
        } catch (\Exception $exception) {
            return response()->redirectWithBanner("surat.keluar.{$from}", "Gagal generate dokumen - {$exception->getMessage()}");
        }
    }

    /**
     * surat keluar page
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $datas = SuratKeluar::orderBy('nomor')->get();
        return Inertia::render('SuratKeluar/Index', [
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
            $data = SuratKeluar::with(['surat_masuk', 'petugases'])->findOrFail($id);
            return Inertia::render('SuratKeluar/View', [
                'data' => $data->toArray()
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
            $refid = $request->query('refid');
            $type = $request->query('type');

            $references = SuratMasuk::all();
            $reference = SuratMasuk::find($refid);

            $urut = SuratKeluar::getNextUrut();

            return Inertia::render('SuratKeluar/Add', [
                'petugases' => Petugas::all(),
                'references' => $references,
                'type' => $type,
                'reference' => $reference ?? (object) [],
                'urut' => $urut ?? ''
            ]);
        } catch (\Exception $e) {
            throw $e;
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
            $input = $request->all();
            Validator::make($input, [
                'type' => ['required', 'string', 'max:255'],
                'nomor' => ['required', 'string', 'max:255'],
                'tanggal' => ['required', 'date'],
                'tanggal_dinas_start' => ['nullable', 'date'],
                'tanggal_dinas_end' => ['nullable', 'date'],
                'tujuan' => ['required', 'string', 'max:255'],
                'alamat' => ['required', 'string', 'max:255'],
                'perihal' => ['required', 'string', 'max:255'],
                'tempat' => ['nullable', 'string', 'max:255'],
                'keterangan' => ['nullable', 'string'],
                'doc' => ['nullable', 'sometimes', 'file', 'max:10000', 'mimes:doc,docx,pdf'],
                'surat_masuk_id' => [
                    'nullable',
                    'sometimes',
                    Rule::exists('surat_masuk', 'id'),
                ],
                'petugases_id' => [
                    'nullable',
                    'sometimes',
                    'array',
                    Rule::exists('petugas', 'id'),
                ],
            ])->validate();

            DB::transaction(function () use ($input) {
                return tap(SuratKeluar::create($input), function (SuratKeluar $s) use ($input) {
                    if (@$input['petugases_id'] && is_array($input['petugases_id'])) {
                        $s->petugases()->sync($input['petugases_id']);
                    }
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
            $data = SuratKeluar::with(['surat_masuk', 'petugases'])->findOrFail($id);
            $refid = $request->query('refid');
            $type = $request->query('type');

            $references = SuratMasuk::all();
            $reference = SuratMasuk::find($refid);

            $urut = SuratKeluar::getNextUrut();

            return Inertia::render('SuratKeluar/Edit', [
                'petugases' => Petugas::all(),
                'references' => $references,
                'type' => $type,
                'reference' => $reference ?? (object) [],
                'urut' => $urut ?? '',
                'data' => $data->toArray()
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
            $input = $request->all();
            Validator::make($input, [
                'type' => ['required', 'string', 'max:255'],
                'nomor' => ['required', 'string', 'max:255'],
                'tanggal' => ['required', 'date'],
                'tanggal_dinas_start' => ['nullable', 'date'],
                'tanggal_dinas_end' => ['nullable', 'date'],
                'tujuan' => ['required', 'string', 'max:255'],
                'alamat' => ['required', 'string', 'max:255'],
                'perihal' => ['required', 'string', 'max:255'],
                'tempat' => ['nullable', 'string', 'max:255'],
                'keterangan' => ['nullable', 'string'],
                'doc' => ['nullable', 'sometimes', 'file', 'max:10000', 'mimes:doc,docx,pdf'],
                'surat_masuk_id' => [
                    'nullable',
                    'sometimes',
                    Rule::exists('surat_masuk', 'id'),
                ],
                'petugases_id' => [
                    'nullable',
                    'sometimes',
                    'array',
                    Rule::exists('petugas', 'id'),
                ],
            ])->validate();
            $model = SuratKeluar::findOrFail($id);

            $update = [
                'surat_masuk_id' => $input['surat_masuk_id'],
                'type' => $input['type'],
                'nomor' => $input['nomor'],
                'tanggal' => $input['tanggal'],
                'tanggal_dinas_start' => $input['tanggal_dinas_start'],
                'tanggal_dinas_end' => $input['tanggal_dinas_end'],
                'tujuan' => $input['tujuan'],
                'alamat' => $input['alamat'],
                'perihal' => $input['perihal'],
                'tempat' => $input['tempat'],
                'keterangan' => $input['keterangan'],
            ];
            DB::transaction(function () use ($model, $update, $input) {
                return tap($model->forceFill($update)->save() ? $model : $model, function (SuratKeluar $s) use ($input) {
                    if (@$input['petugases_id'] && is_array($input['petugases_id'])) {
                        $s->petugases()->sync($input['petugases_id']);
                    }
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
