<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;

class FileController extends Controller
{
    protected $request;
    protected $imagePath;
    protected $videoPath;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $year = Carbon::now()->year;
        $month = Carbon::now()->month;
        $day = Carbon::now()->day;

        $this->imagePath = "uploads/images/{$year}/{$month}/{$day}";
        $this->videoPath = "uploads/video/{$year}/{$month}/{$day}";

    }

    public function fileFinalPath($status = 'images')
    {
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;
        $day = Carbon::now()->day;

        $filesystem = new Filesystem();
        $root_path = $_SERVER['DOCUMENT_ROOT'];

        $root_Path =  "{$root_path}/uploads";
        $file_path =  "{$status}/{$year}/{$month}/{$day}/";


        if(!$filesystem->exists("{$root_Path}/{$status}")){
            $filesystem->makeDirectory("{$root_Path}/{$status}");
        }
        if(!$filesystem->exists("{$root_Path}/{$status}/{$year}")){
            $filesystem->makeDirectory("{$root_Path}/{$status}/{$year}");
        }
        if(!$filesystem->exists("{$root_Path}/{$status}/{$year}/{$month}")){
            $filesystem->makeDirectory("{$root_Path}/{$status}/{$year}/{$month}");
        }
        if(!$filesystem->exists("{$root_Path}/{$status}/{$year}/{$month}/{$day}")){
            $filesystem->makeDirectory("{$root_Path}/{$status}/{$year}/{$month}/{$day}");
        }


        //Storage::makeDirectory("{$root_Path}/{$status}");

        $imageServer = '/uploads';

        return [
            'root_path' => $root_Path,
            'file_path' => $file_path,
            'path' => "{$root_Path}/{$file_path}",
            'url' => "{$imageServer}/{$file_path}"
        ];

    }

    public function image(Filesystem $filesystem){

        // Validation Data
        $this->validate($this->request,[
            //'image' => 'required|mimes:gif,jpeg,jpg,bmp,png',
            'image' => 'required',
        ]);



        $Path = $this->fileFinalPath('images');

        $root_Path =  $Path['root_path'];
        $imagePath =  $Path['file_path'];
        $path_blank =  $Path['path'];
        $imageServer = $Path['url'];

        $file = $this->request->file('image');
        $fileName = $file->getClientOriginalName();

        $path = "{$path_blank}/{$fileName}";


        if($filesystem->exists($path)){
            $fileName = Carbon::now()->timestamp . "-{$fileName}";
        }

        $file->move($path_blank, $fileName);

        return url("{$imageServer}{$fileName}");



    }

    public function imageGallery(Filesystem $filesystem){

        // Validation Data
        $this->validate($this->request,[
            'image_gallery' => 'required',
        ]);



        $Path = $this->fileFinalPath('images');

        $root_Path =  $Path['root_path'];
        $imagePath =  $Path['file_path'];
        $path_blank =  $Path['path'];
        $imageServer = $Path['url'];

        $file = $this->request->file('image_gallery');
        $fileName = $file->getClientOriginalName();

        $path = "{$path_blank}/{$fileName}";


        if($filesystem->exists($path)){
            $fileName = Carbon::now()->timestamp . "-{$fileName}";
        }

        $file->move($path_blank, $fileName);

        return url("{$imageServer}{$fileName}");



    }

    public function video(Filesystem $filesystem){

        // Validation Data
        $this->validate($this->request,[
            'video_set' => 'required|mimes:mp4',
        ]);



        $Path = $this->fileFinalPath('video');

        $root_Path =  $Path['root_path'];
        $imagePath =  $Path['file_path'];
        $path_blank =  $Path['path'];
        $imageServer = $Path['url'];

        $file = $this->request->file('video_set');
        $fileName = $file->getClientOriginalName();

        $path = "{$path_blank}/{$fileName}";


        if($filesystem->exists($path)){
            $fileName = Carbon::now()->timestamp . "-{$fileName}";
        }

        $file->move($path_blank, $fileName);

        return url("{$imageServer}{$fileName}");

    }

}
