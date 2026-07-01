<?php
declare(strict_types=1);
/**
 * The model file of jenkins module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.com>
 * @package     jenkins
 * @link        https://www.zentao.net
 */

class jenkinsModel extends model
{
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
    public function getDepthJobs(array $jobs, string $userPWD, int $depth = 1): array
    {
        if($depth > 4) return array();

        $tasks = array();
        foreach($jobs as $job)
        {
            if(empty($job->url)) continue;

            $isJob = true;
            if(stripos($job->_class, '.multibranch') !== false || stripos($job->_class, '.folder') !== false || stripos($job->_class, '.OrganizationFolder') !== false) $isJob = false;
            if(!empty($job->buildable) && $job->buildable) $isJob = true;

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

        return "$jenkinsUser:$jenkinsPassword";
    }
}
