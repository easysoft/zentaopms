<?php
class file extends control
{
    /**
     * Upload chat avatar from xuan client.
     *
     * @access public
     * @return void
     */
    public function uploadChatAvatar()
    {
        if($this->app->user->account == 'guest') die;

        $file = $this->file->getUpload('imgFile');
        $file = current($file);
        if($file)
        {
            move_uploaded_file($file['tmpname'], $this->file->savePath . $this->file->getSaveName($file['pathname']));

            /* Compress image for jpg and bmp. */
            $file = $this->file->compressImage($file);

            $file['addedBy']   = $this->app->user->account;
            $file['addedDate'] = helper::now();
            unset($file['tmpname']);
            $this->dao->insert(TABLE_FILE)->data($file)->exec();

            $fileID = $this->dao->lastInsertID();
            $this->send(array('result' => 'success', 'id' => $fileID));
        }
    }
}
