<?php


namespace App\Core;

use App\Core\App;

use App\Core\Log;


class File
{

    protected $new_filename;
    protected $max_filesize = 6097152;
    protected $extension;
    protected $path;
    public $uploads_dir;




/*
 *array(5) { ["name"]=> string(19) "20190211_222252.jpg" ["type"]=> string(10) "image/jpeg" ["tmp_name"]=> string(23) "C:\xampp\tmp\php3E0.tmp" ["error"]=> int(0) ["size"]=> int(1266982) }
 * */


    /**
     * Get the file name
     * @return mixed
     */
    public function getName()
    {
        return $this->new_filename;
    }

    /**
     * Set the name of the file
     * @param $file
     * @param string $name
     */
    protected function setName($file)
    {
        $hash = md5(microtime());
        $extension = $this->fileExtension($file);
        $this->new_filename = "{$hash}.{$extension}";
    }

    /**
     * set file extension
     * @param $file
     * @return mixed
     */
    protected function fileExtension($file)
    {
        return $this->extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    }

    /**
     * Validate file Size
     * @param $file
     * @return bool
     */
    public static function fileSize($file)
    {
        $fileobj = new static;
        return $file > $fileobj->max_filesize ? true : false;
    }

    /**
     * Validate file upload
     * @param $file
     * @return bool
     */
    public static function isImage($file)
    {
        $fileobj = new static;
        $ext = $fileobj->fileExtension($file);
        $validExt = array('jpg', 'jpeg', 'png', 'bmp', 'gif');

        if(!in_array(strtolower($ext), $validExt)){

            return false;
        }

        return true;
    }

    /**
     * Get the path where file was uploaded to
     * @return mixed
     */
    public function path()
    {
        return $this->path;
    }

    /**
     * Move the file to intended location
     *
     * @param $temp_path
     * @param $folder
     * @param $file
     * @param $new_filename
     * @return null|static
     */
    public static function move($temp_path, $folder, $file)
    {

        $fileObj = new static;
        $ds = DIRECTORY_SEPARATOR;

        $fileObj->setName($file);
        $file_name = $fileObj->getName();

        if(!is_dir($folder)){
            mkdir($folder, 0777, true);
        }

        $fileObj->path = "{$folder}{$ds}{$file_name}";

        $new_file=App::get('config')['files_dir'].$ds.$folder.$ds.$fileObj->getName();
        if(move_uploaded_file($temp_path, $new_file)){
            return $fileObj;
        }

        return null;
    }
    //$file= $row->file, $location=
    public static function deleteFile($file, $location){
        $fileObj=new static;
        //Log::add(toJson(App::get('config')['files_dir'].DIRECTORY_SEPARATOR.$location.DIRECTORY_SEPARATOR.$file));
        if(file_exists($file_to_delete=App::get('config')['files_dir'].DIRECTORY_SEPARATOR.$location.DIRECTORY_SEPARATOR.$file)){
            //Log::add(toJson($file_to_delete));
            return unlink($file_to_delete);
        }else{
            return false;
        }
    }

}