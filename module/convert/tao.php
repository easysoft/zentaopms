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
     * 从数据库获取jira数据。
     * Get jira data from db.
     *
     * @param  string $module
     * @param  int    $lastID
     * @param  int    $limit
     * @access protected
     * @return array
     */
    protected function getJiraDataFromDB(string $module = '', int $lastID = 0, int $limit = 0): array
    {
        $dataList = array();
        $table    = zget($this->config->convert->objectTables, $module, '');
        if($module == 'user')
        {
            $dataList = $this->dao->dbh($this->sourceDBH)->select('t1.`ID`, t1.`lower_user_name` as account, t1.`lower_display_name` as realname, t1.`lower_email_address` as email, t1.created_date as `join`, t2.user_key as userCode')->from(JIRA_USERINFO)->alias('t1')
                ->leftJoin(JIRA_USER)->alias('t2')->on('t1.`lower_user_name` = t2.`lower_user_name`')
                ->where('1 = 1')
                ->beginIF($lastID)->andWhere('t1.ID')->gt($lastID)->fi()
                ->orderBy('t1.ID asc')->limit($limit)
                ->fetchAll('ID');
        }
        elseif(!empty($table))
        {
            $dataList = $this->dao->dbh($this->sourceDBH)->select('*')->from($table)
                ->where('1 = 1')
                ->beginIF($lastID)->andWhere('ID')->gt($lastID)->fi()
                ->orderBy('ID asc')->limit($limit)
                ->fetchAll('ID');
        }

        return $dataList;
    }

    /**
     * 获取需求、任务、bug、对象类型。
     * Get stories and tasks and bugs and objectType.
     *
     * @access protected
     * @return array
     */
    protected function getIssueData(): array
    {
        $issueStories = $this->dao->dbh($this->dbh)->select('AID,BID')->from(JIRA_TMPRELATION)
            ->where('AType')->eq('jstory')
            ->andWhere('BType')->eq('zstory')
            ->fetchPairs();

        $issueTasks = $this->dao->dbh($this->dbh)->select('AID,BID')->from(JIRA_TMPRELATION)
            ->where('AType')->eq('jtask')
            ->andWhere('BType')->eq('ztask')
            ->fetchPairs();

        $issueBugs = $this->dao->dbh($this->dbh)->select('AID,BID')->from(JIRA_TMPRELATION)
            ->where('AType')->eq('jbug')
            ->andWhere('BType')->eq('zbug')
            ->fetchPairs();

        $issueObjectType = $this->dao->dbh($this->dbh)->select('AID,extra')->from(JIRA_TMPRELATION)
            ->where('AType')->eq('jissueid')
            ->andWhere('BType')->eq('zissuetype')
            ->fetchPairs();

        return array($issueStories, $issueTasks, $issueBugs, $issueObjectType);
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

        foreach($dataList as $data)
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
     * @param  array  $dataList
     * @param  string $method db|file
     * @access protected
     * @return void
     */
    protected function importJiraIssue(array $dataList, string $method = 'db')
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
            $issueProject = $data->PROJECT;
            if(!isset($projectRelation[$issueProject])) continue;

            $projectID   = $projectRelation[$issueProject];
            $productID   = $projectProduct[$projectID];
            $executionID = $projectExecution[$projectID];

            $issueType = isset($issueTypeList[$data->issuetype]) ? $issueTypeList[$data->issuetype] : 'task';
            if($issueType == 'requirement' || $issueType == 'story')
            {
                $this->createStory($productID, $projectID, $executionID, $issueType, $data, $method, $reasonList);
            }
            elseif($issueType == 'task' || $issueType == 'subTask')
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
     * 导入版本数据。
     * Import jira build.
     *
     * @param  array  $dataList
     * @param  string $method db|file
     * @access protected
     * @return void
     */
    protected function importJiraBuild(array $dataList, string $method = 'db')
    {
        list($issueStories, $issueTasks, $issueBugs, $issueObjectType) = $this->getIssueData();

        $projectRelation = $this->dao->dbh($this->dbh)->select('AID,BID')->from(JIRA_TMPRELATION)
            ->where('AType')->eq('jproject')
            ->andWhere('BType')->eq('zproject')
            ->fetchPairs();

        $projectProduct = $this->dao->dbh($this->dbh)->select('project,product')->from(TABLE_PROJECTPRODUCT)
            ->where('project')->in(array_values($projectRelation))
            ->fetchPairs();

        $versionGroup = $method == 'db' ? $this->dao->dbh($this->sourceDBH)->select('SINK_NODE_ID as versionID, SOURCE_NODE_ID as issueID, ASSOCIATION_TYPE as relation')->from(JIRA_NODEASSOCIATION)->where('SINK_NODE_ENTITY')->eq('Version')->fetchGroup('versionID') : $this->getVersionGroup();

        foreach($dataList as $data)
        {
            $buildProject = $data->PROJECT;
            $projectID    = $projectRelation[$buildProject];
            $productID    = $projectProduct[$projectID];
            $build        = $this->createBuild($productID, $projectID, $data, $versionGroup, $method, $issueObjectType, $issueBugs, $issueStories);
            if(empty($data->RELEASEDATE)) continue;

            $this->createRelease($build, $data, $versionGroup, $method, $issueObjectType, $issueBugs, $issueStories);
        }
    }

    /**
     * 导入issue link数据。
     * Import jira issue link.
     *
     * @param  array $dataList
     * @access protected
     * @return void
     */
    protected function importJiraIssueLink(array $dataList)
    {
        list($issueStories, $issueTasks, $issueBugs, $issueObjectType) = $this->getIssueData();

        $issueLinkTypeList = array();
        $relations = $this->session->jiraRelation;
        foreach($relations['jiraLinkType'] as $id => $jiraCode) $issueLinkTypeList[$jiraCode] = $relations['zentaoLinkType'][$id];

        $storyLink = $taskLink = $duplicateLink = $relatesLink = array();
        foreach($dataList as $issueLink)
        {
            $linkType = $issueLink->LINKTYPE;
            if($issueLinkTypeList[$linkType] == 'subStoryLink') $storyLink[$issueLink->SOURCE][]   = $issueLink->DESTINATION;
            if($issueLinkTypeList[$linkType] == 'subTaskLink')  $taskLink[$issueLink->SOURCE][]    = $issueLink->DESTINATION;
            if($issueLinkTypeList[$linkType] == 'duplicate')    $duplicateLink[$issueLink->SOURCE] = $issueLink->DESTINATION;
            if($issueLinkTypeList[$linkType] == 'relates')      $relatesLink[$issueLink->SOURCE]   = $issueLink->DESTINATION;
        }

        $this->updateSubStory($storyLink, $issueStories);
        $this->updateSubTask($taskLink, $issueTasks);
        $this->updateDuplicateStoryAndBug($duplicateLink, $issueObjectType, $issueStories, $issueBugs);
        $this->updateRelatesObject($relatesLink, $issueObjectType, $issueStories, $issueTasks, $issueBugs);
    }

    /**
     * 导入action数据。
     * Import jira action.
     *
     * @param  array  $dataList
     * @param  string $method db|file
     * @access protected
     * @return void
     */
    protected function importJiraAction(array $dataList, string $method = 'db')
    {
        list($issueStories, $issueTasks, $issueBugs, $issueObjectType) = $this->getIssueData();

        foreach($dataList as $data)
        {
            $issueID = $data->issueid;
            $comment = $data->actionbody;
            if(empty($comment)) continue;

            if(!isset($issueObjectType[$issueID])) continue;

            $objectType = $issueObjectType[$issueID];
            if($objectType == 'task')  $objectID = $issueTasks[$issueID];
            if($objectType == 'bug')   $objectID = $issueBugs[$issueID];
            if($objectType == 'story') $objectID = $issueStories[$issueID];

            if(empty($objectID)) continue;

            $action = new stdclass();
            $action->objectType = $objectType;
            $action->objectID   = $objectID;
            $action->actor      = $this->getJiraAccount($data->AUTHOR, $method);
            $action->action     = 'commented';
            $action->date       = substr($data->CREATED, 0, 19);
            $action->comment    = $comment;
            $this->dao->dbh($this->dbh)->insert(TABLE_ACTION)->data($action)->exec();
        }
    }

    /**
     * 导入file数据。
     * Import jira file.
     *
     * @param  array  $dataList
     * @param  string $method db|file
     * @access protected
     * @return void
     */
    protected function importJiraFile(array $dataList, string $method = 'db')
    {
        $this->loadModel('file');

        list($issueStories, $issueTasks, $issueBugs, $issueObjectType) = $this->getIssueData();

        $filePaths = $this->dao->dbh($this->dbh)->select('AID,extra')->from(JIRA_TMPRELATION)
            ->where('AType')->eq('jissueid')
            ->andWhere('BType')->eq('jfilepath')
            ->fetchPairs();

        foreach($dataList as $fileAttachment)
        {
            $issueID = $fileAttachment->issueid;
            if(!isset($issueObjectType[$issueID])) continue;

            $objectType = $issueObjectType[$issueID];
            if($objectType != 'bug' && $objectType != 'task' && $objectType != 'story') continue;

            if($objectType == 'bug')   $objectID = $issueBugs[$issueID];
            if($objectType == 'task')  $objectID = $issueTasks[$issueID];
            if($objectType == 'story') $objectID = $issueStories[$issueID];
            if(empty($objectID)) continue;

            $fileID   = $fileAttachment->ID;
            $fileName = $fileAttachment->FILENAME;
            list($mime, $extension) = explode('/', $fileAttachment->MIMETYPE);

            $file = new stdclass();
            $file->pathname   = $this->file->setPathName($fileID, $extension);
            $file->title      = str_ireplace(".{$extension}", '', $fileName);
            $file->extension  = $extension;
            $file->size       = $fileAttachment->FILESIZE;
            $file->objectType = $objectType;
            $file->objectID   = $objectID;
            $file->addedBy    = $this->getJiraAccount($fileAttachment->AUTHOR, $method);
            $file->addedDate  = substr($fileAttachment->CREATED, 0, 19);
            $this->dao->dbh($this->dbh)->insert(TABLE_FILE)->data($file)->exec();

            $jiraFile = $this->app->getTmpRoot() . 'attachments/' . $filePaths[$issueID] .  $fileID;
            if(is_file($jiraFile)) copy($jiraFile, $this->file->savePath . $file->pathname);
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
            if($story->closedReason && !isset($this->lang->story->reasonList[$story->closedReason])) $story->closedReason = 'done';
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
            if($task->closedReason && !isset($this->lang->task->reasonList[$task->closedReason])) $task->closedReason = 'cancel';
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
            if($bug->resolution && !isset($this->lang->bug->resolutionList[$bug->resolution])) $bug->resolution = 'fixed';
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

    /**
     * 创建版本。
     * Create build.
     *
     * @param  int    $productID
     * @param  int    $projectID
     * @param  object $data
     * @param  array  $versionGroup
     * @param  string $method
     * @param  array  $issueObjectType
     * @param  array  $issueBugs
     * @param  array  $issueStories
     * @access protected
     * @return object
     */
    protected function createBuild(int $productID, int $projectID, object $data, array $versionGroup, string $method, array $issueObjectType, array $issueBugs, array $issueStories): object
    {
        /* Create build. */
        $build = new stdclass();
        $build->product     = $productID;
        $build->project     = $projectID;
        $build->name        = $data->vname;
        $build->date        = substr($data->RELEASEDATE, 0, 10);
        $build->builder     = $this->app->user->account;
        $build->createdBy   = $this->app->user->account;
        $build->createdDate = helper::now();
        $this->dao->dbh($this->dbh)->insert(TABLE_BUILD)->data($build)->exec();

        $buildID   = $this->dao->dbh($this->dbh)->lastInsertID();
        $versionID = $data->ID;

        /* Process build data. */
        if(isset($versionGroup[$versionID]))
        {
            foreach($versionGroup[$versionID] as $issue)
            {
                $issueID   = $method == 'db' ? $issue->issueID : $issue;
                $issueType = zget($issueObjectType, $issueID, '');
                if(!$issueType || ($issueType != 'story' && $issueType != 'bug')) continue;

                if($issueType == 'story')
                {
                    $objectID = zget($issueStories, $issueID);
                    $this->dao->dbh($this->dbh)->update(TABLE_BUILD)->set("stories = CONCAT(stories, ',$objectID')")->where('id')->eq($buildID)->exec();
                }
                else
                {
                    $objectID = zget($issueBugs, $issueID);
                    $this->dao->dbh($this->dbh)->update(TABLE_BUILD)->set("bugs = CONCAT(bugs, ',$objectID')")->where('id')->eq($buildID)->exec();
                    if($issue->relation == 'IssueVersion')
                    {
                        $this->dao->dbh($this->dbh)->update(TABLE_BUG)->set('openedBuild')->eq($buildID)->where('id')->eq($objectID)->exec();
                    }
                    elseif($issue->relation == 'IssueFixVersion')
                    {
                        $this->dao->dbh($this->dbh)->update(TABLE_BUG)->set('resolvedBuild')->eq($buildID)->where('id')->eq($objectID)->exec();
                    }
                }
            }
        }

        $build->id = $buildID;
        return $build;
    }

    /**
     * 创建发布。
     * Create release.
     *
     * @param  object $build
     * @param  object $data
     * @param  array  $versionGroup
     * @param  string $method
     * @param  array  $issueObjectType
     * @param  array  $issueBugs
     * @param  array  $issueStories
     * @access protected
     * @return void
     */
    protected function createRelease(object $build, object $data, array $versionGroup, string $method, array $issueObjectType, array $issueBugs, array $issueStories)
    {
        /* Create release. */
        $release = new stdclass();
        $release->product     = $build->product;
        $release->build       = $build->id;
        $release->name        = $build->name;
        $release->date        = $build->date;
        $release->desc        = $data->DESCRIPTION;
        $release->status      = 'normal';
        $release->createdBy   = $this->app->user->account;
        $release->createdDate = helper::now();
        $this->dao->dbh($this->dbh)->insert(TABLE_RELEASE)->data($release)->exec();

        $releaseID = $this->dao->dbh($this->dbh)->lastInsertID();
        $versionID = $data->ID;

        /* Process release data. */
        if(isset($versionGroup[$versionID]))
        {
            foreach($versionGroup[$versionID] as $issue)
            {
                $issueID   = $method == 'db' ? $issue->issueID : $issue;
                $issueType = zget($issueObjectType, $issueID, '');
                if(!$issueType || ($issueType != 'story' && $issueType != 'bug')) continue;

                if($issueType == 'story')
                {
                    $objectID = zget($issueStories, $issueID);
                    $this->dao->dbh($this->dbh)->update(TABLE_RELEASE)->set("stories = CONCAT(stories, ',$objectID')")->where('id')->eq($releaseID)->exec();
                }
                else
                {
                    $objectID = zget($issueBugs, $issueID);
                    $this->dao->dbh($this->dbh)->update(TABLE_RELEASE)->set("bugs = CONCAT(bugs, ',$objectID')")->where('id')->eq($releaseID)->exec();
                }
            }
        }
    }

    /**
     * 更新子需求。
     * Update sub story.
     *
     * @param  array  $storyLink
     * @param  array  $issueStories
     * @access protected
     * @return void
     */
    protected function updateSubStory(array $storyLink, array $issueStories)
    {
        foreach($storyLink as $source => $dest)
        {
            if(!isset($issueStories[$source])) continue;

            $parentID = $issueStories[$source];
            $this->dao->dbh($this->dbh)->update(TABLE_STORY)->set('parent')->eq('-1')->where('id')->eq($parentID)->exec();

            foreach($dest as $childID)
            {
                if(!isset($issueStories[$childID])) continue;
                $this->dao->dbh($this->dbh)->update(TABLE_STORY)->set('parent')->eq($parentID)->where('id')->eq($issueStories[$childID])->exec();
            }
        }
    }

    /**
     * 更新子任务。
     * Update sub task.
     *
     * @param  array  $taskLink
     * @param  array  $issueTasks
     * @access protected
     * @return void
     */
    protected function updateSubTask(array $taskLink, array $issueTasks)
    {
        foreach($taskLink as $source => $dest)
        {
            if(!isset($issueTasks[$source])) continue;

            $parentID = $issueTasks[$source];
            $this->dao->dbh($this->dbh)->update(TABLE_TASK)->set('parent')->eq('-1')->where('id')->eq($parentID)->exec();

            foreach($dest as $childID)
            {
                if(!isset($issueTasks[$childID])) continue;
                $this->dao->dbh($this->dbh)->update(TABLE_TASK)->set('parent')->eq($parentID)->where('id')->eq($issueTasks[$childID])->exec();
            }
        }
    }

    /**
     * 更新重复的需求和bug。
     * Update duplicate story and bug.
     *
     * @param  array  $duplicateLink
     * @param  array  $issueObjectType
     * @param  array  $issueStories
     * @param  array  $issueBugs
     * @access protected
     * @return void
     */
    protected function updateDuplicateStoryAndBug(array $duplicateLink, array $issueObjectType, array $issueStories, array $issueBugs)
    {
        foreach($duplicateLink as $source => $dest)
        {
            $objectType = $issueObjectType[$source];

            if($objectType != 'story' && $objectType != 'bug') continue;
            if($issueObjectType[$source] != $issueObjectType[$dest]) continue;

            if($objectType == 'story')
            {
                if(empty($issueStories[$dest]) || empty($issueStories[$source])) continue;
                $this->dao->dbh($this->dbh)->update(TABLE_STORY)->set('duplicateStory')->eq($$issueStories[$dest])->where('id')->eq($issueStories[$source])->exec();
            }
            elseif($objectType == 'bug')
            {
                if(empty($issueBugs[$dest]) || empty($issueBugs[$source])) continue;
                $this->dao->dbh($this->dbh)->update(TABLE_BUG)->set('duplicateBug')->eq($issueBugs[$dest])->where('id')->eq($issueBugs[$source])->exec();
            }
        }
    }

    /**
     * 更新相关对象数据。
     * Update relates object.
     *
     * @param  array  $relatesLink
     * @param  array  $issueObjectType
     * @param  array  $issueStories
     * @param  array  $issueTasks
     * @param  array  $issueBugs
     * @access protected
     * @return void
     */
    protected function updateRelatesObject(array $relatesLink, array $issueObjectType, array $issueStories, array $issueTasks, array $issueBugs)
    {
        foreach($relatesLink as $source => $dest)
        {
            if(empty($issueObjectType[$source]) || empty($issueObjectType[$dest])) continue;

            $sourceObjectType = $issueObjectType[$source];
            $destObjectType   = $issueObjectType[$dest];

            if($sourceObjectType == 'task' && $destObjectType == 'story')
            {
                $this->dao->dbh($this->dbh)->update(TABLE_TASK)->set('story')->eq($issueStories[$dest])->where('id')->eq($issueTasks[$source])->exec();
            }
            elseif($sourceObjectType == 'story' && $destObjectType == 'task')
            {
                $this->dao->dbh($this->dbh)->update(TABLE_TASK)->set('story')->eq($issueStories[$source])->where('id')->eq($issueTasks[$dest])->exec();
            }
            elseif($sourceObjectType == 'story' && $destObjectType == 'bug')
            {
                $this->dao->dbh($this->dbh)->update(TABLE_BUG)->set('story')->eq($issueStories[$source])->set('storyVersion')->eq(1)->where('id')->eq($issueBugs[$dest])->exec();
            }
            elseif($sourceObjectType == 'bug' && $destObjectType == 'story')
            {
                $this->dao->dbh($this->dbh)->update(TABLE_BUG)->set('story')->eq($issueStories[$dest])->set('storyVersion')->eq(1)->where('id')->eq($issueBugs[$source])->exec();
            }
            elseif($sourceObjectType == 'story' && $destObjectType == 'story')
            {
                $this->dao->dbh($this->dbh)->update(TABLE_STORY)->set("linkStories=concat(linkStories, ',{$issueStories[$dest]}')")->where('id')->eq($issueStories[$source])->exec();
            }
            elseif($sourceObjectType == 'bug' && $destObjectType == 'bug')
            {
                $this->dao->dbh($this->dbh)->update(TABLE_BUG)->set("relatedBug=concat(relatedBug, ',{$issueBugs[$dest]}')")->where('id')->eq($issueBugs[$source])->exec();
            }
        }
    }
}
