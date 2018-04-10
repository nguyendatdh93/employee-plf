<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 08/11/2017
 * Time: 16:38
 */

namespace App\Repositories\Eloquents;

use App\Models\UploadFile;
use App\Repositories\Contracts\ATBBaseRepositoryInterface;
use Illuminate\Support\Facades\URL;

class ATBBaseRepository implements ATBBaseRepositoryInterface
{
    public function all()
    {
    }

    public function fill($id)
    {
    }

    public function getDownloadStatus($file_status)
    {
        return UploadFile::$status_lable[$file_status];
    }

    public function getFileName($file_name)
    {
        $file_name_slices = explode('/',$file_name);
        $file_name_index     = count($file_name_slices) - 1;
        $file_name      = $file_name_slices[$file_name_index];

        return $file_name;
    }

    public function getFileInfo($file) {
        return array(
            'name'          => $this->getFileName($file->file_name),
            'url'           => URL::route('download.detail', $file->id),
            'description'   => $file->description,
            'expired_in'    => date('Y-m-d H:i:s', strtotime("+1 day", strtotime($file->created_at)))
        );
    }
}