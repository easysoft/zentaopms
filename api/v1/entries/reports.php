<?php
/**
 * The reports entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class reportsEntry extends entry
{
    /**
     * GET method.
     *
     * @access public
     * @return void
     */
    public function get()
    {
        $this->loadModel('report');

        $fields  = $this->param('fields', '');
        $dept    = $this->param('dept', 0);
        $account = $this->param('account', '');
        $year    = $this->param('year', date('Y'));
        if(empty($fields)) return $this->send(400, 'Need fields param for report.');

        $accounts = array();
        if($account) $accounts = array($account => $account);
        if(empty($accounts) and $dept) $accounts = $this->loadModel('dept')->getDeptUserPairs($dept);

        $fields = explode(',', strtolower($fields));
        $report = array();
        foreach($fields as $field)
        {
            $field = trim($field);
            if(empty($field)) continue;

            if($field == 'projectoverview')
            {
                $statusOverview = $this->report->getProjectStatusOverview(array_keys($accounts));

                $this->app->loadLang('project');
                $total    = 0;
                $overview = array();
                foreach($statusOverview as $status => $count)
                {
                    $total += $count;
                    $statusName = zget($this->lang->project->statusList, $status);

                    $overview[$status] = array();
                    $overview[$status]['code']  = $status;
                    $overview[$status]['name']  = $statusName;
                    $overview[$status]['total'] = $count;
                }

                $projectOverview = array();
                $projectOverview['total']    = $total;
                $projectOverview['overview'] = array_values($overview);

                $report['projectOverview'] = $projectOverview;
            }
        }

        return $this->send(200, $report);
    }
}
