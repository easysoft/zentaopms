<?php
declare(strict_types=1);
/**
 * The zen file of sonarqube module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Zeng<zenggang@easycorp.ltd>
 * @package     sonarqube
 * @link        https://www.zentao.net
 */
class sonarqubeZen extends sonarqube
{
    /**
     * 检查token必须数据。
     * Check token required data.
     *
     * @param  object    $sonarqube
     * @access protected
     * @return void
     */
    protected function checkTokenRequire(object $sonarqube): void
    {
        $this->dao->update('sonarqube')->data($sonarqube)
            ->batchCheck(empty($sonarqubeID) ? $this->config->sonarqube->create->requiredFields : $this->config->sonarqube->edit->requiredFields, 'notempty')
            ->batchCheck("url", 'URL');
        if(strpos($sonarqube->url, 'http') !== 0) dao::$errors['url'][] = $this->lang->sonarqube->hostError;
    }

    /**
     * 对数据进行排序和分页。
     * Sort and page data list.
     *
     * @param  array     $dataList
     * @param  string    $orderBy
     * @param  int       $recPerPage
     * @param  int       $pageID
     * @access protected
     * @return array
     */
    protected function sortAndPage(array $dataList, string $orderBy, int $recPerPage, int $pageID): array
    {
        /* Data sort. */
        list($order, $sort) = explode('_', $orderBy);
        $orderList = array();
        foreach($dataList as $data) $orderList[] = $data->$order;
        array_multisort($orderList, $sort == 'desc' ? SORT_DESC : SORT_ASC, $dataList);

        /* Pager. */
        $this->app->loadClass('pager', true);
        $recTotal             = count($dataList);
        $pager                = new pager($recTotal, $recPerPage, $pageID);
        $dataList = array_chunk($dataList, $pager->recPerPage);

        $this->view->pager = $pager;
        return $dataList;
    }

    protected function getIssueList(int $sonarqubeID, string $projectKey)
    {
        ini_set('memory_limit', '1024M');

        $cacheFile = $this->sonarqube->getCacheFile($sonarqubeID, $projectKey);
        if(!$cacheFile or !file_exists($cacheFile) or (time() - filemtime($cacheFile)) / 60 > $this->config->sonarqube->cacheTime)
        {
            $sonarqubeIssueList = $this->sonarqube->apiGetIssues($sonarqubeID, $projectKey);
            foreach($sonarqubeIssueList as $key => $sonarqubeIssue)
            {
                if(!isset($sonarqubeIssue->line)) $sonarqubeIssue->line = '';
                if(!isset($sonarqubeIssue->effort)) $sonarqubeIssue->effort = '';
                $sonarqubeIssue->message      = htmlspecialchars($sonarqubeIssue->message);
                $sonarqubeIssue->creationDate = date('Y-m-d H:i:s', strtotime($sonarqubeIssue->creationDate));

                list(, $file) = explode(':', $sonarqubeIssue->component);
                $sonarqubeIssue->file = $file;
            }

            if($cacheFile && !file_exists($cacheFile . '.lock'))
            {
                touch($cacheFile . '.lock');
                file_put_contents($cacheFile, serialize($sonarqubeIssueList));
                unlink($cacheFile . '.lock');
            }
        }
        else
        {
            $sonarqubeIssueList = unserialize(file_get_contents($cacheFile));
        }

        return $sonarqubeIssueList;
    }
}

