<?php
declare(strict_types=1);
/**
 * The zen file of testreport module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     testreport
 * @link        https://www.zentao.net
 */
class testreportZen extends testreport
{
    /**
     * Get reports for browse.
     *
     * @param  int       $objectID
     * @param  string    $objectType
     * @param  int       $extra
     * @param  string    $orderBy
     * @param  int       $recTotal
     * @param  int       $recPerPage
     * @param  int       $pageID
     * @access protected
     * @return array
     */
    protected function getReportsForBrowse(int $objectID = 0, string $objectType = 'product', int $extra = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1): array
    {
        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $reports = $this->testreport->getList($objectID, $objectType, $extra, $orderBy, $pager);

        if(strpos('|project|execution|', $objectType) !== false && ($extra || isset($_POST['taskIdList'])))
        {
            $taskIdList = isset($_POST['taskIdList']) ? $_POST['taskIdList'] : array($extra);
            foreach($reports as $reportID => $report)
            {
                $tasks = explode(',', $report->tasks);
                if(count($tasks) != count($taskIdList) || array_diff($tasks, $taskIdList)) unset($reports[$reportID]);
            }
            $pager->setRecTotal(count($reports));
        }

        $this->view->pager = $pager;
        return $reports;
    }
}

