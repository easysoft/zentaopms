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
                $report['projectOverview'] = $this->projectOverview($accounts);
            }
            elseif($field == 'radar')
            {
                $report['radar'] = $this->radar($accounts, $year);
            }
            elseif($field == 'projectprogress')
            {
                $report['projectProgress'] = $this->projectProgress();
            }
        }

        return $this->send(200, $report);
    }

    public function projectOverview($accounts)
    {
        $statusOverview = $this->loadModel('report')->getProjectStatusOverview(array_keys($accounts));

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

        return $projectOverview;
    }

    public function radar($accounts, $year)
    {
        $allAccounts      = $this->loadModel('user')->getPairs('noletter|noclosed');
        $contributions    = $this->loadModel('report')->getUserYearContributions(empty($accounts) ? array_keys($allAccounts) : $accounts, $year);
        $annualDataConfig = $this->config->report->annualData;

        $radarData = array('product' => 0, 'execution' => 0, 'devel' => 0, 'qa' => 0, 'other' => 0);
        foreach($contributions as $objectType => $objectContributions)
        {
            foreach($objectContributions as $actionName => $count)
            {
                $radarTypes = isset($annualDataConfig['radar'][$objectType][$actionName]) ? $annualDataConfig['radar'][$objectType][$actionName] : array('other');
                foreach($radarTypes as $radarType) $radarData[$radarType] += $count;
            }
        }

        $radar = array();
        foreach($radarData as $radarType => $total)
        {
            $radar[$radarType]['code']  = $radarType;
            $radar[$radarType]['name']  = $this->lang->report->annualData->radarItems[$radarType];
            $radar[$radarType]['total'] = $total;
        }

        return array_values($radar);
    }

    public function projectProgress()
    {
        $projects = $this->loadModel('program')->getProjectStats(0, 'all');
        $this->app->loadLang('project');

        $processedProjects = array();
        $statusList['all']['total']    = 0;
        $statusList['doing']['total']  = 0;
        $statusList['wait']['total']   = 0;
        $statusList['closed']['total'] = 0;
        foreach($projects as $project)
        {
            $newProject = new stdclass();
            $newProject->id            = $project->id;
            $newProject->name          = $project->name;
            $newProject->status        = $project->status;
            $newProject->progress      = $project->hours->progress;
            $newProject->totalConsumed = $project->hours->totalConsumed;
            $newProject->totalLeft     = $project->hours->totalLeft;
            if(isset($project->delay)) $newProject->delay = $project->delay;

            $statusList['all']['total'] += 1;
            if(isset($statusList[$project->status])) $statusList[$project->status]['total'] += 1;

            $processedProjects[$project->id] = $newProject;
        }

        foreach(array_keys($statusList) as $status)
        {
            $statusName = zget($this->lang->project->statusList, $status);
            if($status == 'all') $statusName = $this->lang->project->featureBar['all'];

            $statusList[$status]['code'] = $status;
            $statusList[$status]['name'] = $statusName;
        }

        return array('statusList' => $statusList, 'projects' => array_values($processedProjects));
    }
}
