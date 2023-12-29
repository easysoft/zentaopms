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

            $this->dao->insert(TABLE_IMAGE)->data($remoteImage, 'desc,os')->autoCheck()->exec();

            $refreshPageData = true;
        }

        return $refreshPageData;
    }

    /**
     * 获取当前的下载任务。
     * Get current download task.
     *
     * @param  int         $imageID
     * @param  array       $statusGroupTasks
     * @access protected
     * @return null|object
     */
    protected function getCurrentTask(int $imageID, object $statusGroupTasks): null|object
    {
        $currentTask = null;
        $finished    = false;
        foreach($statusGroupTasks as $groupTasks)
        {
            if($finished) break;
            foreach($groupTasks as $task)
            {
                if($finished) break;
                if($task->task != $imageID) continue;

                $task->endDate = $task->endDate ? substr($task->endDate, 0, 19) : '';
                if(empty($currentTask) || strtotime($task->endDate) > strtotime($currentTask->endDate)) $currentTask = $task;

                if($task->status == 'inprogress')
                {
                    $currentTask = $task;
                    $finished    = true;
                    break;
                }
            }
        }
        return $currentTask;
    }
}
