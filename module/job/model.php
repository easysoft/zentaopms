<?php
declare(strict_types=1);
/**
 * The model file of job module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     job
 * @version     $Id$
 * @link        https://www.zentao.net
 * @property    jobTao $jobTao
 */
class jobModel extends model
{
    /**
     * 根据id获取流水线。
     * Get by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getByID(int $id): object
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
            if(strpos($job->pipeline, '/job/') === 0)
            {
                $job->rawPipeline = $job->pipeline;
                $job->pipeline    = trim(substr($job->pipeline, 5), '/');
            }
        }
        return $job;
    }

    /**
     * 获取流水线列表。
     * Get job list.
     *
     * @param  int    $repoID
     * @param  string $jobQuery
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $engine
     * @param  string $pipeline
     * @access public
     * @return array
     */
    public function getList(int $repoID = 0, string $jobQuery = '', string $orderBy = 'id_desc', ?object $pager = null, string $engine = '', string $pipeline = ''): array
    {
        return $this->dao->select('t1.*, t2.name as repoName, t3.name as jenkinsName')->from(TABLE_JOB)->alias('t1')
            ->leftJoin(TABLE_REPO)->alias('t2')->on('t1.repo=t2.id')
            ->leftJoin(TABLE_PIPELINE)->alias('t3')->on('t1.server=t3.id')
            ->where('t1.deleted')->eq('0')
            ->beginIF($repoID)->andWhere('t1.repo')->eq($repoID)->fi()
            ->beginIF($engine)->andWhere('t1.engine')->eq($engine)->fi()
            ->beginIF($pipeline)->andWhere('t1.pipeline')->eq($pipeline)->fi()
            ->beginIF(!empty($jobQuery))->andWhere($jobQuery)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

     /**
     * 获取流水线列表根据版本库ID。
     * Get job list by RepoID.
     *
     * @param  int    $repoID
     * @access public
     * @return array
     */
    public function getListByRepoID(int $repoID): array
    {
        return $this->dao->select('id, name, lastStatus')->from(TABLE_JOB)
            ->where('deleted')->eq('0')
            ->andWhere('repo')->eq($repoID)
            ->orderBy('id_desc')
            ->fetchAll('id');
    }

     /**
     * 获取流水线键值对根据版本库ID。
     * Get job pairs by RepoID.
     *
     * @param  int    $repoID
     * @param  string $engine gitlab|jenkins
     * @access public
     * @return array
     */
    public function getPairs(int $repoID, string $engine = ''): array
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
    public function getListByTriggerType(string $triggerType, array $repoIdList = array()): array
    {
        return $this->dao->select('*')->from(TABLE_JOB)
            ->where('deleted')->eq('0')
            ->andWhere('triggerType')->like('%' . $triggerType . '%')
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
    public function getTriggerConfig(object $job): string
    {
        $triggerList = array();
        if(strpos($job->triggerType, 'tag') !== false)
        {
            $triggerType = $this->lang->job->triggerTypeList['tag'];
            if(!empty($job->svnDir)) $triggerType = $this->lang->job->dirChange . "({$job->svnDir})";

            $triggerList[] = $triggerType;
        }

        if(strpos($job->triggerType, 'commit') !== false) $triggerList[] = "{$this->lang->job->triggerTypeList['commit']}({$job->comment})";

        if(strpos($job->triggerType, 'schedule') !== false)
        {
            $atDay = '';
            foreach(explode(',', $job->atDay) as $day) $atDay .= zget($this->lang->datepicker->dayNames, trim($day), '') . ',';
            $atDay = trim($atDay, ',');
            $triggerList[] = "{$this->lang->job->triggerTypeList['schedule']}({$atDay}, {$job->atTime})";
        }

        return implode('; ', $triggerList);
    }

    /**
     * Get trigger group.
     *
     * @param  string $triggerType
     * @param  array  $repoIdList
     * @access public
     * @return array
     */
    public function getTriggerGroup(string $triggerType, array $repoIdList): array
    {
        $jobs  = $this->getListByTriggerType($triggerType, $repoIdList);
        $group = array();
        foreach($jobs as $job) $group[$job->repo][$job->id] = $job;

        return $group;
    }

    /**
     * Create a job.
     *
     * @param  object $job
     * @access public
     * @return int|bool
     */
    public function create(object $job): int|bool
    {
        $repo = $this->loadModel('repo')->getByID($job->repo);
        $job  = $this->jobTao->getServerAndPipeline($job, $repo);
        if(dao::isError()) return false;

        $result = $this->jobTao->checkIframe($job);
        if(!$result) return false;

        $this->dao->insert(TABLE_JOB)->data($job)
            ->batchCheck($this->config->job->create->requiredFields, 'notempty')
            ->batchCheckIF($job->frame === 'sonarqube', "sonarqubeServer,projectKey", 'notempty')
            ->exec();
        if(dao::isError()) return false;

        return $this->dao->lastInsertId();
    }

    /**
     * Update a job.
     *
     * @param  int    $id
     * @access public
     * @return bool
     */
    public function update(int $id, object $job): bool
    {
        $repo = $this->loadModel('repo')->getByID($job->repo);
        if($this->app->rawMethod != 'trigger')
        {
            $job  = $this->jobTao->getServerAndPipeline($job, $repo);
            if(dao::isError()) return false;

            $result = $this->jobTao->checkIframe($job, $id);
            if(!$result) return false;
        }
        else
        {
            $this->jobTao->getSvnDir($job, $repo);
            $result = $this->jobTao->getCustomParam($job);
            if(!$result) return false;
        }

        $skipFields = 'triggerType,svnDir,comment,atDay,atTime,paramName,paramValue,autoRun';
        if($this->app->rawMethod == 'trigger') $skipFields = 'name,engine,repo,reference,frame,product,sonarqubeServer,projectKey,jkServer,jkTask,gitfoxpipeline';
        $this->dao->update(TABLE_JOB)->data($job, $skipFields)
            ->batchCheckIF($this->app->rawMethod != 'trigger', $this->config->job->edit->requiredFields, 'notempty')
            ->batchCheckIF(strpos($job->triggerType, 'schedule') !== false && $job->atDay !== '0', "atDay", 'notempty')
            ->batchCheckIF(strpos($job->triggerType, 'schedule') !== false, "atTime", 'notempty')
            ->batchCheckIF(strpos($job->triggerType, 'commit') !== false, "comment", 'notempty')
            ->batchCheckIF(strpos($job->triggerType, 'action') !== false, "triggerActions", 'notempty')
            ->batchCheckIF(($repo->SCM == 'Subversion' && strpos($job->triggerType, 'tag') !== false), "svnDir", 'notempty')
            ->batchCheckIF($job->frame === 'sonarqube', "sonarqubeServer,projectKey", 'notempty')
            ->where('id')->eq($id)
            ->exec();
        if(dao::isError()) return false;

        $this->initJob($id, $job);
        return true;
    }

    /**
     * 创建或者更新流水线的时候初始化工作。
     * Init when create or update job.
     *
     * @param  int    $id
     * @param  object $job
     * @access public
     * @return bool
     */
    public function initJob(int $id, object $job): bool
    {
        if(empty($id) || empty($job->triggerType)) return false;

        if(strpos($job->triggerType, 'schedule') !== false && strpos($job->atDay, date('w')) !== false)
        {
            $compiles = $this->dao->select('*')->from(TABLE_COMPILE)->where('job')->eq($id)->andWhere('LEFT(createdDate, 10)')->eq(date('Y-m-d'))->fetchAll();
            foreach($compiles as $compile)
            {
                if(!empty($compile->status)) continue;
                $this->dao->delete()->from(TABLE_COMPILE)->where('id')->eq($compile->id)->exec();
            }
            $this->loadModel('compile')->createByJob($id, $job->atTime, 'atTime');
        }

        if(strpos($job->triggerType, 'tag') !== false)
        {
            $repo = $this->loadModel('repo')->getByID($job->repo);
            if(!$repo) return false;

            $lastTag = $this->getLastTagByRepo($repo, $job);
            $this->updateLastTag($id, $lastTag);
        }

        return true;
    }

    /**
     * 执行流水线。
     * Exec job.
     *
     * @param  int    $id
     * @param  array  $extraParam
     * @param  string $triggerType  commit|tag|schedule
     * @access public
     * @return object|false
     */
    public function exec(int $id, array $extraParam = array(), string $triggerType = ''): object|false
    {
        $job = $this->dao->select('t1.*,t2.name as jenkinsName,t2.url,t2.account,t2.token,t2.password')
            ->from(TABLE_JOB)->alias('t1')
            ->leftJoin(TABLE_PIPELINE)->alias('t2')->on('t1.server=t2.id')
            ->where('t1.id')->eq($id)
            ->fetch();
        if(!$job) return false;

        if(($this->app->rawModule != 'job' || $this->app->rawMethod != 'exec') && !in_array($this->app->rawModule, array('mr', 'sonarqube')) && !empty($job->autoRun)) return false;

        $repo = $this->loadModel('repo')->getByID($job->repo);
        if(!$repo) return false;

        $method = 'exec' . ucfirst($job->engine) . 'Pipeline';
        if(!method_exists($this, $method)) return false;

        $compileID = 0;
        if(in_array($triggerType, array('', 'schedule')) && strpos($job->triggerType, 'schedule') !== false)
        {
            $compileID = $this->loadModel('compile')->createByJob($job->id, $job->atTime, 'atTime');
        }

        if(!$compileID && in_array($triggerType, array('', 'tag')) && strpos($job->triggerType, 'tag') !== false)
        {
            $job->lastTag = $this->getLastTagByRepo($repo, $job);

            $tag = '';
            if($job->lastTag)
            {
                $tag = $job->lastTag;
                $this->updateLastTag($job->id, $job->lastTag);
            }

            $compileID = $this->loadModel('compile')->createByJob($job->id, $tag, 'tag');
        }

        if(!$compileID && in_array($triggerType, array('', 'commit')) && (!$job->triggerType || strpos($job->triggerType, 'commit') !== false))
        {
            $compileID = $this->loadModel('compile')->createByJob($job->id);
        }

        $compile = $this->$method($job, $repo, $compileID, $extraParam);
        $compile->updateDate = helper::now();
        $this->dao->update(TABLE_COMPILE)->data($compile)->where('id')->eq($compileID)->exec();

        $this->dao->update(TABLE_JOB)
            ->set('lastExec')->eq(helper::now())
            ->set('lastStatus')->eq($compile->status)
            ->where('id')->eq($job->id)
            ->exec();

        $compile->id = $compileID;
        return $compile;
    }

    /**
     * 执行jenkins流水线。
     * Exec jenkins pipeline.
     *
     * @param  object    $job
     * @param  object    $repo
     * @param  int       $compileID
     * @param  array     $extraParam
     * @access public
     * @return object
     */
    public function execJenkinsPipeline(object $job, object $repo, int $compileID, array $extraParam = array()): object
    {
        $pipeline = new stdclass();
        $pipeline->PARAM_TAG   = '';
        $pipeline->ZENTAO_DATA = "compile={$compileID}";
        if(strpos($job->triggerType, 'tag') !== false) $pipeline->PARAM_TAG = $job->lastTag;

        /* Add custom parameters to the data. */
        if(!empty($job->customParam))
        {
            foreach(json_decode($job->customParam) as $paramName => $paramValue)
            {
                $paramValue = str_replace('$zentao_version',  $this->config->version, $paramValue);
                $paramValue = str_replace('$zentao_account',  $this->app->user->account, $paramValue);
                $paramValue = str_replace('$zentao_product',  (string)$job->product, $paramValue);
                $paramValue = str_replace('$zentao_repopath', $repo->path, $paramValue);

                $pipeline->$paramName = $paramValue;
            }
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
     * 执行gitlab流水线。
     * Exec gitlab pipeline.
     *
     * @param  object $job
     * @access public
     * @return object
     */
    public function execGitlabPipeline(object $job): object
    {
        $pipeline = json_decode($job->pipeline);

        /* Set pipeline run branch. */
        $pipelineParams = new stdclass;
        $pipelineParams->ref = zget($pipeline, 'reference', '');
        if(empty($pipelineParams->ref) && !empty($pipeline->project))
        {
            $project = $this->loadModel('gitlab')->apiGetSingleProject($job->server, (int)$pipeline->project, false);
            $pipelineParams->ref = zget($project, 'default_branch', 'master');

            $pipeline->reference = $pipelineParams->ref;
            $this->dao->update(TABLE_JOB)->set('pipeline')->eq(json_encode($pipeline))->where('id')->eq($job->id)->exec();
        }

        /* Set pipeline params. */
        $customParams = json_decode($job->customParam);
        $variables    = array();
        if($customParams)
        {
            foreach($customParams as $paramName => $paramValue)
            {
                $variable = array();
                $variable['key']           = $paramName;
                $variable['value']         = $paramValue;
                $variable['variable_type'] = "env_var";

                $variables[] = $variable;
            }
        }
        if(!empty($variables)) $pipelineParams->variables = $variables;

        /* Run pipeline. */
        $compile  = new stdclass();
        $pipeline = (object)$this->loadModel('gitlab')->apiCreatePipeline($job->server, (int)zget($pipeline, 'project', 0), $pipelineParams);
        if(empty($pipeline->id))
        {
            $this->gitlab->apiErrorHandling($pipeline);
            $compile->status = 'create_fail';
        }
        else
        {
            $compile->queue  = $pipeline->id;
            $compile->status = zget($pipeline, 'status', 'create_fail');
        }

        return $compile;
    }

    /**
     * 获取版本库最新tag。
     * Get last tag of one repo.
     *
     * @param  object $repo
     * @param  object $job
     * @access public
     * @return string
     */
    public function getLastTagByRepo(object $repo, object $job): string
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
     * 根据版本库获取sonarqube框架的流水线。
     * Get sonarqube by RepoID.
     *
     * @param  array  $repoIDList
     * @param  int    $jobID
     * @param  bool   $showDeleted
     * @access public
     * @return array
     */
    public function getSonarqubeByRepo(array $repoIDList, int $jobID = 0, bool $showDeleted = false)
    {
        return $this->dao->select('id,name,repo,deleted')->from(TABLE_JOB)
            ->where('frame')->eq('sonarqube')
            ->andWhere('repo')->in($repoIDList)
            ->beginIF(!$showDeleted)->andWhere('deleted')->eq('0')->fi()
            ->beginIF($jobID > 0)->andWhere('id')->ne($jobID)->fi()
            ->fetchAll('repo');
    }

    /**
     * 获取流水线键值对根据sonarqubeID或者sonarqube项目。
     * Get job pairs by sonarqube projectkeys.
     *
     * @param  int    $sonarqubeID
     * @param  array  $projectKeys
     * @param  bool   $emptyShowAll
     * @param  bool   $showDeleted
     * @access public
     * @return array|false
     */
    public function getJobBySonarqubeProject(int $sonarqubeID, array $projectKeys = array(), bool $emptyShowAll = false, bool $showDeleted = false): array|false
    {
        return $this->dao->select('projectKey,id')->from(TABLE_JOB)
            ->where('frame')->eq('sonarqube')
            ->andWhere('sonarqubeServer')->eq($sonarqubeID)
            ->beginIF(!$showDeleted)->andWhere('deleted')->eq('0')->fi()
            ->beginIF(!empty($projectKeys) or !$emptyShowAll)->andWhere('projectKey')->in($projectKeys)->fi()
            ->fetchPairs();
    }

    /**
     * 检查jenkins是否启用参数构建。
     * Check if jenkins has enabled parameterized build.
     *
     * @param  string $url
     * @param  string $userPWD
     * @access public
     * @return bool
     */
    public function checkParameterizedBuild(string $url, string $userPWD): bool
    {
        $response = common::http($url, null, array(CURLOPT_HEADER => true, CURLOPT_USERPWD => $userPWD));

        return strpos($response, 'hudson.model.ParametersDefinitionProperty') !== false;
    }

    /**
     * 更新流水线最新tag。
     * Update job last tag.
     *
     * @param  int       $jobID
     * @param  string    $lastTag
     * @access protected
     * @return void
     */
    public function updateLastTag(int $jobID, string $lastTag): void
    {
        $this->dao->update(TABLE_JOB)->set('lastTag')->eq($lastTag)->where('id')->eq($jobID)->exec();
    }

    /**
     * 通过代码库ID导入该代码库的流水线。
     * Import the pipeline of the repository with the repoID.
     *
     * @param  mixed $repoID
     * @return bool
     */
    public function import(string|int $repoID)
    {
        $repo = $this->loadModel('repo')->getByID((int)$repoID);
        if($repo->SCM != 'Gitlab') return false;

        $pipelines = $this->loadModel(strtolower($repo->SCM))->apiGetPipeline((int)$repo->serviceHost, (int)$repo->serviceProject, '');
        if(!is_array($pipelines) or empty($pipelines)) return false;

        $job = new stdclass();
        $job->name      = $repo->name;
        $job->repo      = $repoID;
        $job->product   = is_numeric($repo->product) ? $repo->product : explode(',', $repo->product)[0];
        $job->engine    = strtolower($repo->SCM);
        $job->server    = $repo->serviceHost;
        $job->createdBy = 'system';

        $jobs = $this->dao->select('id, pipeline')->from(TABLE_JOB)->where('repo')->eq($repoID)->fetchPairs();
        $existsPipelines = array();
        foreach($jobs as $pipeline)
        {
            if(empty($pipeline)) continue;

            $pipeline = json_decode($pipeline);
            if(empty($pipeline)) continue;

            $existsPipelines[] = $pipeline->reference;
        }

        $addedPipelines = array();
        foreach($pipelines as $pipeline)
        {
            if(!empty($pipeline->disabled)) continue;

            $ref = isset($pipeline->ref) ? $pipeline->ref : $pipeline->default_branch;
            if(in_array($ref, $existsPipelines)) continue;

            $createdDate = helper::now();
            if(isset($pipeline->created_at)) $createdDate = date('Y-m-d H:i:s', strtotime($pipeline->created_at));
            $job->createdDate = $createdDate;
            if(isset($pipeline->updated_at)) $job->editedDate = date('Y-m-d H:i:s', strtotime($pipeline->updated_at));

            $pipelineMeta  = array('project' => $repo->serviceProject, 'reference' => $ref);
            $job->pipeline = json_encode($pipelineMeta);

            $hash = md5($job->pipeline);
            if(array_key_exists($hash, array_flip($addedPipelines))) continue;
            $addedPipelines[] = $hash;

            $this->dao->insert(TABLE_JOB)->data($job)
                ->batchCheck($this->config->job->create->requiredFields, 'notempty')
                ->autoCheck()
                ->exec();
            if(dao::isError()) return false;

            $this->loadModel('action')->create('job', $this->dao->lastInsertId(), 'imported');
        }

        return true;
    }
}
