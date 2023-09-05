<?php
/**
 * The model file of jenkins module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: $
 * @link        http://www.zentao.net
 */

class jenkinsModel extends model
{
    /**
     * Get a jenkins by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getByID($id)
    {
         return $this->loadModel('pipeline')->getByID($id);
    }

    /**
     * Get jenkins list.
     *
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($orderBy = 'id_desc', $pager = null)
    {
         return $this->loadModel('pipeline')->getList('jenkins', $orderBy, $pager);
    }

    /**
     * Get jenkins pairs
     *
     * @return array
     */
    public function getPairs()
    {
       return $this->loadModel('pipeline')->getPairs('jenkins');
    }

    /**
     * Get jenkins tasks.
     *
     * @param  int    $id
     * @param  int    $depth
     * @access public
     * @return array
     */
    public function getTasks($id, $depth = 0)
    {
        $jenkins = $this->getById($id);

        $jenkinsServer   = $jenkins->url;
        $jenkinsUser     = $jenkins->account;
        $jenkinsPassword = $jenkins->token ? $jenkins->token : $jenkins->password;

        $userPWD  = "$jenkinsUser:$jenkinsPassword";
        $response = common::http($jenkinsServer . '/api/json/items/list' . ($depth ? "?depth=1" : ''), '', array(CURLOPT_USERPWD => $userPWD), $headers = array(), $dataType = 'data', $method = 'POST', $timeout = 30, $httpCode = false, $log = false);
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
     * Get jobs by depth.
     *
     * @param object $jobs
     * @param string $userPWD
     * @param int    $depth
     * @access protected
     * @return array
     */
    protected function getDepthJobs($jobs, $userPWD, $depth = 1)
    {
        if($depth > 4) return array();

        $tasks = array();
        foreach($jobs as $job)
        {
            if(empty($job->url)) continue;

            $isJob = true;
            if(stripos($job->_class, '.multibranch') !== false or stripos($job->_class, '.folder') !== false or stripos($job->_class, '.OrganizationFolder') !== false) $isJob = false;
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
                    $response = common::http($job->url . 'api/json', '', array(CURLOPT_USERPWD => $userPWD), $headers = array(), $dataType = 'data', $method = 'POST', $timeout = 30, $httpCode = false, $log = false);
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
     * Update a jenkins.
     *
     * @param  int    $id
     * @access public
     * @return bool
     */
    public function update($id)
    {
       return $this->loadModel('pipeline')->update($id);
    }
}
