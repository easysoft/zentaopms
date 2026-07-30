<?php
declare(strict_types=1);
/**
 * The model file of pipeline module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
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
        $pipeline = $this->dao->select('t1.*, t2.`variables`, t2.`data`, t3.`id` AS triggerID, t3.`event`, t3.`cron`, t3.`comment`')->from(TABLE_PIPELINE)->alias('t1')
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
        $pipeline = $this->getByID($id);
        if(empty($pipeline)) return false;
        if($pipeline->engine == 'gitlab')
        {
            $this->execGitlabPipeline($pipeline);
            return !dao::isError() ? new stdclass() : false;
        }
        if($pipeline->engine == 'jenkins')
        {
            $this->execJenkinsPipeline($pipeline);
            return !dao::isError() ? new stdclass() : false;
        }

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
     * 执行 GitLab 流水线。
     * Exec gitlab pipeline.
     *
     * @param  object $pipeline
     * @param  string $triggerType
     * @access public
     * @return bool
     */
    public function execGitlabPipeline(object $pipeline, string $triggerType = 'manual'): bool
    {
        $provider = $this->loadModel('provider')->getByID($pipeline->providerID);

        $params = new stdclass();
        $params->ref       = $pipeline->defaultBranch ?: 'main';
        $params->variables = array();
        if(!empty($pipeline->customParam))
        {
            $customParams = json_decode($pipeline->customParam, true);
            if(!empty($customParams))
            {
                foreach($customParams as $key => $value)
                {
                    $params->variables[] = array(
                        "key"           => $key,
                        "value"         => $value,
                        "variable_type" => "env_var"
                    );
                }
            }
        }

        $result    = $this->loadModel('gitlab')->apiCreatePipeline($provider->url, $provider->token, $pipeline->externalPipeline, (object)$params);
        $execution = new stdclass();
        if(empty($result->id))
        {
            $this->gitlab->apiErrorHandling($result);
            $errors = dao::getError();
            $errorMessage = '';
            foreach((array)$errors as $error)
            {
                if(is_string($error)) $errorMessage .= $error . "\n";
                if(is_array($error))  $errorMessage .= implode("\n", $error) . "\n";
            }
            dao::$errors['apiMessage'] = trim($errorMessage) ? $this->lang->pipeline->execFail . '：' . trim($errorMessage) : $this->lang->pipeline->execFail;
            $execution->status = 'create_fail';
        }
        else
        {
            $execution->number = $result->id;
            $execution->status = zget($result, 'status', 'create_fail');
        }

        $execution->pipelineID   = $pipeline->id;
        $execution->trigger      = $triggerType;
        $execution->commit       = '';
        $execution->ref          = $params->ref;
        $execution->params       = json_encode($params);
        $execution->startedDate  = helper::now();
        $execution->createdBy    = $this->app->user->account;
        $execution->createdDate  = helper::now();

        $this->dao->insert(TABLE_PIPELINEEXEC)->data($execution)->exec();
        return !dao::isError();
    }

    /**
     * 执行 Jenkins 流水线。
     * Exec jenkins pipeline.
     *
     * @param  object $pipeline
     * @param  string $triggerType
     * @access public
     * @return bool
     */
    public function execJenkinsPipeline(object $pipeline, string $triggerType = 'manual'): bool
    {
        $provider = $this->loadModel('provider')->getByID($pipeline->providerID);
        $baseUrl  = $provider->url . $pipeline->externalPipeline;
        $userPWD  = $provider->account . ':' . $provider->token;

        $params = array();
        $params['ref'] = $pipeline->defaultBranch ?: 'main';
        if(!empty($pipeline->customParam))
        {
            $customParams = json_decode($pipeline->customParam, true);
            if(!empty($customParams))
            {
                foreach($customParams as $key => $value)
                {
                    $params[$key] = $value;
                }
            }
        }

        $isParameterized = $this->loadModel('jenkins')->checkParameterizedBuild($baseUrl, $userPWD);
        $url = $baseUrl . ($isParameterized ? 'buildWithParameters' : 'build');

        $result = $this->jenkins->apiCreatePipeline($url, (object)$params, $userPWD);
        if(empty($result))
        {
            dao::$errors['apiMessage'] = $this->lang->pipeline->execFail;
            $this->dao->insert(TABLE_PIPELINEEXEC)->data($execution)->exec();
            return false;
        }

        $execution = new stdclass();
        $execution->pipelineID   = $pipeline->id;
        $execution->trigger      = $triggerType;
        $execution->commit       = '';
        $execution->ref          = $params['ref'];
        $execution->params       = json_encode($params);
        $execution->startedDate  = helper::now();
        $execution->createdBy    = $this->app->user->account;
        $execution->createdDate  = helper::now();
        $execution->status       = 'created';
        $execution->number       = 0;

        $this->dao->insert(TABLE_PIPELINEEXEC)->data($execution)->exec();
        $executionID = $this->dao->lastInsertId();

        $number   = 0;
        $maxRetry = 10;
        for($i = 0; $i < $maxRetry; $i ++)
        {
            sleep(1);
            $number = $this->jenkins->apiGetJobNumberByQueueID($provider->url, $result, $userPWD);
            if(!empty($number)) break;
        }

        if(empty($number))
        {
            dao::$errors['apiMessage'] = $this->lang->pipeline->execFail;
            $this->dao->update(TABLE_PIPELINEEXEC)->set('status')->eq('create_fail')->where('id')->eq($executionID)->exec();
            return false;
        }

        $this->dao->update(TABLE_PIPELINEEXEC)->set('number')->eq($number)->where('id')->eq($executionID)->exec();
        return !dao::isError();
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
            if(empty($formData->pipeline)) dao::$errors['pipeline'][] = sprintf($this->lang->error->notempty, $this->lang->pipeline->pipeline);
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

        if($action === 'edit')
        {
            return $pipeline->engine != 'gitfox';
        }
        if($action === 'arrange')
        {
            return $pipeline->engine == 'gitfox';
        }
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
     * Save a trigger to ops_triggers table.
     *
     * @param  object $trigger
     * @access public
     * @return void
     */
    public function saveTrigger(object $trigger): void
    {
        $this->dao->insert(TABLE_PIPELINETRIGGER)->data($trigger)->exec();
    }

    /**
     * Get triggers by pipeline ID.
     *
     * @param  int $pipelineID
     * @access public
     * @return array
     */
    public function getTriggers(int $pipelineID): array
    {
        return $this->dao->select('*')->from(TABLE_PIPELINETRIGGER)
            ->where('pipelineID')->eq($pipelineID)
            ->fetchAll();
    }

    /**
     * Update a single field of trigger.
     *
     * @param  int    $triggerID
     * @param  string $field
     * @param  string $value
     * @access public
     * @return void
     */
    public function updateTriggerField(int $triggerID, string $field, string $value): void
    {
        $this->dao->update(TABLE_PIPELINETRIGGER)
            ->set($field)->eq($value)
            ->set('editedBy')->eq($this->app->user->account)
            ->set('editedDate')->eq(helper::now())
            ->where('id')->eq($triggerID)
            ->exec();
    }

    /**
     * Delete a trigger.
     *
     * @param  int $triggerID
     * @access public
     * @return void
     */
    public function deleteTrigger(int $triggerID): void
    {
        $this->dao->delete()->from(TABLE_PIPELINETRIGGER)->where('id')->eq($triggerID)->exec();
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

    /**
     * 处理 webhook 请求(事件触发)。
     * Handle received GitLab webhook.
     *
     * @param  string $event
     * @param  object $data
     * @param  object $pipeline
     * @access public
     * @return bool
     */
    public function handleWebhook(string $event, object $data, object $pipeline): bool
    {
        $eventMap = array(
            'Push Hook'          => 'push',
            'Tag Push Hook'      => 'tag_push',
            'Merge Request Hook' => 'merge_requests',
        );
        if(!isset($eventMap[$event])) return false;

        $eventType = $eventMap[$event];
        $eventList = explode(',', $pipeline->event);
        if(!in_array($eventType, $eventList)) return false;

        if($eventType == 'push')
        {
            if(empty($data->commits)) return false;

            $matched = false;
            if(empty($pipeline->comment))
            {
                $matched = true;
            }
            else
            {
                $keywords = array_filter(array_map('trim', explode(',', $pipeline->comment)));
                foreach($data->commits as $commit)
                {
                    foreach($keywords as $keyword)
                    {
                        if(strpos($commit->message, $keyword) !== false)
                        {
                            $matched = true;
                            break 2;
                        }
                    }
                }
            }

            if(!$matched) return false;
        }

        $ref = '';
        if($eventType == 'merge_requests')
        {
            $ref = $data->object_attributes->target_branch ?? '';
        }
        else
        {
            $ref = $data->ref ?? '';
        }

        $provider = $this->loadModel('provider')->getByID($pipeline->providerID);
        $params = new stdclass();
        $params->ref = $ref ?: $pipeline->defaultBranch;
        $params->variables = array();
        if(!empty($pipeline->customParam))
        {
            $customParams = json_decode($pipeline->customParam, true);
            if(!empty($customParams))
            {
                foreach($customParams as $key => $value)
                {
                    $params->variables[] = array(
                        "key" => $key,
                        "value" => $value,
                        "variable_type" => "env_var"
                    );
                }
            }
        }

        $result    = $this->loadModel('gitlab')->apiCreatePipeline($provider->url, $provider->token, $pipeline->externalPipeline, (object)$params);
        $execution = new stdclass();
        if(empty($result->id))
        {
            $this->gitlab->apiErrorHandling($result);
            $execution->status = 'create_fail';
        }
        else
        {
            $execution->number  = $result->id;
            $execution->status = zget($result, 'status', 'create_fail');
        }

        $execution->pipelineID   = $pipeline->id;
        $execution->trigger      = $eventType;
        $execution->commit       = $data->after ?? $data->checkout_sha ?? '';
        $execution->ref          = $params->ref;
        $execution->params       = json_encode($params);
        $execution->startedDate  = helper::now();
        $execution->createdBy    = 'admin';
        $execution->createdDate  = helper::now();

        $this->dao->insert(TABLE_PIPELINEEXEC)->data($execution)->exec();
        return !dao::isError();
    }

    /**
     * 调用 gitfox 接口添加定时任务。
     * Add cron job via gitfox API.
     *
     * @param  int    $pipelineID
     * @param  string $cronDef
     * @param  string $engine
     * @access public
     * @return bool
     */
    public function addTriggerCronJob(int $pipelineID, string $cronDef, string $engine = 'gitlab'): bool
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        if(!$apiRoot) return false;

        $url  = sprintf($apiRoot->url, '/cron/jobs');
        $data = new stdclass();
        $data->jobName            = "{$engine}:pipeline:cron:{$pipelineID}";
        $data->jobType            = "{$engine}:pipeline:cron-trigger";
        $data->jobData            = (string)$pipelineID;
        $data->cronDef            = $cronDef;
        $data->maxDurationSeconds = 300;

        $response = json_decode(commonModel::http($url, $data, array(), $apiRoot->header, 'json', 'POST'));
        if(empty($response) || empty($response->code) || $response->code != 'success')
        {
            dao::$errors['apiMessage'] = !empty($response->message) ? $response->message : $this->lang->error->httpServerError;
            return false;
        }
        return !dao::isError();
    }

    /**
     * 调用 gitfox 接口删除定时任务。
     * Delete cron job via gitfox API.
     *
     * @param  int    $pipelineID
     * @param  string $engine
     * @access public
     * @return bool
     */
    public function deleteTriggerCronJob(int $pipelineID, string $engine = 'gitlab'): bool
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        if(!$apiRoot) return false;

        $url  = sprintf($apiRoot->url, '/cron/jobs');
        $data = new stdclass();
        $data->jobName = "{$engine}:pipeline:cron:{$pipelineID}";

        $response = json_decode(commonModel::http($url, $data, array(CURLOPT_CUSTOMREQUEST => 'DELETE'), $apiRoot->header, 'json', 'DELETE'));
        if(empty($response) || empty($response->code) || $response->code != 'success')
        {
            dao::$errors['apiMessage'] = !empty($response->message) ? $response->message : $this->lang->error->httpServerError;
            return false;
        }
        return !dao::isError();
    }

    /**
     * 迁移流水线。
     * Migrate jobs to ops pipelines.
     *
     * @access public
     * @return bool
     */
    public function migrateJobsToOpsPipelines(): bool
    {
        $repos = $this->dao->select('*')->from(TABLE_REPO)->fetchAll('id');
        $jobs  = $this->dao->select('*')->from($this->config->db->prefix . 'job')->fetchAll('id');

        $pipelineNames = array();
        foreach($jobs as $job)
        {
            if(empty($job->repo) && !isset($repos[$job->repo])) continue;

            $repo = $repos[$job->repo];
            $legacyPipeline = json_decode((string)$job->pipeline);

            $externalPipeline = '';
            $defaultBranch    = '';
            if($job->engine == 'gitlab')
            {
                if(!empty($legacyPipeline->project)) $externalPipeline = zget($legacyPipeline, 'project', '');
                if(!empty($legacyPipeline->reference)) $defaultBranch  = zget($legacyPipeline, 'reference', '');
            }
            elseif($job->engine == 'jenkins')
            {
                $externalPipeline = $job->pipeline;
            }

            $pipeline = new stdclass();
            $pipeline->name             = in_array($job->name, $pipelineNames) ? $job->name . '-' . $job->id : (string)$job->name;
            $pipeline->engine           = strtolower((string)$job->engine);
            $pipeline->providerID       = (int)$job->server;
            $pipeline->scope            = 'repo';
            $pipeline->spaceID          = (int)$repo->spaceID;
            $pipeline->repoID           = (int)$repo->id;
            $pipeline->desc             = '';
            $pipeline->status           = 'active';
            $pipeline->latestVersion    = 0;
            $pipeline->defaultBranch    = $defaultBranch;
            $pipeline->yamlPath         = '';
            $pipeline->customParam      = empty($job->customParam) ? '' : $job->customParam;
            $pipeline->lastExec         = empty($job->lastExec) ? null : $job->lastExec;
            $pipeline->lastResult       = (string)$job->lastStatus;
            $pipeline->externalPipeline = $externalPipeline;
            $pipeline->createdBy        = (string)$job->createdBy;
            $pipeline->createdDate      = empty($job->createdDate) ? helper::now() : $job->createdDate;
            $pipeline->editedBy         = (string)$job->editedBy;
            $pipeline->editedDate       = empty($job->editedDate) ? null : $job->editedDate;
            $pipeline->deleted          = (int)$job->deleted;

            $this->dao->insert(TABLE_PIPELINE)->data($pipeline)->exec();
            if(dao::isError()) return false;

            $pipelineNames[] = $job->name;
        }

        return dao::isError();
    }

    /**
     * 获取外部流水线。
     * Get external pipelines.
     *
     * @param  array $statusList
     * @access public
     * @return array
     */
    public function getExternalPipeline(array $statusList = array()): array
    {
        return $this->dao->select('t1.*, t2.`providerID`, t2.`externalPipeline`, t2.`engine`')->from(TABLE_PIPELINEEXEC)->alias('t1')
            ->leftJoin(TABLE_PIPELINE)->alias('t2')->on('t1.pipelineID=t2.id')
            ->where('t2.deleted')->eq(0)
            ->andWhere('t2.engine')->in('gitlab,jenkins')
            ->andWhere('t1.number')->ne(0)
            ->beginIF($statusList)->andWhere('t1.status')->in($statusList)->fi()
            ->fetchAll('id');
    }

    /**
     * 同步外部流水线。
     * Sync external pipelines.
     *
     * @access public
     * @return bool
     */
    public function syncExternalPipeline(): bool
    {
        $syncStatus = array('', 'created', 'pending', 'running', 'building');
        $externalPipelines = $this->getExternalPipeline($syncStatus);
        if(empty($externalPipelines)) return true;

        $providerList = $this->loadModel('provider')->getList();

        $this->loadModel('jenkins');
        $this->loadModel('gitlab');
        foreach($externalPipelines as $externalPipeline)
        {
            if(!isset($providerList[$externalPipeline->providerID])) continue;

            $provider = $providerList[$externalPipeline->providerID];
            $engine   = $externalPipeline->engine;
            $execInfo = $this->$engine->apiGetExecInfo($externalPipeline->number, $externalPipeline->externalPipeline, $provider);
            if(empty($execInfo)) continue;

            $syncData = new stdclass();
            if($engine == 'jenkins')
            {
                $syncData->status       = strtolower(zget($execInfo, 'result', ''));
                $syncData->finishedDate = empty($execInfo->timestamp) ? null : date('Y-m-d H:i:s', intval($execInfo->timestamp / 1000));
                $syncData->duration     = zget($execInfo, 'estimatedDuration', 0);
            }
            elseif($engine == 'gitlab')
            {
                $syncData->status       = strtolower(zget($execInfo, 'status', ''));
                $syncData->status       = $syncData->status == 'failed' ? 'failure' : $syncData->status;
                $syncData->finishedDate = empty($execInfo->finished_at) ? null : date('Y-m-d H:i:s', strtotime($execInfo->finished_at));
                $syncData->duration     = zget($execInfo, 'duration', 0);
            }

            $this->dao->update(TABLE_PIPELINEEXEC)->data($syncData)->where('id')->eq($externalPipeline->id)->exec();
        }

        return !dao::isError();
    }
}
