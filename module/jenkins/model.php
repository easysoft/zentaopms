<?php
declare(strict_types=1);
/**
 * The model file of jenkins module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.com>
 * @package     jenkins
 * @link        http://www.zentao.net
 */

class jenkinsModel extends model
{
    /**
     * 获取流水线列表。
     * Get jenkins tasks.
     *
     * @param  int    $jenkinsID
     * @param  int    $depth
     * @access public
     * @return array
     */
    public function getTasks(int $jenkinsID, int $depth = 0): array
    {
        $jenkins = $this->loadModel('pipeline')->getByID($jenkinsID);

        $jenkinsServer   = $jenkins->url;
        $jenkinsUser     = $jenkins->account;
        $jenkinsPassword = $jenkins->token ? $jenkins->token : $jenkins->password;

        $userPWD  = "$jenkinsUser:$jenkinsPassword";
        $response = common::http($jenkinsServer . '/api/json/items/list' . ($depth ? "?depth=1" : ''), '', array(CURLOPT_USERPWD => $userPWD));
        $response = json_decode($response);

        $tasks = array();
        if($depth)
        {
            /* Support up to 4 levels. */
            if(isset($response->jobs)) $tasks = $this->getDepthJobs($response->jobs, $userPWD, 1);
        }
        else
        {
            if(isset($response->jobs))
            {
                foreach($response->jobs as $job) $tasks[basename($job->url)] = $job->name;
            }
        }
        return $tasks;
    }

    /**
     * 根据深度获取流水线。
     * Get jobs by depth.
     *
     * @param  object    $jobs
     * @param  string    $userPWD
     * @param  int       $depth
     * @access protected
     * @return array
     */
    protected function getDepthJobs(array $jobs, string $userPWD, int $depth = 1): array
    {
        if($depth > 4) return array();

        $tasks = array();
        foreach($jobs as $job)
        {
            if(empty($job->url)) continue;

            $isJob = true;
            if(stripos($job->_class, '.multibranch') !== false || stripos($job->_class, '.folder') !== false || stripos($job->_class, '.OrganizationFolder') !== false) $isJob = false;
            if(!empty($job->buildable) and $job->buildable == true) $isJob = true;

            if($isJob)
            {
                $parms = parse_url($job->url);
                $tasks[$parms['path']] = $job->name;
            }
            else
            {
                if($depth > 1)
                {
                    $response = common::http($job->url . 'api/json', '', array(CURLOPT_USERPWD => $userPWD));
                    $job = json_decode($response);
                }

                $tasks[urldecode(basename($job->url))] = array();
                if(empty($job->jobs)) continue;

                $tasks[urldecode(basename($job->url))] = $this->getDepthJobs($job->jobs, $userPWD, $depth + 1);
            }
        }

        return $tasks;
    }

    /**
     * 获取Jenkins流水线。
     * Get jobs by jenkins.
     *
     * @param  int    $jenkinsID
     * @access public
     * @return array
     */
    public function getJobPairs(int $jenkinsID): array
    {
        return $this->dao->select('id, name')->from(TABLE_JOB)
            ->where('server')->eq($jenkinsID)
            ->andWhere('engine')->eq('jenkins')
            ->andWhere('deleted')->eq('0')
            ->fetchPairs();
    }

    /**
     * 获取jenkins api 密码串。
     * Get jenkins api userpwd string.
     *
     * @param  object $jenkins
     * @access public
     * @return string
     */
    public function getApiUserPWD(object $jenkins): string
    {
            $jenkinsUser     = $jenkins->account;
            $jenkinsPassword = $jenkins->token ? $jenkins->token : base64_decode($jenkins->password);
            $userPWD         = "$jenkinsUser:$jenkinsPassword";

            return $userPWD;
    }
}
