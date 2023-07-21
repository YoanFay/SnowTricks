<?php

namespace App\Service;

use App\Kernel;

class  UploadService
{

    private $kernel;

    public function __construct(Kernel $kernel){
        $this->kernel = $kernel;
    }

    public function uploadTricks($tricksSlug, $image)
    {

        $dir = $this->kernel->getProjectDir().'/public/img/Tricks/'.$tricksSlug.'/';

        if (!is_dir($dir)){
            mkdir($dir);
        }

        $name = md5(uniqid());
        $image->move($dir, $name.'.'.$image->getClientOriginalExtension());

        return $name;

    }

}