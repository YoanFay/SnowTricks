<?php

namespace App\Service;

use App\Kernel;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class  UploadService
{

    /**
     * @var Kernel
     */
    private $kernel;


    /**
     * @param Kernel $kernel parameter
     */
    public function __construct(Kernel $kernel)
    {

        $this->kernel = $kernel;

    }//end __construct()


    /**
     * @param string       $tricksSlug parameter
     * @param UploadedFile $image      parameter
     *
     * @return string
     */
    public function uploadTricks(string $tricksSlug, UploadedFile $image): string
    {

        $dir = $this->kernel->getProjectDir().'/public/img/Tricks/'.$tricksSlug.'/';

        if (is_dir($dir) === FALSE) {
            mkdir($dir);
        }

        $name = md5(uniqid());
        $image->move($dir, $name.'.'.$image->getClientOriginalExtension());

        return $name;

    }//end uploadTricks()


}
