<?php
/**
 * The zen file of errorlog module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2026 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      ZenTao Team
 * @package     errorlog
 * @link        https://www.zentao.net
 * @property    errorlogModel $errorlog
 */
class errorlogZen extends errorlog
{
    /**
     * 构建搜索表单。
     * Build search form.
     *
     * @param  array      $searchConfig
     * @param  string|int $queryID
     * @param  string     $actionURL
     * @access protected
     * @return void
     */
    protected function buildSearchForm(array $searchConfig, string|int $queryID, string $actionURL)
    {
        $searchConfig['queryID']   = (int)$queryID;
        $searchConfig['actionURL'] = $actionURL;

        if(isset($searchConfig['params']['module'])) $searchConfig['params']['module']['values'] = $this->errorlog->getModulePairs();

        $this->loadModel('search')->setSearchParams($searchConfig);
    }

    /**
     * 获取搜索条件。
     * Get search condition.
     *
     * @param  int    $queryID
     * @param  string $queryName
     * @access public
     * @return string
     */
    public function getErrorLogQuery(int $queryID, string $queryName = 'errorlogQuery'): string
    {
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set($queryName, $query->sql);
                $this->session->set('errorlogForm', $query->form);
            }
        }
        if(!$this->session->$queryName) $this->session->set($queryName, ' 1 = 1');
        $errorLogQuery = $this->session->$queryName;
        return $errorLogQuery;
    }
}
