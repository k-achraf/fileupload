<?php
namespace App\libs;


use function Sodium\library_version_major;

trait Upload
{
    protected $fileName;
    protected $maxSize = MAX_SIZE;
    protected $extention;
    protected $path;

    public function getName(){
        return $this->fileName;
    }
    protected function setName($file){
        $this->fileName = md5(microtime());
        $this->getExtention($file);
        $this->fileName = $this->fileName . '.' . $this->extention;
    }
    protected function getExtention($file){
        $this->extention = explode('.' , $file->name);
        $this->extention = end($this->extention);
        return $this->extention;
    }
    public function fileSize($file){
        return $file->size < $this->maxSize ? true : false;
    }
    public function getPath($file){
        $this->path = $file->tmp_name;
        return $this->path;
    }

    public function type($type , $file){
        $img = ['jpg','jpeg','png','bmp','gif'];
        $audio = ['mp3','acc','m4a'];
        $doc = ['pdf','doc','docx'];
        $video = ['mp4','avi','mkv'];
        switch ($type){
            case 'img':
                if (in_array(strtolower($this->extention) , $img)){
                    return true;
                }
                break;

            case 'audio':
                if (in_array(strtolower($this->extention) , $audio)){
                    return true;
                }
                break;

            case 'doc':
                if (in_array(strtolower($this->extention) , $doc)){
                    return true;
                }
                break;

            case 'video':
                if (in_array(strtolower($this->extention) , $video)){
                    return true;
                }
                break;
        }
        return false;
    }

    public function upload($file , $type){
        $this->setName($file);
        $this->getPath($file);
        $uploadPath = BASE_PATH . '/public/storage/' . $this->fileName;
        if($this->fileSize($file) && $this->type($type , $file)) {
            move_uploaded_file($this->path, $uploadPath);
        }
    }

    public function uploadTo($file , $type , $folder){
        $this->setName($file);
        $this->getPath($file);
        if(!is_dir(BASE_PATH . '/public/storage/'.$folder)){
            mkdir(BASE_PATH . '/public/storage/'.$folder , 0777);
        }
        $uploadPath = BASE_PATH . '/public/storage/'. $folder . '/' . $this->fileName;
        if($this->fileSize($file) && $this->type($type , $file)) {
            move_uploaded_file($this->path, $uploadPath);
        }
    }
}