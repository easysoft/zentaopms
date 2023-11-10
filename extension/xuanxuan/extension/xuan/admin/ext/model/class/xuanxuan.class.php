<?php
class xuanxuanAdmin extends adminModel
{
    /**
     * 获取所有聊天文件的总大小。
     * Get total size of all xxc files.
     *
     * @access public
     * @return string
     */
    public function getXxcAllFileSize(): string
    {
        $fileSize = $this->dao->select('SUM(size) AS size')->from(TABLE_FILE)->where('objectType')->eq('chat')->fetch('size');
        if(!$fileSize) $fileSize = 0;

        if($fileSize > pow(1024, 3)) return round($fileSize / pow(1024, 3), 2) . '<small> GB</small>';
        if($fileSize > pow(1024, 2)) return round($fileSize / pow(1024, 2), 2) . '<small> MB</small>';
        if($fileSize > 1024) return round($fileSize / 1024, 2) . '<small> KB</small>';
        return $fileSize . '<small> B</small>';
    }
}
