<?php


namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    public $imageFile;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png,jpg,jpeg','maxFiles' => 1],
        ];
    }

    /**
     * method saves file to directory uploads
     * @param $bookId
     * @return bool
     */
    public function upload($bookId)
    {
        if ($this->validate()) {
            $this->imageFile->saveAs('uploads/' . $bookId . '.' . $this->imageFile->extension);
            return true;
        } else {
            return false;
        }
    }

}