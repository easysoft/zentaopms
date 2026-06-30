<?php
declare(strict_types=1);
/**
 * The model file of pipeline module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     pipeline
 * @version     $Id$
 * @link        https://www.zentao.net
 * @property    pipelineTao $pipelineTao
 */
class pipelineModel extends model
{
    /**
     * 根据id获取流水线。
     * Get by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getByID(int $id): object|false
    {
        $pipeline = $this->dao->select('t1.*, t2.`variables`, t2.`data`, t3.`id` AS triggerID, t3.`event`, t3.`cron`')->from(TABLE_PIPELINE)->alias('t1')
            ->leftJoin(TABLE_PIPELINECONTENT)->alias('t2')->on('t1.id=t2.pipelineID')
            ->leftJoin(TABLE_PIPELINETRIGGER)->alias('t3')->on('t1.id=t3.pipelineID')
            ->where('t1.id')->eq($id)
            ->fetch();
        if(empty($pipeline)) return false;

        $pipeline->variables = empty($pipeline->variables) ? array() : json_decode($pipeline->variables);
        $pipeline->triggers  = $this->parseTriggers($pipeline->cron, $pipeline->event);

        $pipeline = $this->loadModel('file')->replaceImgURL($pipeline, 'desc');

        return $pipeline;
    }

    /**
     * 获取流水线列表。
     * Get pipeline list.
     *
     * @param  int    $spaceID
     * @param  int    $repoID
     * @param  string $type
     * @param  int    $pipelineID
     * @param  string $pipelineQuery
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList(int $spaceID = 0, int $repoID = 0, $type = '', string $pipelineQuery = '', string $orderBy = 'id_desc', ?object $pager = null): array
    {
        $pipelines = $this->dao->select('t1.*, t2.spaceID AS space, t2.name AS repoName, t3.variables as variables, t4.name AS providerName')->from(TABLE_PIPELINE)->alias('t1')
            ->leftJoin(TABLE_REPO)->alias('t2')->on('t1.repoID=t2.id')
            ->leftJoin(TABLE_PIPELINECONTENT)->alias('t3')->on('t1.id=t3.pipelineID')
            ->leftJoin(TABLE_PROVIDER)->alias('t4')->on('t1.providerID=t4.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.name')->ne('_codescan')
            ->beginIF($repoID)->andWhere('t1.repoID')->eq($repoID)->fi()
            ->beginIF(!empty($pipelineQuery))->andWhere($pipelineQuery)->fi()
            ->beginIF($spaceID)->andWhere('t1.spaceID')->eq($spaceID)->fi()
            ->beginIF($type == 'repo')->andWhere('t1.repoID')->ne(0)->fi()
            ->beginIF($type == 'space')->andWhere('t1.repoID')->eq(0)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        if(empty($pipelines)) return array();

        $executions = $this->getExecutionByPipeline(array_keys($pipelines), true);

        foreach($pipelines as $pipeline)
        {
            if(!empty($executions))
            {
                $execution = zget($executions, $pipeline->id, array());
                $pipeline->lastExecStatus = zget($execution, 'status', '');
                $pipeline->triggerPerson  = zget($execution, 'createdBy', '');
                $pipeline->triggerType    = zget($execution, 'trigger', '');
                $pipeline->lastExecDate   = zget($execution, 'finishedDate', '');
            }

            /* 非 gitfox 引擎的流水线状态均为激活。 */
            if($pipeline->engine != 'gitfox') $pipeline->status = 'active';
        }

        return $pipelines;
    }

    /**
     * 获取流水线列表。
     * Get pipeline list.
     *
     * @param  int    $spaceID
     * @param  int    $repoID
     * @param  string $type
     * @param  int    $pipelineID
     * @param  string $pipelineQuery
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getExecutionList(int $spaceID = 0, int $repoID = 0, string $type = '', int $pipelineID = 0, string $pipelineQuery = '', string $orderBy = 'id_desc', ?object $pager = null): array
    {
        return $this->dao->select('t1.*, t2.`scope`, t2.`spaceID` AS space, t2.`repoID` AS repo, t2.`name` AS pipelineName')->from(TABLE_PIPELINEEXEC)->alias('t1')
            ->leftJoin(TABLE_PIPELINE)->alias('t2')->on('t1.pipelineID=t2.id')
            ->where('1=1')
            ->beginIF($repoID)->andWhere('t2.repoID')->eq($repoID)->fi()
            ->beginIF(!empty($pipelineQuery))->andWhere($pipelineQuery)->fi()
            ->beginIF($spaceID)->andWhere('t2.spaceID')->eq($spaceID)->fi()
            ->beginIF($type == 'repo' && !$pipelineID)->andWhere('t2.repoID')->ne(0)->fi()
            ->beginIF($type == 'space' && !$pipelineID)->andWhere('t2.repoID')->eq(0)->fi()
            ->beginIF($pipelineID)->andWhere('t1.pipelineID')->eq($pipelineID)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 获取流水线执行记录。
     * Get pipeline execution.
     *
     * @param  array $pipelineIdList
     * @access public
     * @return array
     */
    public function getExecutionByPipeline(array $pipelineIdList, bool $showLast = false): array
    {
        $executions = $this->dao->select('*')->from(TABLE_PIPELINEEXEC)
            ->where('pipelineID')->in($pipelineIdList)
            ->fetchAll('id', false);
        if(empty($executions)) return array();
        if(!$showLast) return $executions;

        $executionDateList = array();
        $executionList     = array();
        foreach($executions as $execution)
        {
            $createTime = strtotime($execution->createdDate);
            if(!isset($executionDateList[$execution->pipelineID]))
            {
                $executionDateList[$execution->pipelineID] = $createTime;
                $executionList[$execution->pipelineID]     = $execution;
            }
            if($createTime > $executionDateList[$execution->pipelineID]) $executionList[$execution->pipelineID] = $execution;
        }

        return $executionList;
    }

     /**
     * 获取流水线列表根据版本库ID。
     * Get pipeline list by RepoID.
     *
     * @param  int    $repoID
     * @access public
     * @return array
     */
    public function getListByRepoID(int $repoID): array
    {
        return $this->dao->select('id, name, lastStatus')->from(TABLE_PIPELINE)
            ->where('deleted')->eq('0')
            ->andWhere('repoID')->eq($repoID)
            ->orderBy('id_desc')
            ->fetchAll('id');
    }

     /**
     * 获取流水线键值对根据版本库ID。
     * Get pipeline pairs by RepoID.
     *
     * @param  int    $repoID
     * @access public
     * @return array
     */
    public function getPairs(int $repoID): array
    {
        return $this->dao->select('id, name')->from(TABLE_PIPELINE)
            ->where('deleted')->eq('0')
            ->andWhere('repoID')->eq($repoID)
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
        return $this->dao->select('*')->from(TABLE_PIPELINE)
            ->where('deleted')->eq('0')
            ->beginIF($repoIdList)->andWhere('repo')->in($repoIdList)->fi()
            ->fetchAll('id');
    }

    /**
     * Get trigger config.
     *
     * @param  object $pipeline
     * @access public
     * @return string
     */
    public function getTriggerConfig(object $pipeline): string
    {
        $triggerList = array();
        if(strpos($pipeline->triggerType, 'tag') !== false)
        {
            $triggerType = $this->lang->pipeline->triggerTypeList['tag'];
            if(!empty($pipeline->svnDir)) $triggerType = $this->lang->pipeline->dirChange . "({$pipeline->svnDir})";

            $triggerList[] = $triggerType;
        }

        if(strpos($pipeline->triggerType, 'commit') !== false) $triggerList[] = "{$this->lang->pipeline->triggerTypeList['commit']}({$pipeline->comment})";

        if(strpos($pipeline->triggerType, 'schedule') !== false)
        {
            $atDay = '';
            foreach(explode(',', $pipeline->atDay) as $day) $atDay .= zget($this->lang->datepicker->dayNames, trim($day), '') . ',';
            $atDay = trim($atDay, ',');
            $triggerList[] = "{$this->lang->pipeline->triggerTypeList['schedule']}({$atDay}, {$pipeline->atTime})";
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
        $pipelines  = $this->getListByTriggerType($triggerType, $repoIdList);
        $group = array();
        foreach($pipelines as $pipeline) $group[$pipeline->repo][$pipeline->id] = $pipeline;

        return $group;
    }

    /**
     * Create a pipeline.
     *
     * @param  object $pipeline
     * @access public
     * @return int|bool
     */
    public function create(object $pipeline): int|bool
    {
        $check = empty($pipeline->repoID) ? "spaceID = {$pipeline->spaceID}" : "repoID = {$pipeline->repoID}";

        $pipeline = $this->loadModel('file')->processImgURL($pipeline, $this->config->pipeline->editor->create['id'], (string)$this->post->uid);
        $this->dao->insert(TABLE_PIPELINE)->data($pipeline)
            ->batchCheck($this->config->pipeline->create->requiredFields, 'notempty')
            ->check('name', 'unique', $check)
            ->autoCheck()
            ->exec();

        if(dao::isError()) return false;

        $pipelineID = $this->dao->lastInsertId();

        $copyPipelineID = (int)$this->post->existPipeline;
        $hasCopy        = $this->post->createType == 'copy' && !empty($copyPipelineID);

        if($pipelineID)
        {
            $content = new stdclass();
            $content->pipelineID  = $pipelineID;
            $content->createdBy   = $this->app->user->account;
            $content->createdDate = helper::now();
            if($hasCopy)
            {
                $copyPipelineContent = $this->dao->select('*')->from(TABLE_PIPELINECONTENT)->where('pipelineID')->eq($copyPipelineID)->fetch();

                $content->data      = $copyPipelineContent->data;
                $content->variables = $copyPipelineContent->variables;
            }

            if(!empty($pipeline->repoID))
            {
                if(!empty($content->variables))
                {
                    $variables = json_decode($content->variables);
                    foreach($variables as &$var)
                    {
                        if($var->key == 'gitRef')
                        {
                            $var->runtime      = true;
                            $var->defaultValue = '';
                            unset($var->value);
                        }
                    }
                    $content->variables = json_encode($variables);
                }
                else
                {
                    $variables = array(array('key' => 'gitRef', 'name' => $this->lang->pipeline->branch, 'value' => '', 'defaultValue' => '', 'runtime' => true));
                    $content->variables = json_encode($variables);
                }
            }

            $this->dao->insert(TABLE_PIPELINECONTENT)->data($content)->exec();
            if(dao::isError()) return false;

            if($hasCopy)
            {
                $copyTrigger = $this->dao->select('*')->from(TABLE_PIPELINETRIGGER)->where('pipelineID')->eq($copyPipelineID)->fetch();

                $content->event = $copyTrigger->event;
                $content->cron    = $copyTrigger->cron;
            }
            unset($content->data, $content->variables);
            $this->dao->insert(TABLE_PIPELINETRIGGER)->data($content)->exec();
            if(dao::isError()) return false;
        }

        $this->file->updateObjectID($this->post->uid, $pipelineID, 'pipeline');
        return $pipelineID;
    }

    /**
     * Update a pipeline.
     *
     * @param  int    $id
     * @access public
     * @return bool
     */
    public function update(int $id, object $pipeline): bool
    {
        $updatePipeline = $this->fetchByID($id);
        if(empty($updatePipeline)) return false;

        $check = empty($updatePipeline->repoID) ? "spaceID = {$updatePipeline->spaceID}" : "repoID = {$updatePipeline->repoID}";
        $check .= " AND id != $id";

        $this->dao->update(TABLE_PIPELINE)->data($pipeline)
            ->batchCheck($this->config->pipeline->edit->requiredFields, 'notempty')
            ->check('name', 'unique', $check)
            ->where('id')->eq($id)
            ->autoCheck()
            ->exec();

        return !dao::isError();
    }

    /**
     * 创建或者更新流水线的时候初始化工作。
     * Init when create or update pipeline.
     *
     * @param  int    $id
     * @param  object $pipeline
     * @access public
     * @return bool
     */
    public function initJob(int $id, object $pipeline): bool
    {
        if(empty($id) || empty($pipeline->triggerType)) return false;

        if(strpos($pipeline->triggerType, 'schedule') !== false && strpos($pipeline->atDay, date('w')) !== false)
        {
            $compiles = $this->dao->select('*')->from(TABLE_COMPILE)->where('pipeline')->eq($id)->andWhere('LEFT(createdDate, 10)')->eq(date('Y-m-d'))->fetchAll();
            foreach($compiles as $compile)
            {
                if(!empty($compile->status)) continue;
                $this->dao->delete()->from(TABLE_COMPILE)->where('id')->eq($compile->id)->exec();
            }
            $this->loadModel('compile')->createByJob($id, $pipeline->atTime, 'atTime');
        }

        if(strpos($pipeline->triggerType, 'tag') !== false)
        {
            $repo = $this->loadModel('repo')->getByID($pipeline->repo);
            if(!$repo) return false;

            $lastTag = $this->getLastTagByRepo($repo, $pipeline);
            $this->updateLastTag($id, $lastTag);
        }

        return true;
    }

    /**
     * 执行流水线。
     * Exec pipeline.
     *
     * @param  int    $id
     * @param  array  $extraParam
     * @param  string $triggerType  commit|tag|schedule
     * @access public
     * @return object|false
     */
    public function exec(int $id, object $variables): object|false
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, '/pipeline/executions');
        if(!empty($variables->gitRef)) $variables->gitRef = 'refs/heads/' . $variables->gitRef;

        $data = new stdClass();
        $data->pipelineID = $id;
        $data->params     = $variables;

        $response = json_decode(commonModel::http($url, $data, array(), $apiRoot->header, 'json'));
        return $this->gitfox->getResponse($response);
    }

    /**
     * 执行jenkins流水线。
     * Exec jenkins pipeline.
     *
     * @param  object    $pipeline
     * @param  object    $repo
     * @param  int       $compileID
     * @param  array     $extraParam
     * @access public
     * @return object
     */
    public function execJenkinsPipeline(object $pipeline, object $repo, int $compileID, array $extraParam = array()): object
    {
        $pipeline = new stdclass();
        $pipeline->PARAM_TAG   = '';
        $pipeline->ZENTAO_DATA = "compile={$compileID}";
        if(strpos($pipeline->triggerType, 'tag') !== false) $pipeline->PARAM_TAG = $pipeline->lastTag;

        /* Add custom parameters to the data. */
        if(!empty($pipeline->customParam))
        {
            foreach(json_decode($pipeline->customParam) as $paramName => $paramValue)
            {
                $paramValue = str_replace('$zentao_version',  $this->config->version, $paramValue);
                $paramValue = str_replace('$zentao_account',  $this->app->user->account, $paramValue);
                $paramValue = str_replace('$zentao_product',  (string)$pipeline->product, $paramValue);
                $paramValue = str_replace('$zentao_repopath', $repo->path, $paramValue);

                $pipeline->$paramName = $paramValue;
            }
        }

        foreach($extraParam as $paramName => $paramValue)
        {
            if(!isset($pipeline->$paramName)) $pipeline->$paramName = $paramValue;
        }

        $url = $this->loadModel('compile')->getBuildUrl($pipeline);

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
     * @param  object $pipeline
     * @access public
     * @return object
     */
    public function execGitlabPipeline(object $pipeline): object
    {
        $pipeline = json_decode($pipeline->pipeline);

        /* Set pipeline run branch. */
        $pipelineParams = new stdclass;
        $pipelineParams->ref = zget($pipeline, 'reference', '');
        if(empty($pipelineParams->ref) && !empty($pipeline->project))
        {
            $project = $this->loadModel('gitlab')->apiGetSingleProject($pipeline->server, (int)$pipeline->project, false);
            $pipelineParams->ref = zget($project, 'default_branch', 'master');

            $pipeline->reference = $pipelineParams->ref;
            $this->dao->update(TABLE_JOB)->set('pipeline')->eq(json_encode($pipeline))->where('id')->eq($pipeline->id)->exec();
        }

        /* Set pipeline params. */
        $customParams = json_decode($pipeline->customParam);
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
        $pipeline = (object)$this->loadModel('gitlab')->apiCreatePipeline($pipeline->server, (int)zget($pipeline, 'project', 0), $pipelineParams);
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
     * @param  object $pipeline
     * @access public
     * @return string
     */
    public function getLastTagByRepo(object $repo, object $pipeline): string
    {
        if($repo->SCM == 'Subversion')
        {
            $dirs = $this->loadModel('svn')->getRepoTags($repo, $pipeline->svnDir);
            if($dirs)
            {
                end($dirs);
                $lastTag = current($dirs);
                return rtrim($repo->path , '/') . '/' . trim($pipeline->svnDir, '/') . '/' . $lastTag;
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
     * @param  int    $pipelineID
     * @param  bool   $showDeleted
     * @access public
     * @return array
     */
    public function getSonarqubeByRepo(array $repoIDList, int $pipelineID = 0, bool $showDeleted = false)
    {
        return $this->dao->select('id,name,repo,deleted')->from(TABLE_JOB)
            ->where('frame')->eq('sonarqube')
            ->andWhere('repo')->in($repoIDList)
            ->beginIF(!$showDeleted)->andWhere('deleted')->eq('0')->fi()
            ->beginIF($pipelineID > 0)->andWhere('id')->ne($pipelineID)->fi()
            ->fetchAll('repo');
    }

    /**
     * 获取流水线键值对根据sonarqubeID或者sonarqube项目。
     * Get pipeline pairs by sonarqube projectkeys.
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
     * Update pipeline last tag.
     *
     * @param  int       $pipelineID
     * @param  string    $lastTag
     * @access protected
     * @return void
     */
    public function updateLastTag(int $pipelineID, string $lastTag): void
    {
        $this->dao->update(TABLE_JOB)->set('lastTag')->eq($lastTag)->where('id')->eq($pipelineID)->exec();
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

        $pipeline = new stdclass();
        $pipeline->name      = $repo->name;
        $pipeline->repo      = $repoID;
        $pipeline->product   = is_numeric($repo->product) ? $repo->product : explode(',', $repo->product)[0];
        $pipeline->engine    = strtolower($repo->SCM);
        $pipeline->server    = $repo->serviceHost;
        $pipeline->createdBy = 'system';

        $pipelines = $this->dao->select('id, pipeline')->from(TABLE_JOB)->where('repo')->eq($repoID)->fetchPairs();
        $existsPipelines = array();
        foreach($pipelines as $pipeline)
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
            $pipeline->createdDate = $createdDate;
            if(isset($pipeline->updated_at)) $pipeline->editedDate = date('Y-m-d H:i:s', strtotime($pipeline->updated_at));

            $pipelineMeta  = array('project' => $repo->serviceProject, 'reference' => $ref);
            $pipeline->pipeline = json_encode($pipelineMeta);

            $hash = md5($pipeline->pipeline);
            if(array_key_exists($hash, array_flip($addedPipelines))) continue;
            $addedPipelines[] = $hash;

            $this->dao->insert(TABLE_JOB)->data($pipeline)
                ->batchCheck($this->config->pipeline->create->requiredFields, 'notempty')
                ->autoCheck()
                ->exec();
            if(dao::isError()) return false;

            $this->loadModel('action')->create('pipeline', $this->dao->lastInsertId(), 'imported');
        }

        return true;
    }

    /**
     * 通过空间ID获取流水线
     * Get pipelines by spaceID list.
     *
     * @param  array $spaceIdList
     * @access public
     * @return array
     */
    public function getBySpaces(array $spaceIdList): array
    {
        return $this->dao->select('*')->from(TABLE_PIPELINE)
            ->where('spaceID')->in($spaceIdList)
            ->andWhere('deleted')->eq('0')
            ->fetchAll('id');
    }

    /**
     * 从provider导入流水线。
     * Import pipeline from a selected provider.
     *
     * @param  object $repo       The repo object
     * @param  object $formData   Form data: providerID, pipeline, name, desc
     * @access public
     * @return int|false
     */
    public function importFromProvider(object $repo, object $formData): int|false
    {
        $provider = $this->loadModel('provider')->getByID((int)$formData->providerID);
        if(!$provider) return false;

        $existPipeline = $this->dao->select('id')->from(TABLE_PIPELINE)
            ->where('spaceID')->eq($repo->spaceID)
            ->andWhere('repoID')->eq($repo->id)
            ->andWhere('name')->eq($formData->name)
            ->andWhere('deleted')->eq('0')
            ->fetch();
        if($existPipeline)
        {
            dao::$errors['name'][] = sprintf($this->lang->pipeline->nameExist, $formData->name);
            return false;
        }

        $this->loadModel('file')->processImgURL($formData, 'desc', (string)$this->post->uid);

        $engine   = strtolower($provider->type);
        $pipeline = new stdclass();
        $pipeline->name          = $formData->name;
        $pipeline->desc          = zget($formData, 'desc', '');
        $pipeline->repoID        = $repo->id;
        $pipeline->spaceID       = $repo->spaceID;
        $pipeline->engine        = $engine;
        $pipeline->providerID    = $provider->id;
        $pipeline->scope         = 'repo';
        $pipeline->status        = 'active';
        $pipeline->defaultBranch = $repo->defaultBranch;
        $pipeline->createdBy     = $this->app->user->account;
        $pipeline->createdDate   = helper::now();

        /* Set externalPipeline: Gitlab uses repo connector projectID, Jenkins uses form-selected pipeline. */
        if($engine == 'gitlab')
        {
            $connector = json_decode($repo->connector);
            $pipeline->externalPipeline = $connector && !empty($connector->projectID) ? $connector->projectID : '';
        }
        else
        {
            $pipeline->externalPipeline = $formData->pipeline;
        }

        $this->dao->insert(TABLE_PIPELINE)->data($pipeline, 'pipeline')
            ->batchCheck($this->config->pipeline->import->requiredFields, 'notempty')
            ->autoCheck()
            ->exec();

        if(dao::isError()) return false;

        $pipelineID = $this->dao->lastInsertID();

        $this->file->updateObjectID($this->post->uid, $pipelineID, 'pipeline');
        $this->loadModel('action')->create('pipeline', $pipelineID, 'imported');

        return $pipelineID;
    }

    /**
     * 判断按钮是否可点击。
     * Judge an action is clickable or not.
     *
     * @param  object $pipeline
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isClickable(object $pipeline, string $action): bool
    {
        $action = strtolower($action);
        if(in_array($action, array('execution', 'exec'))) return !empty($pipeline->status) && $pipeline->status != 'draft';

        return true;
    }

    /**
     * 获取步骤组.
     * Get step groups.
     *
     * @access public
     * @return object|bool|array
     */
    public function getStepGroups(): object|bool|array
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, "/pipeline/steps/grouped");

        $response = json_decode(commonModel::http($url, null, array(), $apiRoot->header));
        return $this->gitfox->getResponse($response);
    }

    /**
     * 获取步骤详情.
     * Get step details.
     *
     * @param  string $stepName
     * @access public
     * @return string
     */
    public function getStepSchema(string $stepName): string
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, "/pipeline/steps/{$stepName}/schema");

        $response = json_decode(commonModel::http($url, null, array(), $apiRoot->header));
        if(empty($response) || empty($response->code) || $response->code != 'success')
        {
            dao::$errors['apiMessage'] = !empty($response->message) ? $response->message : $this->lang->error->httpServerError;
            return '';
        }
        return empty($response->data) ? '' : $response->data;
    }

    /**
     * 修改流水线内容。
     * Update pipeline content.
     *
     * @param  int $pipelineID
     * @param  object $content
     * @access public
     * @return bool
     */
    public function updateContent(int $pipelineID, object $content): bool
    {
        $this->dao->update(TABLE_PIPELINECONTENT)->data($content)
            ->where('pipelineID')->eq($pipelineID)
            ->exec();

        return !dao::isError();
    }

    /**
     * 更新触发器.
     * Update trigger.
     *
     * @param  int $pipelineID
     * @param  int $triggerID
     * @param  object $trigger
     * @access public
     * @return void
     */
    public function apiUpdateTrigger(int $pipelineID, int $triggerID, object $trigger)
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, "/pipelines/{$pipelineID}/triggers/{$triggerID}");

        $response = json_decode(commonModel::http($url, $trigger, array(CURLOPT_CUSTOMREQUEST => 'PUT'), $apiRoot->header, 'json', 'PUT'));
        if(empty($response) || empty($response->code) || $response->code != 'success')
        {
            dao::$errors['apiMessage'] = !empty($response->message) ? $response->message : $this->lang->error->httpServerError;
            return false;
        }
        return !dao::isError();
    }

    /**
     * 创建触发器.
     * Create trigger.
     *
     * @param  int $pipelineID
     * @param  object $trigger
     * @access public
     * @return void
     */
    public function apiCreateTrigger(int $pipelineID, object $trigger)
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, "/pipelines/{$pipelineID}/triggers");

        $response = json_decode(commonModel::http($url, $trigger, array(), $apiRoot->header, 'json', 'POST'));
        if(empty($response) || empty($response->code) || $response->code != 'success')
        {
            dao::$errors['apiMessage'] = !empty($response->message) ? $response->message : $this->lang->error->httpServerError;
            return false;
        }
        return !dao::isError();
    }

    /**
     * 解析触发规则。
     * Parse triggers.
     *
     * @param  string $cron
     * @param  string $events
     * @access public
     * @return array
     */
    public function parseTriggers(string $cron, string $events): array
    {
        $cron   = explode('|', $cron);

        $triggers = array();
        if(!empty($events))
        {
            $trigger = new stdclass;
            $trigger->type  = 'event';
            $trigger->value = $events;
            $triggers[] = $trigger;
        }

        if(!empty($cron))
        {
            foreach($cron as $item)
            {
                $itemArr = explode(' ', $item);
                if(count($itemArr) != 5) continue;

                $trigger = new stdclass;
                $trigger->type  = $itemArr[4] == '*' ? 'month' : 'week';
                $trigger->value = $itemArr[4] == '*' ? $itemArr[2] : $itemArr[4];
                $trigger->time  = $itemArr[1] == '*' ? '' : $itemArr[1] . ':' . $itemArr[0];
                $triggers[] = $trigger;
            }
        }

        return $triggers;
    }

    /**
     * 获取执行详情。
     * Get execution info.
     *
     * @param  int $execID
     * @access public
     * @return object|false
     */
    public function apiGetExecInfo(int $execID): object|false
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, "/pipeline/executions/{$execID}");

        $response = json_decode(commonModel::http($url, null, array(), $apiRoot->header));
        $response = $this->gitfox->getResponse($response);

        if(dao::isError()) return false;
        return $response;
    }

    /**
     * 通过执行ID获取执行详情。
     * Get execution info by execID.
     *
     * @param  int $execID
     * @access public
     * @return object|false
     */
    public function getExecByID(int $execID): object|false
    {
        return $this->dao->select('*')->from(TABLE_PIPELINEEXEC)
            ->where('id')->eq($execID)
            ->fetch();
    }

    /**
     * 秒数格式化方法
     * Second format.
     *
     * @access public
     * @param int $seconds 秒数（必须为非负整数）
     * @return string 格式化后的时间字符串
     */
    public function formatSeconds($seconds): string
    {
        // 确保入参是数字且非负
        $seconds = max(0, (int)$seconds);

        $hour   = 3600; // 1小时 = 3600秒
        $minute = 60;   // 1分钟 = 60秒

        // 小于1分钟
        if($seconds < $minute) return "{$seconds}s";

        // 小于1小时
        if ($seconds < $hour)
        {
            $m = intval($seconds / $minute);
            $s = $seconds % $minute;
            return "{$m}m{$s}s";
        }

        // 大于等于1小时
        $h      = intval($seconds / $hour);
        $remain = $seconds % $hour;
        $m      = intval($remain / $minute);
        $s      = $remain % $minute;

        return "{$h}h{$m}m{$s}s";
    }
}
