<?php


namespace AppBundle;

use Symfony\Component\HttpFoundation\File\UploadedFile;
class FileUploader
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function upload(UploadedFile $file,$type,$name)
    {
        $fileName =$name.'.'.$file->guessExtension();

        $file->move($this->targetDir.'/'.$type, $fileName);

        return $fileName;
    }
}