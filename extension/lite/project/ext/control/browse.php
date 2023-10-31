<?php
helper::importControl('project');
class myProject extends project
{
    public function browse(int $programID = 0, string $browseType = 'doing', string $param = '', string $orderBy = 'order_asc', int $recTotal = 0, int $recPerPage = 15, int $pageID = 1)
    {
        $_COOKIE['projectType'] = 'bylist';
        return parent::browse($programID, $browseType, $param, $orderBy, $recTotal, $recPerPage, $pageID);
    }
}
