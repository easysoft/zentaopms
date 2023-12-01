<?php
declare(strict_types=1);
/**
 * The tao file of job module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zenggang <zenggang@easycorp.ltd>
 * @package     job
 * @link        https://www.zentao.net
 */
class jobTao extends jobModel
{
    /**
     * 获取流水线实例的服务器和流水线信息。
     * Get job server and pipeline.
     *
     * @param  object    $job
     * @param  object    $repo
     * @access protected
     * @return bool
     */
    protected function getServerAndPipeline(object &$job, object $repo): bool
    {
        if($job->engine == 'jenkins')
        {
            $job->server   = (int)zget($job, 'jkServer', 0);
            $job->pipeline = zget($job, 'jkTask', '');
        }

        if(strtolower($job->engine) == 'gitlab')
        {
            $project = zget($repo, 'project');
            if($job->repo && !empty($repo))
            {
                $pipeline = $this->loadModel('gitlab')->apiGetPipeline($repo->serviceHost, $repo->serviceProject, '');
                if(!is_array($pipeline) or empty($pipeline))
                {
                    dao::$errors['repo'][] = $this->lang->job->engineTips->error;
                    return false;
                }
            }

            $job->server   = (int)zget($repo, 'serviceHost', 0);
            $job->pipeline = json_encode(array('project' => $project, 'reference' => ''));
        }

        unset($job->jkServer);
        unset($job->jkTask);
        unset($job->gitlabRepo);

        return true;
    }

    /**
     * 检查框架数据。
     * Check iframe data.
     *
     * @param  object    $job
     * @access protected
     * @return bool
     */
    protected function checkIframe(object $job, int $jobID = 0): bool
    {
        /* SonarQube tool is only used if the engine is JenKins. */
        if($job->engine != 'jenkins' and $job->frame == 'sonarqube')
        {
            dao::$errors['frame'][] = $this->lang->job->mustUseJenkins;
            return false;
        }

        if($job->repo > 0 and $job->frame == 'sonarqube')
        {
            $sonarqubeJob = $this->getSonarqubeByRepo(array($job->repo), $jobID);
            if(!empty($sonarqubeJob))
            {
                $message = sprintf($this->lang->job->repoExists, $sonarqubeJob[$job->repo]->id . '-' . $sonarqubeJob[$job->repo]->name);
                dao::$errors['repo'][] = $message;
                return false;
            }
        }

        if(!empty($job->projectKey) and $job->frame == 'sonarqube')
        {
            $projectList = $this->getJobBySonarqubeProject($job->sonarqubeServer, array($job->projectKey));
            if(!empty($projectList))
            {
                $message = sprintf($this->lang->job->projectExists, $projectList[$job->projectKey]->id);
                dao::$errors['projectKey'][] = $message;
                return false;
            }
        }

        return true;
    }

    /**
     * 获取svn目录信息。
     * Get svn dir.
     *
     * @param  object    $job
     * @param  object    $repo
     * @access protected
     * @return void
     */
    protected function getSvnDir(object &$job, object $repo): void
    {
        $job->svnDir = '';
        if($job->triggerType == 'tag' and $repo->SCM == 'Subversion')
        {
            $job->svnDir = array_pop($_POST['svnDir']);
            if($job->svnDir == '/' and $_POST['svnDir']) $job->svnDir = array_pop($_POST['svnDir']);
        }
    }

    /**
     * 获取流水线对象自定义参数。
     * Get job custom param.
     *
     * @param  object $job
     * @access protected
     * @return bool
     */
    protected function getCustomParam(object &$job): bool
    {
        $customParam = array();
        foreach($job->paramName as $key => $paramName)
        {
            $paramValue = zget($job->paramValue, $key, '');

            if(empty($paramName) and !empty($paramValue))
            {
                dao::$errors['paramName'][] = $this->lang->job->inputName;
                return false;
            }

            if(!empty($paramName) and !validater::checkREG($paramName, '/^[A-Za-z_0-9]+$/'))
            {
                dao::$errors['paramName'][] = $this->lang->job->invalidName;
                return false;
            }

            if(!empty($paramName)) $customParam[$paramName] = $paramValue;
        }
        unset($job->paramName);
        unset($job->paramValue);
        unset($job->custom);
        $job->customParam = json_encode($customParam);

        return true;
    }
}

