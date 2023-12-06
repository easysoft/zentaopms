<?php
declare(strict_types=1);
class convertTao extends convertModel
{
    /**
     * 构建user数据。
     * Build user data.
     *
     * @param  array $data
     * @access protected
     * @return object
     */
    protected function buildUserData(array $data): object
    {
        $user = new stdclass();
        $user->account  = $data['lowerUserName'];
        $user->realname = $data['lowerDisplayName'];
        $user->email    = $data['emailAddress'];
        $user->join     = $data['createdDate'];

        return $user;
    }

    /**
     * 构建project数据。
     * Build project data.
     *
     * @param  array $data
     * @access protected
     * @return object
     */
    protected function buildProjectData(array $data): object
    {
        $project = new stdclass();
        $project->ID          = $data['id'];
        $project->pname       = $data['name'];
        $project->pkey        = $data['key'];
        $project->ORIGINALKEY = $data['originalkey'];
        $project->DESCRIPTION = isset($data['description']) ? $data['description'] : '';
        $project->LEAD        = $data['lead'];

        return $project;
    }

    /**
     * 构建issue数据。
     * Build issue data.
     *
     * @param  array $data
     * @access protected
     * @return object
     */
    protected function buildIssueData(array $data): object
    {
        $issue = new stdclass();
        $issue->ID          = $data['id'];
        $issue->SUMMARY     = $data['summary'];
        $issue->PRIORITY    = $data['priority'];
        $issue->PROJECT     = $data['project'];
        $issue->issuestatus = $data['status'];
        $issue->CREATED     = $data['created'];
        $issue->CREATOR     = $data['creator'];
        $issue->issuetype   = $data['type'];
        $issue->ASSIGNEE    = isset($data['assignee']) ? $data['assignee'] : '';
        $issue->RESOLUTION  = isset($data['resolution']) ? $data['resolution'] : '';
        $issue->issuenum    = $data['number'];
        $issue->DESCRIPTION = isset($data['description']) ? $data['description'] : '';

        return $issue;
    }

    /**
     * 构建build数据。
     * Build build data.
     *
     * @param  array $data
     * @access protected
     * @return object
     */
    protected function buildBuildData(array $data): object
    {
        $build = new stdclass();
        $build->ID          = $data['id'];
        $build->PROJECT     = $data['project'];
        $build->vname       = $data['name'];
        $build->RELEASEDATE = isset($data['releasedate']) ? $data['releasedate'] : '';
        $build->DESCRIPTION = isset($data['description']) ? $data['description'] : '';

        return $build;
    }

    /**
     * 构建issuelink数据。
     * Build issuelink data.
     *
     * @param  array $data
     * @access protected
     * @return object
     */
    protected function buildIssuelinkData(array $data): object
    {
        $issueLink = new stdclass();
        $issueLink->LINKTYPE    = $data['linktype'];
        $issueLink->SOURCE      = $data['source'];
        $issueLink->DESTINATION = $data['destination'];

        return $issueLink;
    }

    /**
     * 构建action数据。
     * Build action data.
     *
     * @param  array $data
     * @access protected
     * @return object
     */
    protected function buildActionData(array $data): object
    {
        $action = new stdclass();
        $action->issueid    = $data['issue'];
        $action->actionbody = $data['body'];
        $action->AUTHOR     = $data['author'];
        $action->CREATED    = $data['created'];

        return $action;
    }

    /**
     * 构建file数据。
     * Build file data.
     *
     * @param  array $data
     * @access protected
     * @return object
     */
    protected function buildFileData(array $data): object
    {
        $file = new stdclass();
        $file->issueid  = $data['issue'];
        $file->ID       = $data['id'];
        $file->FILENAME = $data['filename'];
        $file->MIMETYPE = $data['mimetype'];
        $file->FILESIZE = $data['filesize'];
        $file->CREATED  = $data['created'];
        $file->AUTHOR   = $data['author'];

        return $file;
    }

    /**
     * 获取jira用户。
     * Get jira account.
     *
     * @param  string $userKey
     * @param  string $method db|file
     * @access protected
     * @return string
     */
    protected function getJiraAccount(string $userKey, string $method = 'db'): string
    {
        if(strpos($userKey, 'JIRAUSER') === false) return $userKey;

        if($method == 'db')
        {
            return $this->dao->dbh($this->sourceDBH)->select('lower_user_name')->from(JIRA_USER)->where('user_key')->eq($userKey)->fetch('lower_user_name');
        }

        $appUsers = $this->getJiraAppUser();
        return zget($appUsers, $userKey, $userKey);
    }

    /**
     * 获取jira用户键值对。
     * Get jira app user pairs.
     *
     * @access protected
     * @return array
     */
    protected function getJiraAppUser(): array
    {
        $xmlContent = file_get_contents($this->app->getTmpRoot() . 'jirafile/applicationuser.xml');
        $xmlContent = preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $xmlContent);
        $parsedXML  = simplexml_load_string($xmlContent, 'SimpleXMLElement', LIBXML_NOCDATA);

        $pairs = array();
        $parsedXML = $this->object2Array($parsedXML);
        foreach($parsedXML as $key => $xmlArray)
        {
            if(strtolower($key) != 'applicationuser') continue;
            foreach($xmlArray as $key => $attributes)
            {
                if(is_numeric($key))
                {
                    foreach($attributes as $value)
                    {
                        if(!is_array($value)) continue;
                        if(!isset($value['userKey'])) continue;
                        $pairs[$value['userKey']] = $value['lowerUserName'];
                    }
                }
                else
                {
                    $pairs[$attributes['userKey']] = $attributes['lowerUserName'];
                }
            }
        }

        return $pairs;
    }

    /**
     * 将对象转换为数组。
     * Convert object to array.
     *
     * @param  object|array $parsedXML
     * @access protected
     * @return array
     */
    protected function object2Array(object|array $parsedXML): array
    {
        if(is_object($parsedXML))
        {
            $parsedXML = (array)$parsedXML;
        }

        if(is_array($parsedXML))
        {
            foreach($parsedXML as $key => $value) $parsedXML[$key] = $this->object2Array($value);
        }

        return $parsedXML;
    }

    /**
     * 转换需求阶段。
     * Convert stage.
     *
     * @param  string $jiraStatus
     * @access protected
     * @return string
     */
    protected function convertStage(string $jiraStatus): string
    {
        $stage     = 'wait';
        $relations = $this->session->jiraRelation;

        $jiraStatusList = array_flip($relations['jiraStatus']);
        $stageID        = $jiraStatusList[$jiraStatus];

        if(!empty($relations['storyStage'][$stageID])) $stage = $relations['storyStage'][$stageID];

        return $stage;
    }

    /**
     * 转换状态。
     * Convert jira status.
     *
     * @param  string $objectType
     * @param  string $jiraStatus
     * @access protected
     * @return string
     */
    protected function convertStatus(string $objectType, string $jiraStatus): string
    {
        $status    = $objectType == 'task' ? 'wait' : 'active';
        $relations = $this->session->jiraRelation;

        $arrayKey       = "{$objectType}Status";
        $jiraStatusList = array_flip($relations['jiraStatus']);
        $statusID       = $jiraStatusList[$jiraStatus];

        if(!empty($relations[$arrayKey][$statusID])) $status = $relations[$arrayKey][$statusID];

        return $status;
    }

    /**
     * 导入user数据。
     * Import jira user.
     *
     * @param  array $dataList
     * @access protected
     * @return void
     */
    protected function importJiraUser(array $dataList)
    {
        $localUsers = $this->dao->dbh($this->dbh)->select('account')->from(TABLE_USER)->where('deleted')->eq('0')->fetchPairs();
        $userConfig = $this->session->jiraUser;

        foreach($dataList as $id => $data)
        {
            if(isset($localUsers[$data->account])) continue;

            $user = new stdclass();
            $user->account  = $data->account;
            $user->realname = $data->realname;
            $user->password = $userConfig['password'];
            $user->group    = $userConfig['group'];
            $user->email    = $data->email;
            $user->gender   = 'm';
            $user->type     = 'inside';
            $user->join     = $data->join;

            $this->dao->dbh($this->dbh)->replace(TABLE_USER)->data($user, 'group')->exec();

            if(!dao::isError())
            {
                $data = new stdclass();
                $data->account = $user->account;
                $data->group   = $user->group;

                $this->dao->dbh($this->dbh)->replace(TABLE_USERGROUP)->set('account')->eq($user->account)->set('`group`')->eq($user->group)->exec();
            }
        }
    }

    /**
     * 导入project数据。
     * Import jira project.
     *
     * @param  array  $dataList
     * @param  string $method db|file
     * @access protected
     * @return void
     */
    protected function importJiraProject(array $dataList, string $method = 'db')
    {
        global $app;
        $app->loadConfig('execution');
        $app->loadLang('doc');

        foreach($dataList as $id => $data)
        {
            $project     = $this->createProject($data, $method);
            $executionID = $this->createExecution($project);
            $this->createProduct($project, $executionID);

            $projectRelation = array();
            $projectRelation['AType'] = 'jproject';
            $projectRelation['BType'] = 'zproject';
            $projectRelation['AID']   = $id;
            $projectRelation['BID']   = $project->id;
            $this->dao->dbh($this->dbh)->insert(JIRA_TMPRELATION)->data($projectRelation)->exec();

            $executionRelation = array();
            $executionRelation['AType'] = 'jproject';
            $executionRelation['BType'] = 'zexecution';
            $executionRelation['AID']   = $id;
            $executionRelation['BID']   = $executionID;
            $this->dao->dbh($this->dbh)->insert(JIRA_TMPRELATION)->data($executionRelation)->exec();

            $keyRelation = array();
            $keyRelation['AType'] = 'joldkey';
            $keyRelation['BType'] = 'jnewkey';
            $keyRelation['AID']   = $data->ORIGINALKEY;
            $keyRelation['BID']   = $data->pkey;
            $keyRelation['extra'] = $data->ID;
            $this->dao->dbh($this->dbh)->insert(JIRA_TMPRELATION)->data($keyRelation)->exec();
        }
    }

    /**
     * 导入issue数据。
     * Import jira issue.
     *
     * @param  array $dataList
     * @param  string $method db|file
     * @access public
     * @return void
     */
    public function importJiraIssue(array $dataList, string $method = 'db')
    {
        $projectRelation = $this->dao->dbh($this->dbh)->select('AID,BID')->from(JIRA_TMPRELATION)
            ->where('AType')->eq('jproject')
            ->andWhere('BType')->eq('zproject')
            ->fetchPairs();

        $projectProduct = $this->dao->dbh($this->dbh)->select('project,product')->from(TABLE_PROJECTPRODUCT)
            ->where('project')->in(array_values($projectRelation))
            ->fetchPairs();

        $projectKeys = $this->dao->dbh($this->dbh)->select('extra as ID, AID as oldKey, BID as newKey')->from(JIRA_TMPRELATION)
            ->where('AType')->eq('joldkey')
            ->andWhere('BType')->eq('jnewkey')
            ->fetchAll('ID');

        $projectExecution = $this->dao->dbh($this->dbh)->select('project,id')->from(TABLE_PROJECT)
            ->where('project')->in(array_values($projectRelation))
            ->fetchPairs();

        $relations      = $this->session->jiraRelation;
        $issueTypeList  = array();
        $reasonList     = array();
        $resolutionList = array();
        foreach($relations['jiraObject'] as $id => $jiraCode) $issueTypeList[$jiraCode] = $relations['zentaoObject'][$id];
        foreach($relations['jiraResolution'] as $id => $jiraCode)
        {
            if(!empty($relations['zentaoReason'][$id]))     $reasonList[$jiraCode]     = $relations['zentaoReason'][$id];
            if(!empty($relations['zentaoResolution'][$id])) $resolutionList[$jiraCode] = $relations['zentaoResolution'][$id];
        }

        foreach($dataList as $id => $data)
        {
            $issueType    = isset($issueTypeList[$data->issuetype]) ? $issueTypeList[$data->issuetype] : 'task';
            $issueProject = $data->PROJECT;

            if(!isset($projectRelation[$issueProject])) continue;

            $projectID   = $projectRelation[$issueProject];
            $productID   = $projectProduct[$projectID];
            $executionID = $projectExecution[$projectID];

            if($issueType == 'requirement' or $issueType == 'story')
            {
                $this->createStory($productID, $projectID, $executionID, $issueType, $data, $method, $reasonList);
            }
            elseif($issueType == 'task' or $issueType == 'subTask')
            {
                $this->createTask($projectID, $executionID, $data, $method, $reasonList);
            }
            elseif($issueType == 'bug')
            {
                $this->createBug($productID, $projectID, $data, $method, $resolutionList);
            }

            $oldKey   = zget($projectKeys[$issueProject], 'oldKey', '');
            $newKey   = zget($projectKeys[$issueProject], 'newKey', '');
            $issueKey = $oldKey ? $oldKey . '-' . $data->issuenum : $newKey . '-' . $data->issuenum;

            $fileRelation['AType'] = 'jissueid';
            $fileRelation['BType'] = 'jfilepath';
            $fileRelation['AID']   = $data->ID;
            $fileRelation['extra'] = "{$oldKey}/10000/{$issueKey}/";
            $this->dao->dbh($this->dbh)->insert(JIRA_TMPRELATION)->data($fileRelation)->exec();
        }
    }

    /**
     * 创建项目。
     * Create project.
     *
     * @param  object $data
     * @param  string $method db|file
     * @access protected
     * @return object
     */
    protected function createProject(object $data, string $method): object
    {
        /* Create project. */
        $project = new stdclass();
        $project->name          = $data->pname;
        $project->code          = $data->pkey;
        $project->desc          = $data->DESCRIPTION;
        $project->status        = 'wait';
        $project->type          = 'project';
        $project->model         = 'scrum';
        $project->grade         = 1;
        $project->acl           = 'open';
        $project->end           = date('Y-m-d', time() + 30 * 24 * 3600);
        $project->PM            = $this->getJiraAccount($data->LEAD, $method);
        $project->openedBy      = $this->getJiraAccount($data->LEAD, $method);
        $project->openedDate    = helper::now();
        $project->openedVersion = $this->config->version;

        $this->dao->dbh($this->dbh)->insert(TABLE_PROJECT)->data($project)->exec();
        $projectID = $this->dao->dbh($this->dbh)->lastInsertID();
        $this->dao->dbh($this->dbh)->update(TABLE_PROJECT)->set('`order`')->eq($projectID * 5)->set('path')->eq(",$projectID,")->where('id')->eq($projectID)->exec();

        /* Create member. */
        $member = new stdclass();
        $member->root    = $projectID;
        $member->account = $project->openedBy;
        $member->role    = '';
        $member->join    = '';
        $member->type    = 'project';
        $member->days    = 0;
        $member->hours   = $this->config->execution->defaultWorkhours;
        $this->dao->dbh($this->dbh)->insert(TABLE_TEAM)->data($member)->exec();

        /* Create doc lib. */
        $lib = new stdclass();
        $lib->project = $projectID;
        $lib->name    = $this->lang->doclib->main['project'];
        $lib->type    = 'project';
        $lib->main    = '1';
        $lib->acl     = 'default';
        $this->dao->dbh($this->dbh)->insert(TABLE_DOCLIB)->data($lib)->exec();

        $project->id = $projectID;
        return $project;
    }

    /**
     * 创建执行。
     * Create execution.
     *
     * @param  object $project
     * @access protected
     * @return int
     */
    protected function createExecution(object $project): int
    {
        /* Create execution. */
        $execution = new stdclass();
        $execution->name          = $project->name;
        $execution->code          = $project->code;
        $execution->desc          = $project->desc;
        $execution->status        = 'wait';
        $execution->project       = $project->id;
        $execution->parent        = $project->id;
        $execution->grade         = 1;
        $execution->type          = 'sprint';
        $execution->acl           = 'open';
        $execution->end           = date('Y-m-d', time() + 24 * 3600);
        $execution->PM            = $project->PM;
        $execution->openedBy      = $project->openedBy;
        $execution->openedDate    = helper::now();
        $execution->openedVersion = $this->config->version;

        $this->dao->dbh($this->dbh)->insert(TABLE_PROJECT)->data($execution)->exec();
        $executionID = $this->dao->dbh($this->dbh)->lastInsertID();
        $this->dao->dbh($this->dbh)->update(TABLE_PROJECT)->set('`order`')->eq($executionID * 5)->set('path')->eq(",{$project->id},$executionID,")->where('id')->eq($executionID)->exec();

        /* Create member. */
        $member = new stdclass();
        $member->root    = $executionID;
        $member->account = $execution->openedBy;
        $member->role    = '';
        $member->join    = '';
        $member->type    = 'execution';
        $member->days    = 0;
        $member->hours   = $this->config->execution->defaultWorkhours;
        $this->dao->dbh($this->dbh)->insert(TABLE_TEAM)->data($member)->exec();

        /* Create doc lib. */
        $lib = new stdclass();
        $lib->project   = $project->id;
        $lib->execution = $executionID;
        $lib->name      = $this->lang->doclib->main['execution'];
        $lib->type      = 'execution';
        $lib->main      = '1';
        $lib->acl       = 'default';
        $this->dao->dbh($this->dbh)->insert(TABLE_DOCLIB)->data($lib)->exec();

        return $executionID;
    }

    /**
     * 创建产品。
     * Create product.
     *
     * @param  object $project
     * @param  int    $executionID
     * @access protected
     * @return void
     */
    protected function createProduct(object $project, int $executionID)
    {
        /* Create product. */
        $product = new stdclass();
        $product->name           = $project->name;
        $product->code           = $project->code;
        $product->type           = 'normal';
        $product->status         = 'normal';
        $product->acl            = 'open';
        $product->createdBy      = $project->openedBy;
        $product->createdDate    = helper::now();
        $product->createdVersion = $this->config->version;

        $this->dao->dbh($this->dbh)->insert(TABLE_PRODUCT)->data($product)->exec();
        $productID = $this->dao->dbh($this->dbh)->lastInsertID();

        /* Create doc lib. */
        $lib = new stdclass();
        $lib->product = $productID;
        $lib->name    = $this->lang->doclib->main['product'];
        $lib->type    = 'product';
        $lib->main    = '1';
        $lib->acl     = 'default';
        $this->dao->dbh($this->dbh)->insert(TABLE_DOCLIB)->data($lib)->exec();

        $this->dao->dbh($this->dbh)->update(TABLE_PRODUCT)->set('`order`')->eq($productID * 5)->where('id')->eq($productID)->exec();
        $this->dao->dbh($this->dbh)->replace(TABLE_PROJECTPRODUCT)->set('project')->eq($project->id)->set('product')->eq($productID)->exec();
        $this->dao->dbh($this->dbh)->replace(TABLE_PROJECTPRODUCT)->set('project')->eq($executionID)->set('product')->eq($productID)->exec();
    }

    /**
     * 创建需求。
     * Create story.
     *
     * @param  int    $productID
     * @param  int    $projectID
     * @param  int    $executionID
     * @param  string $type
     * @param  object $data
     * @param  string $method
     * @param  array  $reasonList
     * @access protected
     * @return void
     */
    protected function createStory(int $productID, int $projectID, int $executionID, string $type, object $data, string $method, array $reasonList)
    {
        $this->app->loadLang('story');

        $story             = new stdclass();
        $story->product    = $productID;
        $story->title      = $data->SUMMARY;
        $story->type       = $type;
        $story->pri        = $data->PRIORITY;
        $story->version    = 1;
        $story->stage      = $this->convertStage($data->issuestatus);
        $story->status     = $this->convertStatus('story', $data->issuestatus);
        $story->openedBy   = $this->getJiraAccount($data->CREATOR, $method);
        $story->openedDate = substr($data->CREATED, 0, 19);
        $story->assignedTo = $this->getJiraAccount($data->ASSIGNEE, $method);

        if($data->RESOLUTION)
        {
            $story->closedReason = zget($reasonList, $data->RESOLUTION, '');
            if($story->closedReason and !isset($this->lang->story->reasonList[$story->closedReason])) $story->closedReason = 'done';
        }

        $this->dao->dbh($this->dbh)->insert(TABLE_STORY)->data($story)->exec();

        if(!dao::isError())
        {
            $storyID = $this->dao->dbh($this->dbh)->lastInsertID();

            $storyDesc = new stdclass();
            $storyDesc->story   = $storyID;
            $storyDesc->version = 1;
            $storyDesc->title   = $story->title;
            $storyDesc->spec    = $data->DESCRIPTION;
            $this->dao->dbh($this->dbh)->replace(TABLE_STORYSPEC)->data($storyDesc)->exec();
            $this->dao->dbh($this->dbh)->replace(TABLE_PROJECTSTORY)->set('project')->eq($projectID)
                ->set('product')->eq($productID)
                ->set('story')->eq($storyID)
                ->set('version')->eq('1')
                ->exec();

            $this->dao->dbh($this->dbh)->replace(TABLE_PROJECTSTORY)->set('project')->eq($executionID)
                ->set('product')->eq($productID)
                ->set('story')->eq($storyID)
                ->set('version')->eq('1')
                ->exec();

            /* Create opened action from openedDate. */
            $action = new stdclass();
            $action->objectType = 'story';
            $action->objectID   = $storyID;
            $action->actor      = $story->openedBy;
            $action->action     = 'Opened';
            $action->date       = $story->openedDate;
            $this->dao->dbh($this->dbh)->insert(TABLE_ACTION)->data($action)->exec();

            /* Record relation. */
            $storyRelation['AType'] = 'jstory';
            $storyRelation['BType'] = 'zstory';
            $storyRelation['AID']   = $data->ID;
            $storyRelation['BID']   = $storyID;
            $this->dao->dbh($this->dbh)->insert(JIRA_TMPRELATION)->data($storyRelation)->exec();

            $issueRelation['AType'] = 'jissueid';
            $issueRelation['BType'] = 'zissuetype';
            $issueRelation['AID']   = $data->ID;
            $issueRelation['extra'] = 'story';
            $this->dao->dbh($this->dbh)->insert(JIRA_TMPRELATION)->data($issueRelation)->exec();
        }
    }

    /**
     * 创建任务。
     * Create task.
     *
     * @param  int    $projectID
     * @param  int    $executionID
     * @param  object $data
     * @param  string $method
     * @param  array  $reasonList
     * @access protected
     * @return void
     */
    protected function createTask(int $projectID, int $executionID, object $data, string $method, array $reasonList)
    {
        $this->app->loadLang('task');

        $task = new stdclass();
        $task->project    = $projectID;
        $task->execution  = $executionID;
        $task->name       = $data->SUMMARY;
        $task->type       = 'devel';
        $task->pri        = $data->PRIORITY;
        $task->status     = $this->convertStatus('task', $data->issuestatus);
        $task->desc       = $data->DESCRIPTION;
        $task->openedBy   = $this->getJiraAccount($data->CREATOR, $method);
        $task->openedDate = substr($data->CREATED, 0, 19);
        $task->assignedTo = $this->getJiraAccount($data->ASSIGNEE, $method);
        if($data->RESOLUTION)
        {
            $task->closedReason = zget($reasonList, $data->RESOLUTION, '');
            if($task->closedReason and !isset($this->lang->task->reasonList[$task->closedReason])) $task->closedReason = 'cancel';
        }

        $this->dao->dbh($this->dbh)->insert(TABLE_TASK)->data($task)->exec();
        $taskID = $this->dao->dbh($this->dbh)->lastInsertID();

        /* Create opened action from openedDate. */
        $action = new stdclass();
        $action->objectType = 'task';
        $action->objectID   = $taskID;
        $action->actor      = $task->openedBy;
        $action->action     = 'Opened';
        $action->date       = $task->openedDate;
        $this->dao->dbh($this->dbh)->insert(TABLE_ACTION)->data($action)->exec();

        $taskRelation['AType'] = 'jtask';
        $taskRelation['BType'] = 'ztask';
        $taskRelation['AID']   = $data->ID;
        $taskRelation['BID']   = $taskID;
        $this->dao->dbh($this->dbh)->insert(JIRA_TMPRELATION)->data($taskRelation)->exec();

        $issueRelation['AType'] = 'jissueid';
        $issueRelation['BType'] = 'zissuetype';
        $issueRelation['AID']   = $data->ID;
        $issueRelation['extra'] = 'task';
        $this->dao->dbh($this->dbh)->insert(JIRA_TMPRELATION)->data($issueRelation)->exec();
    }

    /**
     * 创建BUG。
     * Create bug.
     *
     * @param  int    $productID
     * @param  int    $projectID
     * @param  object $data
     * @param  string $method
     * @param  array  $resolutionList
     * @access protected
     * @return void
     */
    protected function createBug(int $productID, int $projectID, object $data, string $method, array $resolutionList)
    {
        $this->app->loadLang('bug');

        $bug = new stdclass();
        $bug->product     = $productID;
        $bug->project     = $projectID;
        $bug->title       = $data->SUMMARY;
        $bug->pri         = $data->PRIORITY;
        $bug->status      = $this->convertStatus('bug', $data->issuestatus);
        $bug->steps       = $data->DESCRIPTION;
        $bug->openedBy    = $this->getJiraAccount($data->CREATOR, $method);
        $bug->openedDate  = substr($data->CREATED, 0, 19);
        $bug->openedBuild = 'trunk';
        $bug->assignedTo  = $bug->status == 'closed' ? 'closed' : $this->getJiraAccount($data->ASSIGNEE, $method);

        if($data->RESOLUTION)
        {
            $bug->resolution = zget($resolutionList, $data->RESOLUTION, '');
            if($bug->resolution and !isset($this->lang->bug->resolutionList[$bug->resolution])) $bug->resolution = 'fixed';
        }

        $this->dao->dbh($this->dbh)->insert(TABLE_BUG)->data($bug)->exec();
        $bugID = $this->dao->dbh($this->dbh)->lastInsertID();

        /* Create opened action from openedDate. */
        $action = new stdclass();
        $action->objectType = 'bug';
        $action->objectID   = $bugID;
        $action->actor      = $bug->openedBy;
        $action->action     = 'Opened';
        $action->date       = $bug->openedDate;
        $this->dao->dbh($this->dbh)->insert(TABLE_ACTION)->data($action)->exec();

        $bugRelation['AType'] = 'jbug';
        $bugRelation['BType'] = 'zbug';
        $bugRelation['AID']   = $data->ID;
        $bugRelation['BID']   = $bugID;
        $this->dao->dbh($this->dbh)->insert(JIRA_TMPRELATION)->data($bugRelation)->exec();

        $issueRelation['AType'] = 'jissueid';
        $issueRelation['BType'] = 'zissuetype';
        $issueRelation['AID']   = $data->ID;
        $issueRelation['extra'] = 'bug';
        $this->dao->dbh($this->dbh)->insert(JIRA_TMPRELATION)->data($issueRelation)->exec();
    }
}
