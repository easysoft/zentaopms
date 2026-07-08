<?php
declare(strict_types=1);
/**
 * The tao file of pipeline module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zenggang <zenggang@easycorp.ltd>
 * @package     pipeline
 * @link        https://www.zentao.net
 */
class pipelineTao extends pipelineModel
{
    /**
     * 获取流水线实例的服务器和流水线信息。
     * Get pipeline server and pipeline.
     *
     * @param  object    $pipeline
     * @param  object    $repo
     * @access protected
     * @return object
     */
    protected function getServerAndPipeline(object $pipeline, object $repo): object
    {
        $project = zget($repo, 'serviceProject', '');
        $host    = (int)zget($repo, 'serviceHost', 0);
        if($pipeline->engine == 'jenkins')
        {
            $pipeline->server   = (int)zget($pipeline, 'jkServer', 0);
            $pipeline->pipeline = zget($pipeline, 'jkTask', '');
        }
        elseif($pipeline->engine == 'gitlab')
        {
            if($pipeline->repo && !empty($repo))
            {
                $pipeline = $this->loadModel('gitlab')->apiGetPipeline($host, (int)$project, '');
                if(!is_array($pipeline) or empty($pipeline))
                {
                    dao::$errors['repo'][] = $this->lang->pipeline->engineTips->error;
                    return $pipeline;
                }
            }

            $pipeline->server   = $host;
            $pipeline->pipeline = json_encode(array('project' => $project, 'reference' => zget($pipeline, 'reference', 'main')));
        }

        unset($pipeline->reference);
        unset($pipeline->jkServer);
        unset($pipeline->jkTask);
        unset($pipeline->gitlabRepo);

        return $pipeline;
    }

    /**
     * 检查框架数据。
     * Check iframe data.
     *
     * @param  object    $pipeline
     * @access protected
     * @return bool
     */
    protected function checkIframe(object $pipeline, int $pipelineID = 0): bool
    {
        /* SonarQube tool is only used if the engine is JenKins. */
        if($pipeline->engine != 'jenkins' and $pipeline->frame == 'sonarqube')
        {
            dao::$errors['frame'][] = $this->lang->pipeline->mustUseJenkins;
            return false;
        }

        if($pipeline->repo > 0 and $pipeline->frame == 'sonarqube')
        {
            $sonarqubeJob = $this->getSonarqubeByRepo(array($pipeline->repo), $pipelineID);
            if(!empty($sonarqubeJob))
            {
                $message = sprintf($this->lang->pipeline->repoExists, $sonarqubeJob[$pipeline->repo]->id . '-' . $sonarqubeJob[$pipeline->repo]->name);
                dao::$errors['repo'][] = $message;
                return false;
            }
        }

        if(!empty($pipeline->projectKey) and $pipeline->frame == 'sonarqube')
        {
            $projectList = $this->getJobBySonarqubeProject($pipeline->sonarqubeServer, array($pipeline->projectKey));
            if(!empty($projectList) && $projectList[$pipeline->projectKey] != $pipelineID)
            {
                $message = sprintf($this->lang->pipeline->projectExists, $projectList[$pipeline->projectKey]);
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
     * @param  object    $pipeline
     * @param  object    $repo
     * @access protected
     * @return void
     */
    protected function getSvnDir(object &$pipeline, object $repo): void
    {
        $pipeline->svnDir = '';
        if(strpos($pipeline->triggerType, 'tag') !== false && $repo->SCM == 'Subversion')
        {
            $pipeline->svnDir = array_pop($_POST['svnDir']);
            if($pipeline->svnDir == '/' and $_POST['svnDir']) $pipeline->svnDir = array_pop($_POST['svnDir']);
        }
    }

    /**
     * 获取流水线对象自定义参数。
     * Get pipeline custom param.
     *
     * @param  object $pipeline
     * @access protected
     * @return bool
     */
    protected function getCustomParam(object &$pipeline): bool
    {
        $customParam = array();
        foreach($pipeline->paramName as $key => $paramName)
        {
            $paramValue = zget($pipeline->paramValue, $key, '');

            if(empty($paramName) and !empty($paramValue))
            {
                dao::$errors['paramName'][] = $this->lang->pipeline->inputName;
                return false;
            }

            if(!empty($paramName) and !validater::checkREG($paramName, '/^\w+$/'))
            {
                dao::$errors['paramName'][] = $this->lang->pipeline->invalidName;
                return false;
            }

            if(!empty($paramName)) $customParam[$paramName] = $paramValue;
        }
        unset($pipeline->paramName);
        unset($pipeline->paramValue);
        unset($pipeline->custom);
        $pipeline->customParam = json_encode($customParam);

        return true;
    }
}
