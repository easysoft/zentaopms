<?php
/**
 * The model file of job module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     job
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class jobModel extends model
{
    /**
     * Get by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getByID($id)
    {
        $job = $this->dao->select('*')->from(TABLE_JOB)->where('id')->eq($id)->fetch();
        if(empty($job)) return new stdClass();

        if(strtolower($job->engine) == 'gitlab')
        {
            $pipeline = json_decode($job->pipeline);
            if(!isset($pipeline->reference)) return $job;
            $job->project   = $pipeline->project;
            $job->reference = $pipeline->reference;
        }
        elseif($job->engine == 'jenkins')
        {
            if(strpos($job->pipeline, '/job/') !== false)
            {
                $job->rawPipeline = $job->pipeline;
                $job->pipeline    = trim(str_replace('/job/', '/', $job->pipeline), '/');
            }
        }
        return $job;
    }

    /**
     * Get job list.
     *
     * @param  int    $repoID
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $engine
     * @param  string $pipeline
     * @access public
     * @return array
     */
    public function getList($repoID = 0, $orderBy = 'id_desc', $pager = null, $engine = '', $pipeline = '')
    {
        return $this->dao->select('t1.*, t2.name as repoName, t3.name as jenkinsName')->from(TABLE_JOB)->alias('t1')
            ->leftJoin(TABLE_REPO)->alias('t2')->on('t1.repo=t2.id')
            ->leftJoin(TABLE_PIPELINE)->alias('t3')->on('t1.server=t3.id')
            ->where('t1.deleted')->eq('0')
            ->beginIF($repoID)->andWhere('t1.repo')->eq($repoID)->fi()
            ->beginIF($engine)->andWhere('t1.engine')->eq($engine)->fi()
            ->beginIF($pipeline)->andWhere('t1.pipeline')->eq($pipeline)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

     /**
     * Get job list by RepoID.
     *
     * @param  int    $repoID
     * @access public
     * @return array
     */
    public function getListByRepoID($repoID)
    {
        return $this->dao->select('id, name, lastStatus')->from(TABLE_JOB)
            ->where('deleted')->eq('0')
            ->andWhere('repo')->eq($repoID)
            ->orderBy('id_desc')
            ->fetchAll('id');
    }

     /**
     * Get job pairs by RepoID.
     *
     * @param  int    $repoID
     * @param  string $engine gitlab|jenkins
     * @access public
     * @return array
     */
    public function getPairs($repoID, $engine = '')
    {
        return $this->dao->select('id, name')->from(TABLE_JOB)
            ->where('deleted')->eq('0')
            ->andWhere('repo')->eq($repoID)
            ->beginIF($engine)->andWhere('engine')->eq($engine)->fi()
            ->orderBy('id_desc')
            ->fetchPairs();
    }

   /**
     * Get list by triggerType field.
     *
     * @param  string  $triggerType
     * @param  array   $repoIdList
     * @access public
     * @return array
     */
    public function getListByTriggerType($triggerType, $repoIdList = array())
    {
        return $this->dao->select('*')->from(TABLE_JOB)
            ->where('deleted')->eq('0')
            ->andWhere('triggerType')->eq($triggerType)
            ->beginIF($repoIdList)->andWhere('repo')->in($repoIdList)->fi()
            ->fetchAll('id');
    }

    /**
     * Get trigger config.
     *
     * @param  object $job
     * @access public
     * @return string
     */
    public function getTriggerConfig($job)
    {
          $triggerType = zget($this->lang->job->triggerTypeList, $job->triggerType);
          if($job->triggerType == 'tag')
          {
              if(empty($job->svnDir)) return $triggerType;

              $triggerType = $this->lang->job->dirChange;
              return "{$triggerType}({$job->svnDir})";
          }

          if($job->triggerType == 'commit') return "{$triggerType}({$job->comment})";

          if($job->triggerType == 'schedule')
          {
              $atDay = '';
              foreach(explode(',', $job->atDay) as $day) $atDay .= zget($this->lang->datepicker->dayNames, trim($day), '') . ',';
              $atDay = trim($atDay, ',');
              return "{$triggerType}({$atDay}, {$job->atTime})";
          }
    }

    /**
     * Get trigger group.
     *
     * @param  string $triggerType
     * @param  array  $repoIdList
     * @access public
     * @return array
     */
    public function getTriggerGroup($triggerType, $repoIdList)
    {
        $jobs  = $this->getListByTriggerType($triggerType, $repoIdList);
        $group = array();
        foreach($jobs as $job) $group[$job->repo][$job->id] = $job;

        return $group;
    }

    /**
     * Create a job.
     *
     * @access public
     * @return int|bool
     */
    public function create()
    {
        $job = fixer::input('post')
            ->setDefault('atDay,projectKey', '')
            ->setDefault('sonarqubeServer', 0)
            ->setIF($this->post->triggerType != 'commit', 'comment', '')
            ->setIF($this->post->triggerType != 'schedule', 'atDay', '')
            ->setIF($this->post->triggerType != 'schedule', 'atTime', '')
            ->setIF($this->post->triggerType != 'tag', 'lastTag', '')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->remove('repoType,reference')
            ->cleanInt('product')
            ->get();
        $repo = $this->loadModel('repo')->getByID($job->repo);

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

        /* SonarQube tool is only used if the engine is JenKins. */
        if($job->engine != 'jenkins' and $job->frame == 'sonarqube')
        {
            dao::$errors['frame'][] = $this->lang->job->mustUseJenkins;
            return false;
        }

        if($job->repo > 0 and $job->frame == 'sonarqube')
        {
            $sonarqubeJob = $this->getSonarqubeByRepo(array($job->repo));
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

        if($job->triggerType == 'schedule') $job->atDay = empty($_POST['atDay']) ? '' : implode(',', $this->post->atDay);

        $job->svnDir = '';
        if($job->triggerType == 'tag' and $repo->SCM == 'Subversion')
        {
            $job->svnDir = array_pop($_POST['svnDir']);
            if($job->svnDir == '/' and $_POST['svnDir']) $job->svnDir = array_pop($_POST['svnDir']);
        }

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

        $this->dao->insert(TABLE_JOB)->data($job)
            ->batchCheck($this->config->job->create->requiredFields, 'notempty')
            ->batchCheckIF($job->triggerType === 'schedule' and $job->atDay !== '0', "atDay", 'notempty')
            ->batchCheckIF($job->triggerType === 'schedule', "atTime", 'notempty')
            ->batchCheckIF($job->triggerType === 'commit', "comment", 'notempty')
            ->batchCheckIF(($repo->SCM == 'Subversion' and $job->triggerType == 'tag'), "svnDir", 'notempty')
            ->batchCheckIF($job->frame === 'sonarqube', "sonarqubeServer,projectKey", 'notempty')
            ->autoCheck()
            ->exec();
        if(dao::isError()) return false;

        $id = $this->dao->lastInsertId();
        if(strtolower($job->engine) == 'jenkins') $this->initJob($id, $job, $repo->SCM);
        return $id;
    }

    /**
     * Update a job.
     *
     * @param  int    $id
     * @access public
     * @return bool
     */
    public function update($id)
    {
        $job = fixer::input('post')
            ->setDefault('atDay', '')
            ->setIF($this->post->triggerType != 'commit', 'comment', '')
            ->setIF($this->post->triggerType != 'schedule', 'atDay', '')
            ->setIF($this->post->triggerType != 'schedule', 'atTime', '')
            ->setIF($this->post->triggerType != 'tag', 'lastTag', '')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->remove('repoType,reference')
            ->get();
        $repo = $this->loadModel('repo')->getByID($job->repo);

        if($job->engine == 'jenkins')
        {
            $job->server   = (int)zget($job, 'jkServer', 0);
            $job->pipeline = zget($job, 'jkTask', '');
        }

        if(strtolower($job->engine) == 'gitlab')
        {
            $project = zget($repo, 'project');
            if(!empty($repo))
            {
                $pipeline = $this->loadModel('gitlab')->apiGetPipeline($repo->serviceHost, $repo->serviceProject, '');
                if(!is_array($pipeline) or empty($pipeline))
                {
                    dao::$errors['gitlabRepo'][] = $this->lang->job->engineTips->error;
                    return false;
                }
            }

            $job->server   = (int)zget($repo, 'serviceHost', 0);
            $job->pipeline = json_encode(array('project' => $project, 'reference' => ''));
        }

        unset($job->jkServer);
        unset($job->jkTask);
        unset($job->gitlabRepo);

        /* SonarQube tool is only used if the engine is JenKins. */
        if($job->engine != 'jenkins' and $job->frame == 'sonarqube')
        {
            dao::$errors['engine'][] = $this->lang->job->mustUseJenkins;
            return false;
        }

        if($job->repo > 0 and $job->frame == 'sonarqube')
        {
            $sonarqubeJob = $this->getSonarqubeByRepo(array($job->repo), $id);
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
            if(!empty($projectList) && $projectList[$job->projectKey] != $id)
            {
                $message = sprintf($this->lang->job->projectExists, $projectList[$job->projectKey]);
                dao::$errors['projectKey'][] = $message;
                return false;
            }
        }

        if($job->triggerType == 'schedule') $job->atDay = empty($_POST['atDay']) ? '' : implode(',', $this->post->atDay);

        $job->svnDir = '';
        if($job->triggerType == 'tag' and $repo->SCM == 'Subversion')
        {
            $job->svnDir = array_pop($_POST['svnDir']);
            if($job->svnDir == '/' and $_POST['svnDir']) $job->svnDir = array_pop($_POST['svnDir']);
        }

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

        $this->dao->update(TABLE_JOB)->data($job)
            ->batchCheck($this->config->job->edit->requiredFields, 'notempty')
            ->batchCheckIF($job->triggerType === 'schedule' and $job->atDay !== '0', "atDay", 'notempty')
            ->batchCheckIF($job->triggerType === 'schedule', "atTime", 'notempty')
            ->batchCheckIF($job->triggerType === 'commit', "comment", 'notempty')
            ->batchCheckIF(($repo->SCM == 'Subversion' and $job->triggerType == 'tag'), "svnDir", 'notempty')
            ->batchCheckIF($job->frame === 'sonarqube', "sonarqubeServer,projectKey", 'notempty')
            ->autoCheck()
            ->where('id')->eq($id)
            ->exec();
        if(dao::isError()) return false;

        $this->initJob($id, $job, $repo->SCM);
        return true;
    }

    /**
     * Init when create or update job.
     *
     * @param  int    $id
     * @param  object $job
     * @param  string $repoType
     * @access public
     * @return bool
     */
    public function initJob($id, $job, $repoType)
    {
        if(empty($id)) return false;
        if($job->triggerType == 'schedule' and strpos($job->atDay, date('w')) !== false)
        {
            $compiles = $this->dao->select('*')->from(TABLE_COMPILE)->where('job')->eq($id)->andWhere('LEFT(createdDate, 10)')->eq(date('Y-m-d'))->fetchAll();
            foreach($compiles as $compile)
            {
                if(!empty($compile->status)) continue;
                $this->dao->delete()->from(TABLE_COMPILE)->where('id')->eq($compile->id)->exec();
            }
            $this->loadModel('compile')->createByJob($id, $job->atTime, 'atTime');
        }

        if($job->triggerType == 'tag')
        {
            $repo    = $this->loadModel('repo')->getByID($job->repo);
            $lastTag = '';
            if($repoType == 'Subversion')
            {
                $dirs = $this->loadModel('svn')->getRepoTags($repo, $job->svnDir);
                end($dirs);
                $lastTag = current($dirs);
            }
            else
            {
                $tags = $this->loadModel('git')->getRepoTags($repo);
                end($tags);
                $lastTag = current($tags);
            }
            $this->dao->update(TABLE_JOB)->set('lastTag')->eq($lastTag)->where('id')->eq($id)->exec();
        }

        return true;
    }

    /**
     * Exec job.
     *
     * @param  int   $id
     * @param  array $extraParam
     * @access public
     * @return string|bool
     */
    public function exec($id, $extraParam = array())
    {
        $job = $this->dao->select('t1.id,t1.name,t1.product,t1.repo,t1.server,t1.pipeline,t1.triggerType,t1.atTime,t1.customParam,t1.engine,t2.name as jenkinsName,t2.url,t2.account,t2.token,t2.password')
            ->from(TABLE_JOB)->alias('t1')
            ->leftJoin(TABLE_PIPELINE)->alias('t2')->on('t1.server=t2.id')
            ->where('t1.id')->eq($id)
            ->fetch();

        if(!$job) return false;

        $repo = $this->loadModel('repo')->getByID($job->repo);
        $now  = helper::now();

        /* Save compile data. */
        $build = new stdclass();
        $build->job         = $job->id;
        $build->name        = $job->name;
        $build->createdBy   = $this->app->user ? $this->app->user->account : 'system';
        $build->createdDate = $now;
        $build->updateDate  = $now;

        if($job->triggerType == 'schedule') $build->atTime = $job->atTime;

        if($job->triggerType == 'tag')
        {
            $job->lastTag = $this->getLastTagByRepo($repo, $job);

            if($job->lastTag)
            {
                $build->tag = $job->lastTag;
                $this->dao->update(TABLE_JOB)->set('lastTag')->eq($job->lastTag)->where('id')->eq($job->id)->exec();
            }
        }

        $this->dao->insert(TABLE_COMPILE)->data($build)->exec();
        $compileID = $this->dao->lastInsertId();

        if($job->engine == 'jenkins') $compile = $this->execJenkinsPipeline($job, $repo, $compileID, $extraParam);
        if($job->engine == 'gitlab')  $compile = $this->execGitlabPipeline($job);

        $this->dao->update(TABLE_COMPILE)->data($compile)->where('id')->eq($compileID)->exec();

        $this->dao->update(TABLE_JOB)
            ->set('lastExec')->eq($now)
            ->set('lastStatus')->eq($compile->status)
            ->where('id')->eq($job->id)
            ->exec();

        return $compile;
    }

    /**
     * Exec jenkins pipeline.
     *
     * @param  object    $job
     * @param  object    $repo
     * @param  int       $compileID
     * @param  array     $extraParam
     * @access public
     * @return object
     */
    public function execJenkinsPipeline($job, $repo, $compileID, $extraParam = array())
    {
        $pipeline = new stdclass();
        $pipeline->PARAM_TAG   = '';
        $pipeline->ZENTAO_DATA = "compile={$compileID}";
        if($job->triggerType == 'tag') $pipeline->PARAM_TAG = $job->lastTag;

        /* Add custom parameters to the data. */
        foreach(json_decode($job->customParam) as $paramName => $paramValue)
        {
            $paramValue = str_replace('$zentao_version',  $this->config->version, $paramValue);
            $paramValue = str_replace('$zentao_account',  $this->app->user->account, $paramValue);
            $paramValue = str_replace('$zentao_product',  $job->product, $paramValue);
            $paramValue = str_replace('$zentao_repopath', $repo->path, $paramValue);

            $pipeline->$paramName = $paramValue;
        }

        foreach($extraParam as $paramName => $paramValue)
        {
            if(!isset($pipeline->$paramName)) $pipeline->$paramName = $paramValue;
        }

        $url = $this->loadModel('compile')->getBuildUrl($job);

        $compile = new stdclass();
        $compile->id     = $compileID;
        $compile->queue  = $this->loadModel('ci')->sendRequest($url->url, $pipeline, $url->userPWD);
        $compile->status = $compile->queue ? 'created' : 'create_fail';

        return $compile;
    }

    /**
     * Exec gitlab pipeline.
     *
     * @param  object $job
     * @access public
     * @return void
     */
    public function execGitlabPipeline($job)
    {
        $pipeline = json_decode($job->pipeline);

        $pipelineParams = new stdclass;
        $pipelineParams->ref = zget($pipeline, 'reference', '');
        if(!$pipelineParams->ref)
        {
            $project = $this->loadModel('gitlab')->apiGetSingleProject($job->server, $pipeline->project, false);
            $pipelineParams->ref = zget($project, 'default_branch', 'master');

            $pipeline->reference = $pipelineParams->ref;
            $this->dao->update(TABLE_JOB)->set('pipeline')->eq(json_encode($pipeline))->where('id')->eq($job->id)->exec();
        }

        $customParams = json_decode($job->customParam);
        $variables    = array();
        foreach($customParams as $paramName => $paramValue)
        {
            $variable = array();
            $variable['key']           = $paramName;
            $variable['value']         = $paramValue;
            $variable['variable_type'] = "env_var";

            $variables[] = $variable;
        }

        if(!empty($variables)) $pipelineParams->variables = $variables;

        $compile  = new stdclass();
        $pipeline = $this->loadModel('gitlab')->apiCreatePipeline($job->server, $pipeline->project, $pipelineParams);
        if(empty($pipeline->id))
        {
            $this->gitlab->apiErrorHandling($pipeline);
            $compile->status = 'create_fail';
        }

        if(!empty($pipeline->id))
        {
            $compile->queue  = $pipeline->id;
            $compile->status = zget($pipeline, 'status', 'create_fail');
        }

        return $compile;
    }

    /**
     * Get last tag of one repo.
     *
     * @param  object $repo
     * @param  object $job
     * @access public
     * @return void
     */
    public function getLastTagByRepo($repo, $job)
    {
        if($repo->SCM == 'Subversion')
        {
            $dirs = $this->loadModel('svn')->getRepoTags($repo, $job->svnDir);
            if($dirs)
            {
                end($dirs);
                $lastTag = current($dirs);
                return rtrim($repo->path , '/') . '/' . trim($job->svnDir, '/') . '/' . $lastTag;
            }
        }
        else
        {
            $tags = $this->loadModel('git')->getRepoTags($repo);
            if($tags)
            {
                end($tags);
                return current($tags);
            }
        }

        return '';
    }

     /**
     * Get sonarqube by RepoID.
     *
     * @param  array  $repoIDList
     * @param  int    $jobID
     * @param  bool   $showDeleted
     * @access public
     * @return array
     */
    public function getSonarqubeByRepo($repoIDList, $jobID = 0, $showDeleted = false)
    {
        return $this->dao->select('id,name,repo,deleted')->from(TABLE_JOB)
            ->where('frame')->eq('sonarqube')
            ->andWhere('repo')->in($repoIDList)
            ->beginIF(!$showDeleted)->andWhere('deleted')->eq('0')->fi()
            ->beginIF($jobID > 0)->andWhere('id')->ne($jobID)->fi()
            ->fetchAll('repo');
    }

    /**
     * Get job pairs by sonarqube projectkeys.
     *
     * @param  int    $sonarqubeID
     * @param  array  $projectKeys
     * @param  bool   $emptyShowAll
     * @param  bool   $showDeleted
     * @access public
     * @return array
     */
    public function getJobBySonarqubeProject($sonarqubeID, $projectKeys = array(), $emptyShowAll = false, $showDeleted = false)
    {
        return $this->dao->select('projectKey,id')->from(TABLE_JOB)
            ->where('frame')->eq('sonarqube')
            ->andWhere('sonarqubeServer')->eq($sonarqubeID)
            ->beginIF(!$showDeleted)->andWhere('deleted')->eq('0')->fi()
            ->beginIF(!empty($projectKeys) or !$emptyShowAll)->andWhere('projectKey')->in($projectKeys)->fi()
            ->fetchPairs();
    }

    /**
     * 判断按钮是否可点击。
     * Adjust the action is clickable.
     *
     * @param  object $object
     * @param  string $action
     * @param  string $module
     * @access public
     * @return bool
     */
    public static function isClickable($object, $action, $module = 'job')
    {
        $action = strtolower($action);

        if($module == 'job' && $action == 'exec') return $object->canExec;

        return true;
    }

    /**
     * Check if jenkins has enabled parameterized build.
     *
     * @param  string    $url
     * @param  string    $userPWD
     * @access public
     * @return bool
     */
    public function checkParameterizedBuild($url, $userPWD)
    {
        $response = common::http($url, null, array(CURLOPT_HEADER => true, CURLOPT_USERPWD => $userPWD));

        return strpos($response, 'hudson.model.ParametersDefinitionProperty') !== false;
    }
}
