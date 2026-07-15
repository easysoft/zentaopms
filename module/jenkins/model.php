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
     * 检查Jenkins是否支持参数化构建。
     * Check if jenkins support parameterized build.
     *
     * @param  string $url
     * @param  string $userPWD
     * @access public
     * @return bool
     */
    public function checkParameterizedBuild(string $url, string $userPWD): bool
    {
        $response = common::http(rtrim($url, '/') . '/config.xml', null, array(CURLOPT_USERPWD => $userPWD));

        return strpos($response, 'hudson.model.ParametersDefinitionProperty') !== false;
    }

    /**
     * 通过api创建一个流水线。
     * Create a new pipeline by api.
     *
     * @param  string $url
     * @param  object $data
     * @param  string $userPWD
     * @access public
     * @return string|int
     */
    public function apiCreatePipeline(string $url, object $data, string $userPWD = ''): string|int
    {
        $response = common::http($url, $data, array(
            CURLOPT_HEADER => true,
            CURLOPT_USERPWD => $userPWD
        ));
        if(preg_match("!Location: .*item/(.*)/!i", $response, $matches)) return $matches[1];
        return 0;
    }

    /**
     * 通过api获取执行信息。
     * Get execution info by api.
     *
     * @param  int    $number
     * @param  string $pipelineName
     * @param  object $provider
     * @access public
     * @return array|object
     */
    public function apiGetExecInfo(int $number, string $pipelineName, object $provider): array|object
    {
        if(empty($provider->token) || empty($provider->url)) return array();
        $options = array(CURLOPT_USERPWD => base64_decode($provider->token));

        $url = rtrim($provider->url, '/') . $pipelineName . $number . '/api/json';

        $response = json_decode(common::http($url, null, $options, array('Accept: application/json'), 'json'));
        if(empty($response) || empty($response->result)) return array();
        return $response;
    }
}
