<?php
/**
 * The model file of ci module of ZenTaoPMS.
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: $
 * @link        http://www.zentao.net
 */

class ciModel extends model
{
    /**
     * Set menu.
     * 
     * @access public
     * @return void
     */
    public function setMenu()
    {
        $repoID = $this->session->repoID;
        $moduleName = $this->app->getModuleName();
        foreach($this->lang->{$moduleName}->menu as $key => $menu) common::setMenuVars($this->lang->{$moduleName}->menu, $key, $repoID);
        $this->lang->{$moduleName}->menuOrder = $this->lang->ci->menuOrder;
    }

    /**
     * Send a request to jenkins to check build status.
     *
     * @access public
     * @return bool
     */
    public function checkBuildStatus()
    {
        $pos = $this->dao->select('build.*, job.jenkinsJob, jenkins.name jenkinsName,jenkins.serviceUrl,jenkins.account,jenkins.token,jenkins.password')
            ->from(TABLE_COMPILE)->alias('build')
            ->leftJoin(TABLE_INTEGRATION)->alias('job')->on('build.cijob=job.id')
            ->leftJoin(TABLE_JENKINS)->alias('jenkins')->on('job.jenkins=jenkins.id')
            ->where('build.status')->ne('success')
            ->andWhere('build.status')->ne('fail')
            ->andWhere('build.status')->ne('timeout')
            ->andWhere('build.createdDate')->gt(date(DT_DATETIME1, strtotime("-1 day")))
            ->fetchAll();

        foreach($pos as $po)
        {
            $jenkinsServer   = $po->serviceUrl;
            $jenkinsUser     = $po->account;
            $jenkinsPassword = $po->token ? $po->token : base64_decode($po->password);

            $jenkinsAuth   = '://' . $jenkinsUser . ':' . $jenkinsTokenOrPassword . '@';
            $jenkinsServer = str_replace('://', $jenkinsAuth, $jenkinsServer);
            $queueUrl = sprintf('%s/queue/item/%s/api/json', $jenkinsServer, $po->queueItem);

            $response = common::http($queueUrl);
            if(strripos($response, "404") > -1)
            { // queue expired, use another api
                $infoUrl = sprintf('%s/job/%s/%s/api/json', $jenkinsServer, $po->jenkinsJob, $po->queueItem);
                $response = common::http($infoUrl);
                $buildInfo = json_decode($response);
                $result = strtolower($buildInfo->result);
                $this->updateBuildStatus($po, $result);

                $logUrl = sprintf('%s/job/%s/%s/consoleText', $jenkinsServer, $po->jenkinsJob, $po->queueItem);
                $response = common::http($logUrl);
                $logs = json_decode($response);

                $this->dao->update(TABLE_COMPILE)->set('logs')->eq($response)->where('id')->eq($po->id)->exec();
            }
            else
            {
                $queueInfo = json_decode($response);

                if(!empty($queueInfo->executable))
                {
                    $buildUrl = $queueInfo->executable->url . 'api/json?pretty=true';
                    $buildUrl = str_replace('://', $r, $buildUrl);

                    $response = common::http($buildUrl);
                    $buildInfo = json_decode($response);

                    if($buildInfo->building)
                    {
                        $this->updateBuildStatus($po, 'building');
                    }
                    else
                    {
                        $result = strtolower($buildInfo->result);
                        $this->updateBuildStatus($po, $result);

                        $logUrl = $buildInfo->url . 'logText/progressiveText/api/json';
                        $logUrl = str_replace('://', $r, $logUrl);

                        $response = common::http($logUrl);
                        $logs = json_decode($response);

                        $this->dao->update(TABLE_COMPILE)->set('logs')->eq($response)->where('id')->eq($po->id)->exec();
                    }
                }
            }
        }
    }

    /**
     * Update ci build status.
     *
     * @param  object $build
     * @param  string $status
     * @access public
     * @return bool
     */
    public function updateBuildStatus($build, $status)
    {
        $this->dao->update(TABLE_COMPILE)->set('status')->eq($status)->where('id')->eq($build->id)->exec();

        $this->dao->update(TABLE_INTEGRATION)
            ->set('lastExec')->eq(helper::now())
            ->set('lastStatus')->eq($status)
            ->where('id')->eq($build->cijob)
            ->exec();
    }

    /**
     * @param $url
     * @return false|mixed|string
     */
	public function sendRequest($url, $data)
	{
		if(!empty($data->PARAM_TAG)) $data->PARAM_REVISION = '';

		$response = common::http($url, $data, true);
		if(preg_match("!Location: .*item/(.*)/!", $response, $matches)) return $matches[1];
		return '';
	}
}
