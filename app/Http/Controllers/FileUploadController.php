<?php

namespace App\Http\Controllers;

use App\Imports\StudentImport;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class FileUploadController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'filename' => 'mimes:zip'
        ]);

        $path = $request->file('filename')->store($dir = Carbon::now());

        $this->unZipFile($path);

        Excel::import(new StudentImport, $this->getXlsxFile());

        Storage::deleteDirectory($dir);
        Storage::deleteDirectory('unzipped');

        return redirect()->route('home')->with('status', 'Üleslaadimine õnnestus');
    }

    protected function getXlsxFile()
    {
        $files = Storage::files('unzipped');

        foreach ($files as $file) {
            if((new Filesystem())->extension($file) === 'xlsx'){
                return $file;
            }
        }
    }

    protected function unZipFile($path)
    {
        $archive = new \ZipArchive;
        $archive->open(getcwd().'/../storage/app/'.$path);
        $archive->extractTo(storage_path('app/unzipped'));
        $archive->close();
    }
}
