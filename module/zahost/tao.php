<?php
declare(strict_types=1);
class zahostTao extends zahostModel
{
    /**
     * 将没有插入到 image 表的镜像数据插入到 image 表中。
     * Insert image list.
     *
     * @param  array     $imageList 
     * @param  int       $hostID 
     * @param  array     $downloadedImageList 
     * @access protected
     * @return bool
     */
    protected function insertImageList(array $imageList, int $hostID, array $downloadedImageList): bool
    {
        $refreshPageData = false;
        foreach($imageList as $remoteImage)
        {
            $downloadedImage = zget($downloadedImageList, $remoteImage->name, '');
            if(!empty($downloadedImage)) continue;

            $remoteImage->status = 'notDownloaded';
            $remoteImage->from   = 'zentao';
            $remoteImage->osName = $remoteImage->os;
            $remoteImage->host   = $hostID;
            unset($remoteImage->os);

            $this->dao->insert(TABLE_IMAGE)->data($remoteImage, 'desc')->autoCheck()->exec();

            $refreshPageData = true;
        }

        return $refreshPageData;
    }
}
