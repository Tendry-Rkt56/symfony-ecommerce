<?php

namespace App\Trait;

use App\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait UploadedImage 
{

     private function image(?UploadedFile $file, User $user, string $directory = '', string $prefix = '')
    {
        if (!$file instanceof UploadedFile && $file == null && $user->getImage() == null) return null;
        if (!$file instanceof UploadedFile && $user->getImage() !== null) return $user->getImage();
        else {
            $this->deleteImage($user);
            $fileName = md5(uniqid($prefix)).'.'.$file->guessExtension();
            $file->move($this->getParameter('kernel.project_dir').'/public/image/'.$directory.'/',$fileName);
            return $directory.'/'.$fileName;
        }
    }

    private function deleteImage(User $user)
    {
        if ($user->getImage()) {
            $path = $this->getParameter('kernel.project_dir').'/public/image/'.$user->getImage();
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }

}