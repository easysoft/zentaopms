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
        $user->id       = $data['id'];
        $user->account  = isset($data['lowerUserName']) ? $data['lowerUserName'] : '';
        $user->realname = isset($data['lowerDisplayName']) ? $data['lowerDisplayName'] : '';
        $user->email    = isset($data['emailAddress']) ? $data['emailAddress'] : '';
        $user->join     = isset($data['createdDate']) ? $data['createdDate'] : null;

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
        $project->id          = $data['id'];
        $project->pname       = isset($data['name']) ? $data['name'] : '';
        $project->pkey        = isset($data['key']) ? $data['key'] : '';
        $project->originalkey = isset($data['originalkey']) ? $data['originalkey'] : '';
        $project->description = isset($data['description']) ? $data['description'] : '';
        $project->lead        = isset($data['lead']) ? $data['lead'] : '';

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
        $issue->id                   = $data['id'];
        $issue->summary              = isset($data['summary']) ? $data['summary'] : '';
        $issue->priority             = isset($data['priority']) ? $data['priority'] : '';
        $issue->project              = isset($data['project']) ? $data['project'] : 0;
        $issue->issuestatus          = isset($data['status']) ? $data['status'] : '';
        $issue->created              = isset($data['created']) ? $data['created'] : '';
        $issue->creator              = isset($data['creator']) ? $data['creator'] : '';
        $issue->issuetype            = isset($data['type']) ? $data['type'] : '';
        $issue->assignee             = isset($data['assignee']) ? $data['assignee'] : '';
        $issue->resolution           = isset($data['resolution']) ? $data['resolution'] : '';
        $issue->timeoriginalestimate = isset($data['timeoriginalestimate']) ? $data['timeoriginalestimate'] : '';
        $issue->timeestimate         = isset($data['timeestimate']) ? $data['timeestimate'] : '';
        $issue->timespent            = isset($data['timespent']) ? $data['timespent'] : '';
        $issue->issuenum             = isset($data['number']) ? $data['number'] : '';
        $issue->description          = isset($data['description']) ? $data['description'] : '';

        return $issue;
    }

    /**
     * 构建issue类型数据。
     * Build issue type data.
     *
     * @param  array     $data
     * @access protected
     * @return object
     */
    protected function buildIssueTypeData(array $data): object
    {
        $issueType = new stdclass();
        $issueType->id          = $data['id'];
        $issueType->pname       = $data['name'];
        $issueType->description = zget($data, 'description', '');
        $issueType->pstyle      = zget($data, 'style', '');
        $issueType->iconurl     = zget($data, 'iconurl', '');
        $issueType->avatar      = zget($data, 'avatar', '');

        return $issueType;
    }

    /**
     * 构建issue关联关系数据。
     * Build issue link type data.
     *
     * @param  array     $data
     * @access protected
     * @return object
     */
    protected function buildIssueLinkTypeData(array $data): object
    {
        $issueLinkType = new stdclass();
        $issueLinkType->id       = $data['id'];
        $issueLinkType->linkname = $data['linkname'];
        $issueLinkType->inward   = zget($data, 'inward', '');
        $issueLinkType->outward  = zget($data, 'outward', '');
        $issueLinkType->pstyle   = zget($data, 'style', '');

        return $issueLinkType;
    }

    /**
     * 构建解决方案数据。
     * Build resolution data.
     *
     * @param  array     $data
     * @access protected
     * @return object
     */
    protected function buildResolutionData(array $data): object
    {
        $resolution = new stdclass();
        $resolution->id          = $data['id'];
        $resolution->sequence    = $data['sequence'];
        $resolution->pname       = $data['name'];
        $resolution->description = zget($data, 'description', '');

        return $resolution;
    }

    /**
     * 构建issue状态数据。
     * Build issue status data.
     *
     * @param  array     $data
     * @access protected
     * @return object
     */
    protected function buildStatusData(array $data): object
    {
        $status = new stdclass();
        $status->id          = $data['id'];
        $status->sequence    = $data['sequence'];
        $status->pname       = $data['name'];
        $status->description = zget($data, 'description', '');

        return $status;
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
        $build->id          = $data['id'];
        $build->project     = isset($data['project']) ? $data['project'] : 0;
        $build->vname       = isset($data['name']) ? $data['name'] : '';
        $build->releasedate = isset($data['releasedate']) ? $data['releasedate'] : '';
        $build->released    = isset($data['released'])    ? $data['released'] : '';
        $build->description = isset($data['description']) ? $data['description'] : '';

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
        $issueLink->id          = $data['id'];
        $issueLink->linktype    = isset($data['linktype']) ? $data['linktype'] : '';
        $issueLink->source      = isset($data['source']) ? $data['source'] : '';
        $issueLink->destination = isset($data['destination']) ? $data['destination'] : '';

        return $issueLink;
    }

    /**
     * 构建action数据。
     * Build action data.
     *
     * @param  array     $data
     * @access protected
     * @return object
     */
    protected function buildActionData(array $data): object
    {
        $action = new stdclass();
        $action->id         = $data['id'];
        $action->issueid    = $data['issue'];
        $action->actionbody = isset($data['body']) ? $data['body'] : '';
        $action->author     = isset($data['author']) ? $data['author'] : '';
        $action->created    = isset($data['created']) ? $data['created'] : '';

        return $action;
    }

    /**
     * 构建file数据。
     * Build file data.
     *
     * @param  array     $data
     * @access protected
     * @return object
     */
    protected function buildFileData(array $data): object
    {
        $file = new stdclass();
        $file->issueid  = $data['issue'];
        $file->id       = $data['id'];
        $file->filename = isset($data['filename']) ? $data['filename'] : '';
        $file->mimetype = isset($data['mimetype']) ? $data['mimetype'] : '';
        $file->filesize = isset($data['filesize']) ? $data['filesize'] : '';
        $file->created  = isset($data['created'])  ? $data['created']  : '';
        $file->author   = isset($data['author'])   ? $data['author']   : '';

        return $file;
    }

    /**
     * 构建优先级数据。
     * Build Priority data.
     *
     * @param  array     $data
     * @access protected
     * @return object
     */
    protected function buildPriorityData(array $data): object
    {
        $priority = new stdclass();
        $priority->id    = $data['id'];
        $priority->pname = isset($data['name']) ? $data['name'] : '';

        return $priority;
    }

    /**
     * 构建自定义字段数据。
     * Build custom field data.
     *
     * @param  array     $data
     * @access protected
     * @return object
     */
    protected function buildCustomFieldData(array $data): object
    {
        $field = new stdclass();
        $field->id                     = $data['id'];
        $field->cfname                 = $data['name'];
        $field->description            = isset($data['description'])            ? $data['description']            : '';
        $field->customfieldtypekey     = isset($data['customfieldtypekey'])     ? $data['customfieldtypekey']     : '';
        $field->customfieldsearcherkey = isset($data['customfieldsearcherkey']) ? $data['customfieldsearcherkey'] : '';

        return $field;
    }

    /**
     * 构建工作流字段值数据。
     * Build custom field value data.
     *
     * @param  array     $data
     * @access protected
     * @return object
     */
    protected function buildCustomFieldValueData(array $data): object
    {
        $fieldValue = new stdclass();
        $fieldValue->id          = $data['id'];
        $fieldValue->issue       = $data['issue'];
        $fieldValue->customfield = $data['customfield'];
        $fieldValue->stringvalue = zget($data, 'stringvalue', '');
        $fieldValue->datevalue   = zget($data, 'datevalue', '');

        return $fieldValue;
    }

    /**
     * 构建工作流字段配置数据。
     * Build custom field option data.
     *
     * @param  array     $data
     * @access protected
     * @return object
     */
    protected function buildCustomFieldOptionData(array $data): object
    {
        $fieldOption = new stdclass();
        $fieldOption->id                = $data['id'];
        $fieldOption->customfield       = $data['customfield'];
        $fieldOption->customfieldconfig = $data['customfieldconfig'];
        $fieldOption->customvalue       = $data['value'];
        $fieldOption->disabled          = $data['disabled'];

        return $fieldOption;
    }

    /**
     * 构建属性条目数据。
     * Build os property entry data.
     *
     * @param  array     $data
     * @access protected
     * @return object
     */
    protected function buildOSPropertyEntryData(array $data): object
    {
        $property = new stdclass();
        $property->id           = $data['id'];
        $property->entity_name  = $data['entityName'];
        $property->entity_id    = $data['entityId'];
        $property->property_key = $data['propertyKey'];
        $property->propertytype = $data['type'];

        return $property;
    }

    /**
     * 构建上下文数据。
     * Build configuration context data.
     *
     * @param  array     $data
     * @access protected
     * @return object
     */
    protected function buildConfigurationcontextData(array $data): object
    {
        $config = new stdclass();
        $config->id                = $data['id'];
        $config->customfield       = isset($data['key']) ? $data['key'] : '';
        $config->fieldconfigscheme = isset($data['fieldconfigscheme']) ? $data['fieldconfigscheme'] : '';
        $config->project           = isset($data['project']) ? $data['project'] : '';

        return $config;
    }

    /**
     * 构建配置选择数据。
     * Build option configuration data.
     *
     * @param  array     $data
     * @access protected
     * @return object
     */
    protected function buildOptionconfigurationData(array $data): object
    {
        $option = new stdclass();
        $option->id          = $data['id'];
        $option->fieldid     = isset($data['fieldid']) ? $data['fieldid'] : '';
        $option->optionid    = isset($data['optionid']) ? $data['optionid'] : '';
        $option->fieldconfig = isset($data['fieldconfig']) ? $data['fieldconfig'] : '';

        return $option;
    }

    /**
     * 构建日志数据。
     * Build audit log data.
     *
     * @param  array     $data
     * @access protected
     * @return object
     */
    protected function buildAuditLogData(array $data): object
    {
        $auditLog = new stdclass();
        $auditLog->id          = $data['id'];
        $auditLog->summary     = isset($data['summary']) ? $data['summary'] : '';
        $auditLog->object_type = isset($data['objectType']) ? $data['objectType'] : '';
        $auditLog->object_id   = isset($data['objectId']) ? $data['objectId'] : '';

        return $auditLog;
    }

    /**
     * 构建项目用户角色数据。
     * Build project role actor data.
     *
     * @param  array     $data
     * @access protected
     * @return object
     */
    protected function buildProjectRoleActorData(array $data): object
    {
        $projectRole = new stdclass();
        $projectRole->id                = $data['id'];
        $projectRole->pid               = isset($data['pid']) ? $data['pid'] : '';
        $projectRole->roletype          = $data['roletype'];
        $projectRole->roletypeparameter = $data['roletypeparameter'];

        return $projectRole;
    }

    /**
     * 构建角色成员数据。
     * Build member ship data.
     *
     * @param  array     $data
     * @access protected
     * @return object
     */
    protected function buildMemberShipData(array $data): object
    {
        $memberShip = new stdclass();
        $memberShip->id              = $data['id'];
        $memberShip->parent_id       = $data['parentId'];
        $memberShip->child_id        = $data['childId'];
        $memberShip->membership_type = $data['membershipType'];
        $memberShip->parent_name     = $data['parentName'];
        $memberShip->child_name      = $data['childName'];

        return $memberShip;
    }

    /**
     * 构建字段视图方案数据。
     * Build field screen layout data.
     *
     * @param  array     $data
     * @access protected
     * @return object
     */
    protected function buildFieldScreenLayoutItemData(array $data): object
    {
        $layout = new stdclass();
        $layout->id              = $data['id'];
        $layout->fieldidentifier = $data['fieldidentifier'];
        $layout->fieldscreentab  = isset($data['fieldscreentab']) ? $data['fieldscreentab'] : '';

        return $layout;
    }

    /**
     * 构建字段配置数据。
     * Build field config scheme data.
     *
     * @param  array     $data
     * @access protected
     * @return object
     */
    protected function buildFieldConfigSchemeData(array $data): object
    {
        $fieldConfig = new stdclass();
        $fieldConfig->id          = $data['id'];
        $fieldConfig->configname  = $data['name'];
        $fieldConfig->description = isset($data['description']) ? $data['description'] : '';
        $fieldConfig->fieldid     = isset($data['fieldid'])     ? $data['fieldid']     : '';

        return $fieldConfig;
    }

    /**
     * 构建字段方案类型数据。
     * Build field config scheme issue type data.
     *
     * @param  array     $data
     * @access protected
     * @return object
     */
    protected function buildFieldConfigSchemeIssueTypeData(array $data): object
    {
        $fieldIssueType = new stdclass();
        $fieldIssueType->id                 = $data['id'];
        $fieldIssueType->issuetype          = isset($data['issuetype'])          ? $data['issuetype']          : '';
        $fieldIssueType->fieldconfigscheme  = isset($data['fieldconfigscheme'])  ? $data['fieldconfigscheme']  : '';
        $fieldIssueType->fieldconfiguration = isset($data['fieldconfiguration']) ? $data['fieldconfiguration'] : '';

        return $fieldIssueType;
    }

    /**
     * 构建工作流数据。
     * Build workflow data.
     *
     * @param  array     $data
     * @access protected
     * @return object
     */
    protected function buildWorkflowData(array $data): object
    {
        $workflow = new stdclass();
        $workflow->id           = $data['id'];
        $workflow->workflowname = $data['name'];
        $workflow->descriptor   = $data['descriptor'];

        return $workflow;
    }

    /**
     * 构建工作流方案数据。
     * Build workflow scheme data.
     *
     * @param  array     $data
     * @access protected
     * @return object
     */
    protected function buildWorkflowSchemeData(array $data): object
    {
        $workflow = new stdclass();
        $workflow->id          = $data['id'];
        $workflow->name        = $data['name'];
        $workflow->description = zget($data, 'description', '');

        return $workflow;
    }

    /**
     * 构建工作日志数据。
     * Build work log data.
     *
     * @param  array     $data
     * @access protected
     * @return object
     */
    protected function buildWorklogData(array $data): object
    {
        $worklog = new stdclass();
        $worklog->id          = $data['id'];
        $worklog->issueid     = $data['issue'];
        $worklog->author      = zget($data, 'author',     '');
        $worklog->worklogbody = zget($data, 'body',       '');
        $worklog->timeworked  = zget($data, 'timeworked', 0);
        $worklog->created     = zget($data, 'created',    NULL);

        return $worklog;
    }

    /**
     * 构建节点关联数据。
     * Build node association data.
     *
     * @param  array     $data
     * @access protected
     * @return object
     */
    protected function buildNodeAssociationData(array $data): object
    {
        $association = new stdclass();
        $association->source_node_id     = $data['sourceNodeId'];
        $association->source_node_entity = $data['sourceNodeEntity'];
        $association->sink_node_id       = $data['sinkNodeId'];
        $association->sink_node_entity   = $data['sinkNodeEntity'];
        $association->association_type   = $data['associationType'];

        return $association;
    }

    /**
     * 构建版本发布数据。
     * Build fix version data.
     *
     * @param  array     $data
     * @access protected
     * @return object
     */
    protected function buildFixVersionData(array $data): object
    {
        $fixVersion = new stdclass();
        $fixVersion->issue   = $data['issue'];
        $fixVersion->version = $data['version'];

        return $fixVersion;
    }

    /**
     * 获取Jira事务与禅道数据的关联关系。
     * Get Jira issue and ZenTao object links.
     *
     * @access protected
     * @return array
     */
    protected function getIssueData(): array
    {
        return $this->dao->dbh($this->dbh)->select('*')->from(JIRA_TMPRELATION)->where('BID')->ne('')->andWhere('extra')->eq('issue')->fetchAll('AID');
    }

    /**
     * 导入user数据。
     * Import jira user.
     *
     * @param  array $dataList
     * @access protected
     * @return bool
     */
    protected function importJiraUser(array $dataList): bool
    {
        $localUsers       = $this->dao->dbh($this->dbh)->select('account')->from(TABLE_USER)->where('deleted')->eq('0')->fetchPairs();
        $userConfig       = $this->session->jiraUser;
        $jiraUserRelation = $this->dao->dbh($this->dbh)->select('AID,BID')->from(JIRA_TMPRELATION)->where('AType')->eq('juser')->fetchPairs();
        foreach($dataList as $data)
        {
            if(!empty($jiraUserRelation[$data->account])) continue;

            /* 如果是atlassian内部帐号，则不导入。 */
            if(strpos($data->email, '@connect.atlassian.com') !== false) continue;

            $user = new stdclass();
            $user->account = $this->processJiraUser($data->account, $data->email);
            if(!isset($localUsers[$user->account]))
            {
                $user->realname = isset($data->realname) ? $data->realname : '';
                $user->password = $userConfig['password'];
                $user->email    = isset($data->email) ? $data->email : '';
                $user->gender   = 'm';
                $user->type     = 'inside';
                $user->join     = !empty($data->join) ? $data->join : helper::now();

                $this->dao->dbh($this->dbh)->replace(TABLE_USER)->data($user, 'group')->exec();

                if(!dao::isError() && !empty($userConfig['group']))
                {
                    $group = new stdclass();
                    $group->account = $user->account;
                    $group->group   = $userConfig['group'];
                    $group->project = '';
                    $this->dao->dbh($this->dbh)->replace(TABLE_USERGROUP)->data($group)->exec();
                }
            }

            $jiraUserRelation[$data->account] = $user->account;
            $this->createTmpRelation('juser', $data->account, 'zuser', $user->account);
        }

        return true;
    }

    /**
     * 记录Jira数据和禅道数据的关联关系。
     * Create data tmp relation.
     *
     * @param  string     $AType
     * @param  string|int $AID
     * @param  string     $BType
     * @param  string|int $BID
     * @param  string     $extra
     * @access protected
     * @return object
     */
    protected function createTmpRelation(string $AType, string|int $AID, string $BType, string|int $BID, string $extra = ''): object
    {
        $relation = new stdclass();
        $relation->AType = $AType;
        $relation->BType = $BType;
        $relation->AID   = $AID;
        $relation->BID   = $BID;
        $relation->extra = $extra;

        $this->dao->dbh($this->dbh)->insert(JIRA_TMPRELATION)->data($relation)->exec();

        return $relation;
    }

    /**
     * 导入project数据。
     * Import jira project.
     *
     * @param  array     $dataList
     * @access protected
     * @return bool
     */
    protected function importJiraProject(array $dataList): bool
    {
        $this->app->loadConfig('execution');
        $this->app->loadLang('doc');

        $projectRoleActor    = $this->getJiraProjectRoleActor();
        $archivedProject     = $this->getJiraArchivedProject($dataList);
        $sprintGroup         = $this->getJiraSprint(array_keys($dataList));
        $jiraProjectRelation = $this->dao->dbh($this->dbh)->select('*')->from(JIRA_TMPRELATION)->where('AType')->eq('jproject')->fetchAll('AID');
        foreach($dataList as $id => $data)
        {
            if(!empty($jiraProjectRelation[$id])) continue;

            $data->status = in_array($data->id, $archivedProject) ? 'closed' : 'wait';

            $project    = $this->createProject($data, $projectRoleActor);
            $executions = $this->createExecution($id, $project, $sprintGroup, $projectRoleActor);
            $productID  = $this->createProduct($project, $executions);

            $this->createTmpRelation('jproject', $id, 'zproject', $project->id);
            $this->createTmpRelation('jproject', $id, 'zproduct', $productID);
            $this->createTmpRelation('joldkey', $data->originalkey, 'jnewkey', $data->pkey, $data->id);
        }

        return true;
    }

    /**
     * 导入issue数据。
     * Import jira issue.
     *
     * @param  array     $dataList
     * @access protected
     * @return bool
     */
    protected function importJiraIssue(array $dataList): bool
    {
        $jiraRelation = $this->session->jiraRelation;
        $relations    = $jiraRelation ? json_decode($jiraRelation, true) : array();

        $projectRelation  = $this->dao->dbh($this->dbh)->select('AID,BID')->from(JIRA_TMPRELATION)->where('AType')->eq('jproject')->andWhere('BType')->eq('zproject')->fetchPairs();
        $productRelation  = $this->dao->dbh($this->dbh)->select('AID,BID')->from(JIRA_TMPRELATION)->where('AType')->eq('jproject')->andWhere('BType')->eq('zproduct')->fetchPairs();
        $projectKeys      = $this->dao->dbh($this->dbh)->select('extra,AID,BID')->from(JIRA_TMPRELATION)->where('AType')->eq('joldkey')->andWhere('BType')->eq('jnewkey')->fetchAll('extra');
        $sprintRelation   = $this->dao->dbh($this->dbh)->select('AID,BID')->from(JIRA_TMPRELATION)->where('AType')->eq('jsprint')->andWhere('BType')->eq('zexecution')->fetchPairs();
        $defaultExecution = $this->dao->dbh($this->dbh)->select('AID,BID')->from(JIRA_TMPRELATION)->where('AType')->eq('jproject')->andWhere('BType')->eq('zexecution')->fetchPairs();
        $issueList        = $this->getIssueData();
        $jiraSprintList   = $this->getJiraSprintIssue();
        $jiraActions      = $this->getJiraWorkflowActions();
        $customFields     = $this->getJiraData($this->session->jiraMethod, 'customfield');
        $fieldValues      = $this->getJiraData($this->session->jiraMethod, 'customfieldvalue');
        $fieldOptions     = $this->getJiraData($this->session->jiraMethod, 'customfieldoption');
        $jiraResolutions  = $this->getJiraData($this->session->jiraMethod, 'resolution');
        $jiraPriList      = $this->getJiraData($this->session->jiraMethod, 'priority');

        $relations = $this->createWorkflow($relations, $jiraActions, $jiraResolutions, $jiraPriList);
        $relations = $this->createWorkflowField($relations, $customFields, $fieldOptions, $jiraResolutions, $jiraPriList);
        $relations = $this->createWorkflowStatus($relations);
        $relations = $this->createWorkflowAction($relations, $jiraActions);
        $relations = $this->createWorkflowGroup($relations, $projectRelation, $productRelation);
        $relations = $this->createResolution($relations);
        $workflows = $this->dao->dbh($this->dbh)->select('module,`table`')->from(TABLE_WORKFLOW)->where('buildin')->eq('0')->fetchPairs();
        foreach($dataList as $id => $data)
        {
            if(!empty($issueList[$data->id])) continue;

            $issueProject = $data->project;
            if(!isset($projectRelation[$issueProject])) continue;

            /* 将自定义字段数据赋值给issue对象。 */
            $data->execution = !empty($jiraSprintList[$data->id]) ? $jiraSprintList[$data->id] : '';
            foreach($fieldValues as $fieldValue)
            {
                if($fieldValue->issue == $data->id)
                {
                    if(!empty($fieldValue->datevalue))
                    {
                        $data->{$fieldValue->customfield} = date('Y-m-d H:i:s', strtotime($fieldValue->datevalue)); // 日期类型是datevalue
                    }
                    elseif(isset($data->{$fieldValue->customfield}))
                    {
                        $data->{$fieldValue->customfield} .= ',' . $fieldValue->stringvalue; // 多选的情况
                    }
                    else
                    {
                        $data->{$fieldValue->customfield} = $fieldValue->stringvalue;
                    }

                    $fieldKey = $customFields[$fieldValue->customfield]->customfieldtypekey;
                    if($fieldKey == 'com.pyxis.greenhopper.jira:gh-sprint' && !empty($sprintRelation[$fieldValue->stringvalue])) $data->execution = $sprintRelation[$fieldValue->stringvalue];
                }
            }

            $projectID    = $projectRelation[$issueProject];
            $productID    = $productRelation[$issueProject];
            $executionID  = !empty($data->execution) ? $data->execution : $defaultExecution[$issueProject];
            $zentaoObject = $relations['zentaoObject'][$data->issuetype];

            if($zentaoObject == 'requirement' || $zentaoObject == 'story' || $zentaoObject == 'epic')
            {
                $this->createStory((int)$productID, (int)$projectID, (int)$executionID, $zentaoObject, $data, $relations);
            }
            elseif($zentaoObject == 'task')
            {
                $this->createTask((int)$projectID, (int)$executionID, $data, $relations);
            }
            elseif($zentaoObject == 'bug')
            {
                $this->createBug((int)$productID, (int)$projectID, (int)$executionID, $data, $relations);
            }
            elseif($zentaoObject == 'testcase')
            {
                $this->createCase((int)$productID, (int)$projectID, (int)$executionID, $data, $relations);
            }
            elseif($zentaoObject == 'feedback')
            {
                $this->createFeedback((int)$productID, $data, $relations);
            }
            elseif($zentaoObject == 'ticket')
            {
                $this->createTicket((int)$productID, $data, $relations);
            }
            else
            {
                $this->createFlowData((int)$productID, (int)$projectID, $zentaoObject, $data, $relations, $workflows);
            }

            $oldKey   = zget($projectKeys[$issueProject], 'AID', '');
            $newKey   = zget($projectKeys[$issueProject], 'BID', '');
            $issueKey = $oldKey ? $oldKey . '-' . $data->issuenum : $newKey . '-' . $data->issuenum;
            $this->createTmpRelation('jissueid', $data->id, 'jfilepath', '', "{$oldKey}/10000/{$issueKey}/");
        }

        return true;
    }

    /**
     * 导入版本数据。
     * Import jira build.
     *
     * @param  array     $dataList
     * @access protected
     * @return bool
     */
    protected function importJiraBuild(array $dataList): bool
    {
        $issueList = $this->getIssueData();

        $projectRelation = $this->dao->dbh($this->dbh)->select('AID,BID')->from(JIRA_TMPRELATION)
            ->where('AType')->eq('jproject')
            ->andWhere('BType')->eq('zproject')
            ->fetchPairs();

        $projectProduct = $this->dao->dbh($this->dbh)->select('project,product')->from(TABLE_PROJECTPRODUCT)
            ->where('project')->in(array_values($projectRelation))
            ->fetchPairs();

        $productSystem = $this->dao->dbh($this->dbh)->select('id,product')->from(TABLE_SYSTEM)->fetchAll('product');

        $versionGroup    = array();
        $nodeassociation = $this->getJiraData($this->session->jiraMethod, 'nodeassociation');
        $fixVersion      = $this->getJiraData($this->session->jiraMethod, 'fixversion');
        foreach($nodeassociation as $node)
        {
            if($node->sink_node_entity == 'Version' && $node->association_type == 'IssueFixVersion' && $node->source_node_entity == 'Issue')
            {
                $versionGroup[$node->sink_node_id][] = $node->source_node_id;
            }
        }
        foreach($fixVersion as $version)
        {
            $versionGroup[$version->version][] = $version->issue;
        }

        $versionRelation = $this->dao->dbh($this->dbh)->select('*')->from(JIRA_TMPRELATION)->where('AType')->eq('jversion')->fetchAll('AID');
        foreach($dataList as $data)
        {
            if(!empty($versionRelation[$data->id])) continue;

            $buildProject = $data->project;
            $projectID    = $projectRelation[$buildProject];
            $productID    = $projectProduct[$projectID];
            $system       = !empty($productSystem[$productID]) ? $productSystem[$productID]->id : 0;
            $build        = $this->createBuild((int)$productID, (int)$projectID, $system, $data, $versionGroup, $issueList);

            $this->createRelease($build, $data, !empty($versionGroup[$data->id]) ? $versionGroup[$data->id] : array(), $issueList);

            $this->createTmpRelation('jversion', $data->id, 'zbuild', $build->id);
        }

        return true;
    }

    /**
     * 导入issue link数据。
     * Import jira issue link.
     *
     * @param  array     $dataList
     * @access protected
     * @return bool
     */
    protected function importJiraIssueLink(array $dataList): bool
    {
        $issueLinkTypeList = array();
        $issueList         = $this->getIssueData();
        $jiraRelation      = $this->session->jiraRelation;
        $relations         = $jiraRelation ? json_decode($jiraRelation, true) : array();

        if($this->config->edition != 'open') $relations = $this->createRelation($relations);

        $storyLink = $taskLink = $duplicateLink = $relatesLink = array();
        $issueLinkTypeList = $relations['zentaoLinkType'];
        foreach($dataList as $issueLink)
        {
            $linkType = $issueLink->linktype;
            if($issueLinkTypeList[$linkType] == 'subStoryLink') $storyLink[$issueLink->source][]   = $issueLink->destination;
            if($issueLinkTypeList[$linkType] == 'subTaskLink')  $taskLink[$issueLink->source][]    = $issueLink->destination;
            if($issueLinkTypeList[$linkType] == 'duplicate')    $duplicateLink[$issueLink->source] = $issueLink->destination;
            if($issueLinkTypeList[$linkType] == 'relates')      $relatesLink[$issueLink->source]   = $issueLink->destination;
        }

        $this->updateSubStory($storyLink, $issueList);
        $this->updateSubTask($taskLink, $issueList);
        $this->updateDuplicateStoryAndBug($duplicateLink, $issueList);
        $this->updateRelatesObject($relatesLink, $issueList);
        if($this->config->edition != 'open') $this->updateObjectRelation($issueLinkTypeList, $dataList, $issueList);

        return true;
    }

    /**
     * 导入工作日志数据。
     * Import jira worklog.
     *
     * @param  array     $dataList
     * @access protected
     * @return bool
     */
    protected function importJiraWorkLog(array $dataList): bool
    {
        $issueList       = $this->getIssueData();
        $worklogRelation = $this->dao->dbh($this->dbh)->select('*')->from(JIRA_TMPRELATION)->where('AType')->eq('jworklog')->fetchAll('AID');
        foreach($dataList as $data)
        {
            if(!empty($worklogRelation[$data->id])) continue;

            $issueID = $data->issueid;
            if(!isset($issueList[$issueID])) continue;

            $objectType = zget($issueList[$issueID], 'BType', '');
            $objectID   = zget($issueList[$issueID], 'BID',   '');

            if(empty($objectID)) continue;

            $effort = new stdclass();
            $effort->vision     = $this->config->vision;
            $effort->objectID   = $objectID;
            $effort->date       = !empty($data->created) ? substr($data->created, 0, 10) : null;
            $effort->account    = $this->getJiraAccount(isset($data->author) ? $data->author : '');
            $effort->consumed   = round($data->timeworked / 3600);
            $effort->work       = $data->worklogbody;
            $effort->objectType = substr($objectType, 1);
            $this->dao->dbh($this->dbh)->insert(TABLE_EFFORT)->data($effort)->exec();
            $effortID = $this->dao->dbh($this->dbh)->lastInsertID();

            $this->createTmpRelation('jworklog', $data->id, 'zeffort', $effortID);
        }

        return true;
    }

    /**
     * 导入issue评论数据。
     * Import jira action.
     *
     * @param  array     $dataList
     * @access protected
     * @return bool
     */
    protected function importJiraAction(array $dataList): bool
    {
        $issueList = $this->getIssueData();

        $actionRelation = $this->dao->dbh($this->dbh)->select('*')->from(JIRA_TMPRELATION)->where('AType')->eq('jaction')->fetchAll('AID');

        foreach($dataList as $data)
        {
            if(!empty($actionRelation[$data->id])) continue;

            $issueID = $data->issueid;
            $comment = $data->actionbody;
            if(empty($comment)) continue;

            if(!isset($issueList[$issueID])) continue;

            $objectType = zget($issueList[$issueID], 'BType', '');
            $objectID   = zget($issueList[$issueID], 'BID',   '');

            if(empty($objectID)) continue;

            $action = new stdclass();
            $action->objectType = substr($objectType, 1);
            $action->objectID   = $objectID;
            $action->actor      = $this->getJiraAccount(isset($data->author) ? $data->author : '');
            $action->action     = 'commented';
            $action->date       = isset($data->created) ? substr($data->created, 0, 19) : '';
            $action->comment    = $comment;
            $this->dao->dbh($this->dbh)->insert(TABLE_ACTION)->data($action)->exec();
            $actionID = $this->dao->dbh($this->dbh)->lastInsertID();

            $this->createTmpRelation('jaction', $data->id, 'zaction', $actionID);
        }

        return true;
    }

    /**
     * 导入file数据。
     * Import jira file.
     *
     * @param  array     $dataList
     * @access protected
     * @return bool
     */
    protected function importJiraFile(array $dataList): bool
    {
        $this->loadModel('file');

        $issueList = $this->getIssueData();

        $filePaths = $this->dao->dbh($this->dbh)->select('AID,extra')->from(JIRA_TMPRELATION)
            ->where('AType')->eq('jissueid')
            ->andWhere('BType')->eq('jfilepath')
            ->fetchPairs();

        $fileRelation = $this->dao->dbh($this->dbh)->select('*')->from(JIRA_TMPRELATION)->where('AType')->eq('jfile')->fetchAll('AID');

        foreach($dataList as $fileAttachment)
        {
            if(!empty($fileRelation[$fileAttachment->id])) continue;

            $issueID = $fileAttachment->issueid;
            if(!isset($issueList[$issueID])) continue;

            $objectType = zget($issueList[$issueID], 'BType', '');
            $objectID   = zget($issueList[$issueID], 'BID',   '');

            if(empty($objectID)) continue;

            $fileID   = $fileAttachment->id;
            $fileName = $fileAttachment->filename;
            list($mime, $extension) = explode('/', $fileAttachment->mimetype);

            $file = new stdclass();
            $file->pathname   = $this->file->setPathName((int)$fileID, $extension);
            $file->title      = str_ireplace(".{$extension}", '', $fileName);
            $file->extension  = $extension;
            $file->size       = $fileAttachment->filesize;
            $file->objectType = substr($objectType, 1);
            $file->objectID   = $objectID;
            $file->extra      = in_array($file->objectType, array('epic', 'requirement', 'story')) ? '1' : '';
            $file->addedBy    = $this->getJiraAccount(isset($fileAttachment->author) ? $fileAttachment->author : '');
            $file->addedDate  = !empty($fileAttachment->created) ? substr($fileAttachment->created, 0, 19) : null;
            $this->dao->dbh($this->dbh)->insert(TABLE_FILE)->data($file)->exec();

            $jiraFile = $this->app->getTmpRoot() . 'attachments/' . $filePaths[$issueID] .  $fileID;
            if(is_file($jiraFile)) copy($jiraFile, $this->file->savePath . $file->pathname);

            $fileID = $this->dao->dbh($this->dbh)->lastInsertID();

            if(in_array($file->objectType, array('epic', 'requirement', 'story')))
            {
                $this->dao->dbh($this->dbh)->update(TABLE_STORYSPEC)->set("files=IF(files IS NOT NULL, CONCAT(files,',{$fileID},'), '{$fileID}')")->where('story')->eq($file->objectID)->andWhere('version')->eq($file->extra)->exec();

            }

            $this->createTmpRelation('jfile', $fileAttachment->id, 'zfile', $fileID);
        }

        return true;
    }

    /**
     * 创建团队成员。
     * Create team member.
     *
     * @param  int       $objectID
     * @param  string    $createdBy
     * @param  string    $type
     * @access protected
     * @return bool
     */
    protected function createTeamMember(int $objectID, string $createdBy, string $type): bool
    {
        $member = new stdclass();
        $member->root    = $objectID;
        $member->account = $createdBy;
        $member->role    = '';
        $member->join    = helper::now();
        $member->type    = $type;
        $member->days    = 0;
        $member->hours   = $this->config->execution->defaultWorkhours;

        $this->dao->dbh($this->dbh)->replace(TABLE_TEAM)->data($member)->exec();

        return true;
    }

    /**
     * 为项目或者迭代创建默认的文档库。
     * Create doc lib.
     *
     * @param  int       $projectID
     * @param  int       $executionID
     * @param  string    $name
     * @param  string    $type
     * @access protected
     * @return boll
     */
    protected function createDocLib(int $projectID, int $executionID, string $name, string $type): bool
    {
        $docLib = new stdclass();
        $docLib->project   = $projectID;
        $docLib->execution = $executionID;
        $docLib->name      = $name;
        $docLib->type      = $type;
        $docLib->main      = '1';
        $docLib->acl       = 'default';

        $this->dao->dbh($this->dbh)->insert(TABLE_DOCLIB)->data($docLib)->exec();

        return true;
    }

    /**
     * 创建项目。
     * Create project.
     *
     * @param  object    $data
     * @param  array     $projectRoleActor
     * @access protected
     * @return object
     */
    protected function createProject(object $data, array $projectRoleActor): object
    {
        /* Create project. */
        $project = new stdclass();
        $project->name          = substr($data->pname, 0, 90);
        $project->code          = $data->pkey;
        $project->desc          = isset($data->description) ? $data->description : '';
        $project->status        = $data->status;
        $project->type          = 'project';
        $project->model         = 'scrum';
        $project->grade         = 1;
        $project->acl           = 'open';
        $project->auth          = 'extend';
        $project->end           = date('Y-m-d', time() + 30 * 24 * 3600);
        $project->PM            = $this->getJiraAccount(isset($data->lead) ? $data->lead : '');
        $project->openedBy      = $this->getJiraAccount(isset($data->lead) ? $data->lead : '');
        $project->openedDate    = helper::now();
        $project->openedVersion = $this->config->version;
        $project->storyType     = 'story,epic,requirement';

        $this->dao->dbh($this->dbh)->insert(TABLE_PROJECT)->data($project)->exec();

        $projectID = $this->dao->dbh($this->dbh)->lastInsertID();
        $this->loadModel('action')->create('project', $projectID, 'opened');

        $this->dao->dbh($this->dbh)->update(TABLE_PROJECT)->set('`order`')->eq($projectID * 5)->set('path')->eq(",$projectID,")->where('id')->eq($projectID)->exec();

        $this->createTeamMember($projectID, $project->openedBy, 'project');
        /* 如果Jira项目有团队成员。 */
        if(!empty($projectRoleActor[$data->id]))
        {
            foreach($projectRoleActor[$data->id] as $userID)
            {
                $account = $this->getJiraAccount($userID);
                if(!$account) continue;
                if($account == $project->openedBy) continue;

                $this->createTeamMember($projectID, $account, 'project');
            }
        }

        $this->createDocLib($projectID, 0, $this->lang->doclib->main['project'], 'project');

        $project->id = $projectID;
        return $project;
    }

    /**
     * 为项目创建默认的同名执行。
     * Create default execution.
     *
     * @param  int       $jiraProjectID
     * @param  object    $project
     * @param  array     $projectRoleActor
     * @access protected
     * @return int
     */
    protected function createDefaultExecution(int $jiraProjectID, object $project, array $projectRoleActor): int
    {
        /* Create default execution. */
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
        $this->loadModel('action')->create('execution', $executionID, 'opened');

        $this->dao->dbh($this->dbh)->update(TABLE_PROJECT)->set('`order`')->eq($executionID * 5)->set('path')->eq(",{$project->id},$executionID,")->where('id')->eq($executionID)->exec();

        $this->createTeamMember($executionID, $execution->openedBy, 'execution');

        /* 如果Jira项目有团队成员。 */
        if(!empty($projectRoleActor[$jiraProjectID]))
        {
            foreach($projectRoleActor[$jiraProjectID] as $userID)
            {
                $account = $this->getJiraAccount($userID);
                if(!$account) continue;
                if($account == $execution->openedBy) continue;

                $this->createTeamMember($executionID, $account, 'execution');
            }
        }

        $this->createDocLib($project->id, $executionID, $this->lang->doclib->main['execution'], 'execution');
        $this->createTmpRelation('jproject', $jiraProjectID, 'zexecution', $executionID);

        return $executionID;
    }

    /**
     * 创建执行。
     * Create execution.
     *
     * @param  int       $jiraProjectID
     * @param  object    $project
     * @param  array     $sprintGroup
     * @param  array     $projectRoleActor
     * @access protected
     * @return array
     */
    protected function createExecution(int $jiraProjectID, object $project, array $sprintGroup, array $projectRoleActor): array
    {
        $executions = array();
        $executions[] = $this->createDefaultExecution($jiraProjectID, $project, $projectRoleActor);

        if(!empty($sprintGroup[$jiraProjectID]))
        {
            $zentaoStatus = array('future' => 'wait', 'active' => 'doing', 'closed' => 'closed');
            foreach($sprintGroup[$jiraProjectID] as $sprint)
            {
                /* Create execution. */
                $execution = new stdclass();
                $execution->name          = $sprint->name;
                $execution->code          = 'jirasprint' . $sprint->id;
                $execution->status        = isset($sprint->state) ? zget($zentaoStatus, $sprint->state, 'wait') : 'wait';
                $execution->project       = $project->id;
                $execution->parent        = $project->id;
                $execution->grade         = 1;
                $execution->desc          = !empty($sprint->goal) ? $sprint->goal : '';
                $execution->type          = 'sprint';
                $execution->acl           = 'open';
                $execution->begin         = !empty($sprint->startDate) ? substr($sprint->startDate, 0, 10) : helper::now();
                $execution->end           = !empty($sprint->endDate)   ? substr($sprint->endDate, 0, 10)   : date('Y-m-d', time() + 24 * 3600);
                $execution->PM            = $project->PM;
                $execution->openedBy      = $project->openedBy;
                $execution->openedDate    = helper::now();
                $execution->openedVersion = $this->config->version;

                $this->dao->dbh($this->dbh)->insert(TABLE_PROJECT)->data($execution)->exec();
                $executionID = $this->dao->dbh($this->dbh)->lastInsertID();
                $this->loadModel('action')->create('execution', $executionID, 'opened');

                $this->dao->dbh($this->dbh)->update(TABLE_PROJECT)->set('`order`')->eq($executionID * 5)->set('path')->eq(",{$project->id},$executionID,")->where('id')->eq($executionID)->exec();

                $this->createTeamMember($executionID, $execution->openedBy, 'execution');

                if(!empty($projectRoleActor[$jiraProjectID]))
                {
                    foreach($projectRoleActor[$jiraProjectID] as $userID)
                    {
                        $account = $this->getJiraAccount($userID);
                        if(!$account) continue;
                        if($account == $execution->openedBy) continue;

                        $this->createTeamMember($executionID, $account, 'execution');
                    }
                }

                $this->createDocLib($project->id, $executionID, $this->lang->doclib->main['execution'], 'execution');
                $this->createTmpRelation('jsprint', $sprint->id, 'zexecution', $executionID);

                $executions[] = $executionID;
            }
        }

        return $executions;
    }

    /**
     * 创建产品。
     * Create product.
     *
     * @param  object    $project
     * @param  array     $executions
     * @access protected
     * @return int
     */
    protected function createProduct(object $project, array $executions): int
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
        $this->loadModel('action')->create('product', $productID, 'opened');

        /* 创建产品同名的应用。 */
        $system = new stdclass();
        $system->name        = substr($product->name, 0, 80) . '-' . uniqid();
        $system->product     = $productID;
        $system->status      = 'active';
        $system->desc        = '';
        $system->integrated  = 0;
        $system->createdBy   = $this->app->user->account;
        $system->createdDate = helper::now();
        $this->loadModel('system')->create($system);

        $this->dao->dbh($this->dbh)->update(TABLE_PRODUCT)->set('`order`')->eq($productID * 5)->where('id')->eq($productID)->exec();
        $this->dao->dbh($this->dbh)->replace(TABLE_PROJECTPRODUCT)->set('project')->eq($project->id)->set('product')->eq($productID)->set('branch')->eq('0')->exec();

        $this->createDocLib($productID, 0, $this->lang->doclib->main['product'], 'product');

        /* 关联产品与迭代。 */
        foreach($executions as $executionID) $this->dao->dbh($this->dbh)->replace(TABLE_PROJECTPRODUCT)->set('project')->eq($executionID)->set('product')->eq($productID)->set('branch')->eq('0')->exec();

        return $productID;
    }

    /**
     * 创建需求。
     * Create story.
     *
     * @param  int       $productID
     * @param  int       $projectID
     * @param  int       $executionID
     * @param  string    $type
     * @param  object    $data
     * @param  array     $reasonList
     * @access protected
     * @return bool
     */
    protected function createStory(int $productID, int $projectID, int $executionID, string $type, object $data, array $relations): bool
    {
        $this->app->loadLang('story');

        $story      = new stdclass();
        $jiraFields = !empty($relations["zentaoField{$data->issuetype}"]) ? $relations["zentaoField{$data->issuetype}"] : array();
        foreach($jiraFields as $jiraField => $zentaoField)
        {
            if(!empty($data->{$jiraField})) $story->{$zentaoField} = $data->{$jiraField};
        }

        foreach($this->lang->convert->jira->buildinFields as $fieldCode => $buildinField)
        {
            if(isset($buildinField['buildin']) && $buildinField['buildin'] === false) continue;
            if(!empty($data->{$buildinField['jiraField']})) $story->{$fieldCode} = $data->{$buildinField['jiraField']};
        }

        $story->title      = $data->summary;
        $story->type       = $type;
        $story->product    = $productID;
        $story->pri        = $data->priority;
        $story->version    = 1;
        $story->grade      = 1;
        $story->stage      = $this->convertStage($data->issuestatus, $data->issuetype, $relations);
        $story->status     = $this->convertStatus('story', $data->issuestatus, $data->issuetype, $relations);
        $story->openedBy   = $this->getJiraAccount(isset($data->creator) ? $data->creator : '');
        $story->openedDate = !empty($data->created) ? substr($data->created, 0, 19) : null;
        $story->assignedTo = $this->getJiraAccount(isset($data->assignee) ? $data->assignee : '');

        if($story->assignedTo) $story->assignedDate = helper::now();

        if($data->resolution)
        {
            $story->closedReason = zget($relations["zentaoReason{$data->issuetype}"], $data->resolution, '');
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
            $storyDesc->spec    = isset($data->description) ? $data->description : '';
            $this->dao->dbh($this->dbh)->replace(TABLE_STORYSPEC)->data($storyDesc)->exec();

            $this->dao->dbh($this->dbh)->replace(TABLE_PROJECTSTORY)->set('project')->eq($projectID)
                ->set('product')->eq($productID)
                ->set('story')->eq($storyID)
                ->set('version')->eq('1')
                ->exec();

            if($type == 'story')
            {
                $this->dao->dbh($this->dbh)->replace(TABLE_PROJECTSTORY)->set('project')->eq($executionID)
                    ->set('product')->eq($productID)
                    ->set('story')->eq($storyID)
                    ->set('version')->eq('1')
                    ->exec();
            }

            /* Create opened action from openedDate. */
            $action = new stdclass();
            $action->objectType = 'story';
            $action->objectID   = $storyID;
            $action->actor      = $story->openedBy;
            $action->action     = 'Opened';
            $action->date       = $story->openedDate;
            $this->dao->dbh($this->dbh)->insert(TABLE_ACTION)->data($action)->exec();

            $this->createTmpRelation("j$type", $data->id, "z$type", $storyID, 'issue');
            $this->createTmpRelation('jissueid', $data->id, 'zissuetype', '', $type);
        }

        return true;
    }

    /**
     * 创建任务。
     * Create task.
     *
     * @param  int       $projectID
     * @param  int       $executionID
     * @param  object    $data
     * @param  array     $relations
     * @access protected
     * @return bool
     */
    protected function createTask(int $projectID, int $executionID, object $data, array $relations): bool
    {
        $this->app->loadLang('task');

        $task       = new stdclass();
        $jiraFields = !empty($relations["zentaoField{$data->issuetype}"]) ? $relations["zentaoField{$data->issuetype}"] : array();
        foreach($jiraFields as $jiraField => $zentaoField)
        {
            if(!empty($data->{$jiraField})) $task->{$zentaoField} = $data->{$jiraField};
        }

        foreach($this->lang->convert->jira->buildinFields as $fieldCode => $buildinField)
        {
            if(isset($buildinField['buildin']) && $buildinField['buildin'] === false) continue;
            if(!empty($data->{$buildinField['jiraField']})) $task->{$fieldCode} = $data->{$buildinField['jiraField']};
        }

        $task->project    = $projectID;
        $task->execution  = $executionID;
        $task->name       = $data->summary;
        $task->type       = 'devel';
        $task->estimate   = !empty($data->timeoriginalestimate) ? round($data->timeoriginalestimate / 3600) : 0;
        $task->left       = !empty($data->timeestimate)         ? round($data->timeestimate / 3600)         : 0;
        $task->consumed   = !empty($data->timespent)            ? round($data->timespent / 3600)            : 0;
        $task->pri        = $data->priority;
        $task->status     = $this->convertStatus('task', $data->issuestatus, $data->issuetype, $relations);
        $task->desc       = isset($data->description) ? $data->description: '';
        $task->openedBy   = $this->getJiraAccount(isset($data->creator) ? $data->creator : '');
        $task->openedDate = !empty($data->created) ? substr($data->created, 0, 19) : null;
        $task->assignedTo = $this->getJiraAccount(isset($data->assignee) ? $data->assignee : '');

        if($task->assignedTo) $task->assignedDate = helper::now();

        if($data->resolution)
        {
            $reasonList         = $relations["zentaoReason{$data->issuetype}"];
            $task->closedReason = zget($reasonList, $data->resolution, '');
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

        $this->createTmpRelation('jtask', $data->id, 'ztask', $taskID, 'issue');
        $this->createTmpRelation('jissueid', $data->id, 'zissuetype', '', 'task');

        return true;
    }

    /**
     * 创建BUG。
     * Create bug.
     *
     * @param  int       $productID
     * @param  int       $projectID
     * @param  int       $executionID
     * @param  object    $data
     * @param  array     $relations
     * @access protected
     * @return bool
     */
    protected function createBug(int $productID, int $projectID, int $executionID, object $data, array $relations): bool
    {
        $this->app->loadLang('bug');

        $bug        = new stdclass();
        $jiraFields = !empty($relations["zentaoField{$data->issuetype}"]) ? $relations["zentaoField{$data->issuetype}"] : array();
        foreach($jiraFields as $jiraField => $zentaoField)
        {
            if(!empty($data->{$jiraField})) $bug->{$zentaoField} = $data->{$jiraField};
        }

        foreach($this->lang->convert->jira->buildinFields as $fieldCode => $buildinField)
        {
            if(isset($buildinField['buildin']) && $buildinField['buildin'] === false) continue;
            if(!empty($data->{$buildinField['jiraField']})) $bug->{$fieldCode} = $data->{$buildinField['jiraField']};
        }

        $bug->product     = $productID;
        $bug->project     = $projectID;
        $bug->execution   = $executionID;
        $bug->title       = $data->summary;
        $bug->pri         = $data->priority;
        $bug->status      = $this->convertStatus('bug', $data->issuestatus, $data->issuetype, $relations);
        $bug->steps       = isset($data->description) ? $data->description : '';
        $bug->openedBy    = $this->getJiraAccount(isset($data->creator) ? $data->creator : '');
        $bug->openedDate  = !empty($data->created) ? substr($data->created, 0, 19) : null;
        $bug->openedBuild = 'trunk';
        $bug->assignedTo  = $bug->status == 'closed' ? 'closed' : $this->getJiraAccount(isset($data->assignee) ? $data->assignee : '');

        if($bug->assignedTo) $bug->assignedDate = helper::now();

        if($data->resolution)
        {
            $resolutionList  = $relations["zentaoResolution{$data->issuetype}"];
            $bug->resolution = zget($resolutionList, $data->resolution, '');
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

        $this->createTmpRelation('jbug', $data->id, 'zbug', $bugID, 'issue');
        $this->createTmpRelation('jissueid', $data->id, 'zissuetype', '', 'bug');

        return true;
    }

    /**
     * 创建测试用例。
     * Create case.
     *
     * @param  int       $productID
     * @param  int       $projectID
     * @param  int       $executionID
     * @param  object    $data
     * @param  array     $relations
     * @access protected
     * @return bool
     */
    protected function createCase(int $productID, int $projectID, int $executionID, object $data, array $relations): bool
    {
        $case       = new stdclass();
        $jiraFields = !empty($relations["zentaoField{$data->issuetype}"]) ? $relations["zentaoField{$data->issuetype}"] : array();
        foreach($jiraFields as $jiraField => $zentaoField)
        {
            if(!empty($data->{$jiraField})) $case->{$zentaoField} = $data->{$jiraField};
        }

        foreach($this->lang->convert->jira->buildinFields as $fieldCode => $buildinField)
        {
            if(isset($buildinField['buildin']) && $buildinField['buildin'] === false) continue;
            if(!empty($data->{$buildinField['jiraField']})) $case->{$fieldCode} = $data->{$buildinField['jiraField']};
        }

        $case->product    = $productID;
        $case->execution  = $executionID;
        $case->type       = 'feature';
        $case->version    = '1';
        $case->title      = $data->summary;
        $case->pri        = $data->priority;
        $case->status     = $this->convertStatus('testcase', $data->issuestatus, $data->issuetype, $relations);
        $case->openedBy   = $this->getJiraAccount(isset($data->creator) ? $data->creator : '');
        $case->openedDate = !empty($data->created) ? substr($data->created, 0, 19) : null;

        $this->dao->dbh($this->dbh)->insert(TABLE_CASE)->data($case)->exec();
        $caseID = $this->dao->dbh($this->dbh)->lastInsertID();

        /* Create opened action from openedDate. */
        $action = new stdclass();
        $action->objectType = 'testcase';
        $action->objectID   = $caseID;
        $action->actor      = $case->openedBy;
        $action->action     = 'Opened';
        $action->date       = $case->openedDate;
        $this->dao->dbh($this->dbh)->insert(TABLE_ACTION)->data($action)->exec();

        $spec               = new stdclass();
        $spec->case         = $caseID;
        $spec->version      = '1';
        $spec->title        = $case->title;
        $spec->precondition = zget($case, 'precondition', '');
        $spec->files        = '';
        $this->dao->insert(TABLE_CASESPEC)->data($spec)->exec();

        $this->loadModel('testcase')->syncCase2Project($case, $caseID);

        $this->createTmpRelation('jcase', $data->id, 'ztestcase', $caseID, 'issue');
        $this->createTmpRelation('jissueid', $data->id, 'zissuetype', '', 'case');

        return true;
    }

    /**
     * 创建反馈。
     * Create feedback.
     *
     * @param  int       $productID
     * @param  object    $data
     * @param  array     $relations
     * @access protected
     * @return bool
     */
    protected function createFeedback(int $productID, object $data, array $relations): bool
    {
        $feedback   = new stdclass();
        $jiraFields = !empty($relations["zentaoField{$data->issuetype}"]) ? $relations["zentaoField{$data->issuetype}"] : array();
        foreach($jiraFields as $jiraField => $zentaoField)
        {
            if(!empty($data->{$jiraField})) $feedback->{$zentaoField} = $data->{$jiraField};
        }

        foreach($this->lang->convert->jira->buildinFields as $fieldCode => $buildinField)
        {
            if(isset($buildinField['buildin']) && $buildinField['buildin'] === false) continue;
            if(!empty($data->{$buildinField['jiraField']})) $feedback->{$fieldCode} = $data->{$buildinField['jiraField']};
        }

        $feedback->product     = $productID;
        $feedback->title       = $data->summary;
        $feedback->public      = '1';
        $feedback->pri         = $data->priority;
        $feedback->status      = $this->convertStatus('feedback', $data->issuestatus, $data->issuetype, $relations);
        $feedback->desc        = isset($data->description) ? $data->description : '';
        $feedback->openedBy    = $this->getJiraAccount(isset($data->creator) ? $data->creator : '');
        $feedback->openedDate  = !empty($data->created) ? substr($data->created, 0, 19) : null;
        $feedback->assignedTo  = $feedback->status == 'closed' ? 'closed' : $this->getJiraAccount(isset($data->assignee) ? $data->assignee : '');

        if($feedback->assignedTo) $feedback->assignedDate = helper::now();

        $this->dao->dbh($this->dbh)->insert(TABLE_FEEDBACK)->data($feedback)->exec();
        $feedbackID = $this->dao->dbh($this->dbh)->lastInsertID();

        /* Create opened action from openedDate. */
        $action = new stdclass();
        $action->objectType = 'feedback';
        $action->objectID   = $feedbackID;
        $action->actor      = $feedback->openedBy;
        $action->action     = 'Opened';
        $action->date       = $feedback->openedDate;
        $this->dao->dbh($this->dbh)->insert(TABLE_ACTION)->data($action)->exec();

        $this->loadModel('feedback')->updateSubStatus($feedbackID, $feedback->status);

        $this->createTmpRelation('jfeedback', $data->id, 'zfeedback', $feedbackID, 'issue');
        $this->createTmpRelation('jissueid', $data->id, 'zissuetype', '', 'feedback');

        return true;
    }

    /**
     * 创建工单。
     * Create ticket.
     *
     * @param  int       $productID
     * @param  object    $data
     * @param  array     $relations
     * @access protected
     * @return bool
     */
    protected function createTicket(int $productID, object $data, array $relations): bool
    {
        $ticket     = new stdclass();
        $jiraFields = !empty($relations["zentaoField{$data->issuetype}"]) ? $relations["zentaoField{$data->issuetype}"] : array();
        foreach($jiraFields as $jiraField => $zentaoField)
        {
            if(!empty($data->{$jiraField})) $ticket->{$zentaoField} = $data->{$jiraField};
        }

        foreach($this->lang->convert->jira->buildinFields as $fieldCode => $buildinField)
        {
            if(isset($buildinField['buildin']) && $buildinField['buildin'] === false) continue;
            if(!empty($data->{$buildinField['jiraField']})) $ticket->{$fieldCode} = $data->{$buildinField['jiraField']};
        }

        $ticket->product     = $productID;
        $ticket->title       = $data->summary;
        $ticket->type        = 'code';
        $ticket->pri         = $data->priority;
        $ticket->status      = $this->convertStatus('ticket', $data->issuestatus, $data->issuetype, $relations, $relations);
        $ticket->desc        = isset($data->description) ? $data->description : '';
        $ticket->openedBy    = $this->getJiraAccount(isset($data->creator) ? $data->creator : '');
        $ticket->openedDate  = !empty($data->created) ? substr($data->created, 0, 19) : null;
        $ticket->assignedTo  = $ticket->status == 'closed' ? 'closed' : $this->getJiraAccount(isset($data->assignee) ? $data->assignee : '');
        $ticket->openedBuild = 'trunk';

        if($ticket->assignedTo) $ticket->assignedDate = helper::now();

        $this->dao->dbh($this->dbh)->insert(TABLE_TICKET)->data($ticket)->exec();
        $ticketID = $this->dao->dbh($this->dbh)->lastInsertID();

        /* Create opened action from openedDate. */
        $action = new stdclass();
        $action->objectType = 'ticket';
        $action->objectID   = $ticketID;
        $action->actor      = $ticket->openedBy;
        $action->action     = 'Opened';
        $action->date       = $ticket->openedDate;
        $this->dao->dbh($this->dbh)->insert(TABLE_ACTION)->data($action)->exec();

        $this->createTmpRelation('jticket', $data->id, 'zticket', $ticketID, 'issue');
        $this->createTmpRelation('jissueid', $data->id, 'zissuetype', '', 'ticket');

        return true;
    }

    /**
     * 创建版本。
     * Create build.
     *
     * @param  int       $productID
     * @param  int       $projectID
     * @param  int       $systemID
     * @param  object    $data
     * @param  array     $versionGroup
     * @param  array     $issueList
     * @access protected
     * @return object
     */
    protected function createBuild(int $productID, int $projectID, int $systemID, object $data, array $versionGroup, array $issueList): object
    {
        /* Create build. */
        $build = new stdclass();
        $build->product     = $productID;
        $build->project     = $projectID;
        $build->system      = $systemID;
        $build->name        = $data->vname;
        $build->date        = !empty($data->releasedate) ? substr($data->releasedate, 0, 10) : null;
        $build->builder     = $this->app->user->account;
        $build->createdBy   = $this->app->user->account;
        $build->createdDate = helper::now();
        $this->dao->dbh($this->dbh)->insert(TABLE_BUILD)->data($build)->exec();

        $buildID   = $this->dao->dbh($this->dbh)->lastInsertID();
        $versionid = $data->id;

        /* Process build data. */
        if(isset($versionGroup[$versionid]))
        {
            foreach($versionGroup[$versionid] as $issue)
            {
                $issueID   = $this->session->jiraMethod == 'db' ? $issue->issueid : $issue;
                $objectID  = zget($issueList[$issueID], 'BID',   '');
                $issueType = zget($issueList[$issueID], 'BType', '');
                if(!$issueType || ($issueType != 'zstory' && $issueType != 'zbug')) continue;

                if($issueType == 'zstory')
                {
                    $this->dao->dbh($this->dbh)->update(TABLE_BUILD)->set("stories = IF(stories IS NOT NULL, CONCAT(stories, ',$objectID'), '{$objectID}')")->where('id')->eq($buildID)->exec();
                }
                if($issueType == 'zbug')
                {
                    $this->dao->dbh($this->dbh)->update(TABLE_BUILD)->set("bugs = IF(bugs IS NOT NULL, CONCAT(bugs, ',$objectID'), {$objectID})")->where('id')->eq($buildID)->exec();
                    if(!isset($issue->relation)) continue;

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
     * @param  object    $build
     * @param  object    $data
     * @param  array     $releaseIssue
     * @param  array     $issueList
     * @access protected
     * @return bool
     */
    protected function createRelease(object $build, object $data, array $releaseIssue, array $issueList): bool
    {
        /* Create release. */
        $release = new stdclass();
        $release->product      = $build->product;
        $release->project      = $build->project;
        $release->system       = $build->system;
        $release->build        = $build->id;
        $release->name         = $build->name;
        $release->date         = helper::isZeroDate($data->startdate) ? NULL : substr($data->startdate, 0, 10);
        $release->desc         = isset($data->description) ? $data->description : '';
        $release->status       = empty($data->released) ? 'wait' : 'normal';
        $release->releasedDate = !empty($data->released) ? substr($data->releasedate, 0, 10) : null;
        $release->createdBy    = $this->app->user->account;
        $release->createdDate  = helper::now();
        $this->dao->dbh($this->dbh)->insert(TABLE_RELEASE)->data($release)->exec();

        $releaseID = $this->dao->dbh($this->dbh)->lastInsertID();

        /* Process release data. */
        foreach($releaseIssue as $issueID)
        {
            $objectID  = zget($issueList[$issueID], 'BID',   '');
            $issueType = zget($issueList[$issueID], 'BType', '');
            if(!$issueType || ($issueType != 'zstory' && $issueType != 'zbug')) continue;

            if($issueType == 'zstory')
            {
                $this->dao->dbh($this->dbh)->update(TABLE_RELEASE)->set("stories = IF(stories IS NOT NULL, CONCAT(stories, ',$objectID'), '{$objectID}')")->where('id')->eq($releaseID)->exec();
            }
            else
            {
                $this->dao->dbh($this->dbh)->update(TABLE_RELEASE)->set("bugs = IF(bugs IS NOT NULL, CONCAT(bugs, ',$objectID'), '{$objectID}')")->where('id')->eq($releaseID)->exec();
            }
        }

        return true;
    }

    /**
     * Jira内置字段新增成工作流字段。
     * Create buildin field.
     *
     * @param  string    $module
     * @param  array     $resolutions
     * @param  array     $priList
     * @param  false     $buildin
     * @access protected
     * @return bool
     */
    protected function createBuildinField(string $module, array $resolutions, array $priList, $buildin = false): bool
    {
        foreach($this->lang->convert->jira->buildinFields as $fieldCode => $buildinField)
        {
            if(isset($buildinField['buildin']) && $buildinField['buildin'] !== $buildin) continue;

            $options = array('code' => array(), 'name' => array());
            if($fieldCode == 'pri')
            {
                foreach($priList as $pri)
                {
                    $options['code'][] = $pri->id;
                    $options['name'][] = $pri->pname;
                }
            }
            if($fieldCode == 'resolution')
            {
                foreach($resolutions as $resolution)
                {
                    $options['code'][] = $resolution->id;
                    $options['name'][] = $resolution->pname;
                }
            }

            $field = new stdclass();
            $field->name          = $buildinField['name'];
            $field->field         = $fieldCode;
            $field->control       = $buildinField['control'];
            $field->type          = $buildinField['type'];
            $field->length        = $buildinField['length'];
            $field->integerDigits = in_array($field->type, $this->config->workflowfield->numberTypes) ? '10' : '';
            $field->decimalDigits = in_array($field->type, $this->config->workflowfield->numberTypes) ? '2'  : '';;
            $field->expression    = '';
            $field->optionType    = $buildinField['optionType'];
            $field->sql           = '';
            $field->options       = $options;
            $field->default       = '';
            $field->placeholder   = '';
            $field->module        = $module;
            $field->group         = '0';
            $field->createdBy     = $this->app->user->account;
            $field->createdDate   = helper::now();

            $this->workflowfield->create($module, $field, null, true);
        }

        return true;
    }

    /**
     * 为工作流生成默认布局。
     * Create default layout.
     *
     * @param  array     $fields
     * @param  object    $flow
     * @param  int       $group
     * @access protected
     * @return bool
     */
    protected function createDefaultLayout(array $fields, object $flow, int $group = 0): bool
    {
        foreach(array('browse', 'create', 'edit', 'view') as $action)
        {
            foreach($fields as $field)
            {
                if($field->field == 'deleted') continue;
                if(($action == 'create' || $action == 'edit') && in_array($field->field, array('id', 'parent', 'createdBy', 'createdDate', 'editedBy', 'editedDate', 'assignedBy', 'assignedDate', 'deleted'))) continue;

                $layout = new stdclass();
                $layout->module = $flow->module;
                $layout->action = $action;
                $layout->field  = $field->field;
                $layout->vision = $this->config->vision;
                $layout->group  = $group;
                if($action == 'view') $layout->position = 'info';
                $this->dao->insert(TABLE_WORKFLOWLAYOUT)->data($layout)->autoCheck()->exec();
            }
            if($action == 'browse' && !empty($fields))
            {
                $layout->field = 'actions';
                $hasExists = $this->dao->select('*')->from(TABLE_WORKFLOWLAYOUT)->where('module')->eq($layout->module)->andWhere('action')->eq($layout->action)->andWhere('group')->eq($layout->group)->andWhere('field')->eq('actions')->fetch();
                if(!$hasExists) $this->dao->insert(TABLE_WORKFLOWLAYOUT)->data($layout)->autoCheck()->exec();
            }
        }
        return true;
    }

    /**
     * Jira对象新增成工作流。
     * Create workflow.
     *
     * @param  array     $relations
     * @param  array     $jiraActions
     * @param  array     $jiraResolutions
     * @param  array     $jiraPriList
     * @access protected
     * @return array
     */
    protected function createWorkflow(array $relations, array $jiraActions, array $jiraResolutions, array $jiraPriList): array
    {
        if($this->config->edition == 'open') return $relations;
        $this->loadModel('workflow');
        $this->loadModel('workflowfield');

        $issueTypeList   = $this->getJiraData($this->session->jiraMethod, 'issuetype');
        $statusList      = $this->getJiraData($this->session->jiraMethod, 'status');
        $flowRelation    = $this->dao->dbh($this->dbh)->select('*')->from(JIRA_TMPRELATION)->where('AType')->eq('jissuetype')->andWhere('BType')->eq('zworkflow')->fetchAll('AID');
        foreach($relations['zentaoObject'] as $jiraCode => $zentaoCode)
        {
            if($zentaoCode != 'add_custom') continue;

            /* Jira事务类型新增成禅道工作流。 */
            if(empty($flowRelation[$jiraCode]))
            {
                $flow = new stdclass();
                $flow->name        = substr(zget($issueTypeList[$jiraCode], 'pname', ''), 0, 30);
                $flow->icon        = 'flow';
                $flow->module      = 'jira' . $jiraCode;
                $flow->approval    = 'disabled';
                $flow->type        = 'flow';
                $flow->table       = 'zt_flow_' . $flow->module;
                $flow->createdBy   = $this->app->user->account;
                $flow->createdDate = helper::now();
                $flow->vision      = $this->config->vision;
                $flow->role        = 'custom';
                $flow->status      = 'wait';
                $flow->belong      = 'project';
                $this->dao->insert(TABLE_WORKFLOW)->data($flow)->autoCheck()->exec();

                $flowID = $this->dao->lastInsertId();
                $this->loadModel('action')->create('workflow', $flowID, 'Created');

                $flow = $this->workflow->getByID($flowID);
                $this->workflow->createFields($flow);
                $this->workflow->createActions($flow);
                $this->workflow->createLabels($flow);

                $this->createBuildinField($flow->module, $jiraResolutions, $jiraPriList);

                $fields = $this->workflowfield->getList($flow->module);
                $this->createDefaultLayout($fields, $flow);

                /* 将工作流发布到指定位置。 */
                $_POST = array();
                $_POST['navigator']      = 'secondary';
                $_POST['app']            = 'scrum';
                $_POST['positionModule'] = 'settings';
                $_POST['position']       = 'before';
                $_POST['syncRelease']    = 'all';
                $_POST['module']         = $flow->module;

                if(isset($this->lang->scrum->menu->other['dropMenu']))
                {
                    $_POST['positionModule'] = 'other';
                    $_POST['dropMenu']       = 'issue';
                }
                $this->workflow->release($flowID);

                $this->createTmpRelation('jissuetype', $jiraCode, 'zworkflow', $flow->module);
                $relations['zentaoObject'][$jiraCode] = $flow->module;
            }
            else
            {
                $relations['zentaoObject'][$jiraCode] = $flowRelation[$jiraCode]->BID;
            }

            $customField = $this->getJiraCustomField($jiraCode, $relations);
            foreach($customField as $id => $field)
            {
                $relations["jiraField{$jiraCode}"][] = $id;
                $relations["zentaoField{$jiraCode}"][$id] = 'add_field';
            }
            foreach($statusList as $id => $status)
            {
                $relations["jiraStatus{$jiraCode}"][] = $id;
                $relations["zentaoStatus{$jiraCode}"][$id] = 'add_flow_status';
            }
            foreach($jiraActions['actions'] as $id => $action)
            {
                if(!empty($action['name']) && $action['name'] == 'Create') continue;
                $relations["jiraAction{$jiraCode}"][] = $id;
                $relations["zentaoAction{$jiraCode}"][$id] = 'add_action';
            }
        }

        return $relations;
    }

    /**
     * Jira字段新增成工作流字段。
     * Create workflow field.
     *
     * @param  array     $relations
     * @param  array     $fields
     * @param  array     $fieldOptions
     * @param  array     $jiraResolutions
     * @param  array     $jiraPriList
     * @access protected
     * @return array
     */
    protected function createWorkflowField(array $relations, array $fields, array $fieldOptions, array $jiraResolutions, array $jiraPriList): array
    {
        if($this->config->edition == 'open') return $relations;

        $this->loadModel('workflowfield');
        $jiraFieldControl = $this->config->convert->jiraFieldControl;
        $fieldRelation    = $this->dao->dbh($this->dbh)->select('*')->from(JIRA_TMPRELATION)->where('AType')->eq('jcustomfield')->fetchGroup('extra', 'AID');

        /* 创建内置字段。 */
        foreach($relations['zentaoObject'] as $jiraCode => $module) $this->createBuildinField($module, $jiraResolutions, $jiraPriList, true);

        /* 创建自定义字段。 */
        foreach($relations as $stepKey => $fieldList)
        {
            if(strpos($stepKey, 'zentaoField') === false) continue;
            $jiraCode = str_replace('zentaoField', '', $stepKey);
            $module   = $relations['zentaoObject'][$jiraCode];

            foreach($fieldList as $jiraField => $zentaoField)
            {
                if($zentaoField != 'add_field') continue;

                if(empty($fieldRelation[$module][$jiraField]))
                {
                    $controlCode = !empty($fields[$jiraField]->customfieldtypekey) ? $fields[$jiraField]->customfieldtypekey : 'com.atlassian.jira.plugin.system.customfieldtypes:textfield';

                    $options = array('code' => array(), 'name' => array());
                    foreach($fieldOptions as $optionID => $fieldOption)
                    {
                        if($fieldOption->customfield != $jiraField) continue;
                        $options['code'][] = $optionID;
                        $options['name'][] = $fieldOption->customvalue;
                    }

                    if(empty($jiraFieldControl[$controlCode])) $controlCode = !empty($options['code']) ? 'com.atlassian.jira.plugin.system.customfieldtypes:select' : 'com.atlassian.jira.plugin.system.customfieldtypes:textfield';

                    $field = new stdclass();
                    $field->name          = substr(zget($fields[$jiraField], 'cfname', ''), 0, 60);
                    $field->field         = 'jirafield' . str_replace(range(0, 9), range('a', 'z'), uniqid());
                    $field->control       = $jiraFieldControl[$controlCode]['control'];
                    $field->type          = $jiraFieldControl[$controlCode]['type'];
                    $field->length        = $jiraFieldControl[$controlCode]['length'];
                    $field->integerDigits = in_array($field->type, $this->config->workflowfield->numberTypes) ? '10' : '';
                    $field->decimalDigits = in_array($field->type, $this->config->workflowfield->numberTypes) ? '2'  : '';;
                    $field->expression    = '';
                    $field->optionType    = strpos($controlCode, 'userpicker') === false ? 'custom' : 'user';
                    $field->sql           = '';
                    $field->options       = $options;
                    $field->default       = '';
                    $field->placeholder   = '';
                    $field->module        = $module;
                    $field->group         = '0';
                    $field->createdBy     = $this->app->user->account;
                    $field->createdDate   = helper::now();

                    if(strpos(',select,multi-select,radio,checkbox,', ",$field->control,") !== false && empty($options['code'])) $field->control = 'text';
                    $result = $this->workflowfield->create($module, $field, null, true);

                    $relation = $this->createTmpRelation('jcustomfield', $jiraField, 'zworkflowfield', $field->field, $field->module);

                    $fieldRelation[$module][$jiraField] = $relation;
                    $relations[$stepKey][$jiraField] = $field->field;
                }
                else
                {
                    $relations[$stepKey][$jiraField] = $fieldRelation[$module][$jiraField]->BID;
                }

            }
        }

        return $relations;
    }

    /**
     * Jira状态新增成工作流状态。
     * Create workflow status.
     *
     * @param  array     $relations
     * @access protected
     * @return array
     */
    protected function createWorkflowStatus(array $relations): array
    {
        if($this->config->edition == 'open') return $relations;

        $this->loadModel('custom');
        $currentLang    = $this->app->getClientLang();
        $jiraStatusList = $this->getJiraData($this->session->jiraMethod, 'status');
        foreach($relations as $stepKey => $statusList)
        {
            if(strpos($stepKey, 'zentaoStatus') === false) continue;
            $jiraCode = str_replace('zentaoStatus', '', $stepKey);
            $module   = $relations['zentaoObject'][$jiraCode];
            foreach($statusList as $jiraStatus => $zentaoStatus)
            {
                if($zentaoStatus != 'add_case_status' && $zentaoStatus != 'add_flow_status') continue;

                $zentaoCode = $jiraStatus;
                if($zentaoStatus == 'add_case_status')
                {
                    $items = $this->loadModel('setting')->getItem("module=testcase&section=statusList");
                    if(empty($items))
                    {
                        $this->loadModel('testcase');
                        $items = $this->lang->testcase->statusList;
                        foreach($items as $key => $value) $this->custom->setItem("{$currentLang}.testcase.statusList.$key.1", $value);
                    }
                    $this->custom->setItem("{$currentLang}.testcase.statusList.$zentaoCode.0", zget($jiraStatusList[$jiraStatus], 'pname', ''));
                }
                elseif($zentaoStatus == 'add_flow_status')
                {
                    $fieldOptions = $this->dao->select('options')->from(TABLE_WORKFLOWFIELD)->where('module')->eq($module)->andWhere('field')->eq('status')->fetch('options');
                    $fieldOptions = json_decode($fieldOptions);
                    if(!$fieldOptions) $fieldOptions = new stdclass();
                    $fieldOptions->{$zentaoCode} = zget($jiraStatusList[$jiraStatus], 'pname', '');
                    $this->dao->update(TABLE_WORKFLOWFIELD)->set('options')->eq(json_encode($fieldOptions))->where('module')->eq($module)->andWhere('field')->eq('status')->exec();
                }
                $relations[$stepKey][$jiraStatus] = $zentaoCode;
            }
        }
        return $relations;
    }

    /**
     * 导入jira状态变更为工作流扩展动作。
     * Process workflow hooks.
     *
     * @param  array     $jiraAction
     * @param  array     $jiraStepList
     * @param  string    $module
     * @access protected
     * @return array
     */
    protected function processWorkflowHooks(array $jiraAction, array $jiraStepList, string $module): array
    {
        if(empty($jiraAction['results']['unconditional-result']['@attributes']['step'])) return array();

        $hooks    = array();
        $jiraStep = $jiraAction['results']['unconditional-result']['@attributes']['step'];

        $field = new stdclass();
        $field->field     = 'status';
        $field->paramType = 'custom';
        $field->param     = $jiraStepList[$jiraStep];

        $where = new stdclass();
        $where->field           = 'id';
        $where->logicalOperator = 'and';
        $where->operator        = 'equal';
        $where->paramType       = 'record';
        $where->param           = 'id';

        $hook = new stdclass();
        $hook->action        = 'update';
        $hook->table         = $module;
        $hook->conditionType = 'data';
        $hook->sqlResult     = 'empty';
        $hook->message       = '';
        $hook->comment       = '';
        $hook->fields        = array($field);
        $hook->conditions    = array();
        $hook->wheres        = array($where);
        $hook->sqlVars       = array();
        $hook->formVars      = array();
        $hook->recordVars    = array($where->param);
        $hook->formulaVars   = array();

        list($sql, $error) = $this->workflowhook->check($hook);
        $hook->sql = $sql;

        $hooks[] = $hook;
        return $hooks;
    }

    /**
     * Jira动作新增成工作流动作。
     * Create workflow action.
     *
     * @param  array     $relations
     * @param  array     $jiraActions
     * @access protected
     * @return array
     */
    protected function createWorkflowAction(array $relations, array $jiraActions): array
    {
        if($this->config->edition == 'open') return $relations;

        $this->loadModel('workflowaction');
        $this->loadModel('workflowhook');
        $currentLang        = $this->app->getClientLang();
        $flowActionRelation = $this->dao->dbh($this->dbh)->select('*')->from(JIRA_TMPRELATION)->where('AType')->eq('jflowaction')->fetchGroup('extra', 'AID');
        foreach($relations as $stepKey => $actionList)
        {
            if(strpos($stepKey, 'zentaoAction') === false) continue;
            $jiraCode = str_replace('zentaoAction', '', $stepKey);
            $module   = $relations['zentaoObject'][$jiraCode];
            foreach($actionList as $jiraAction => $zentaoAction)
            {
                if($zentaoAction != 'add_action') continue;

                if(empty($flowActionRelation[$module][$jiraAction]))
                {
                    $hooks = $this->processWorkflowHooks($jiraActions['actions'][$jiraAction], $jiraActions['steps'], $module);

                    $action = new stdclass();
                    $action->name          = $jiraActions['actions'][$jiraAction]['name'];
                    $action->action        = $module . 'action' . $jiraAction;
                    $action->type          = 'single';
                    $action->batchMode     = 'same';
                    $action->open          = 'none';
                    $action->position      = 'browseandview';
                    $action->show          = 'dropdownlist';
                    $action->desc          = '';
                    $action->module        = $module;
                    $action->method        = 'operate';
                    $action->extensionType = 'override';
                    $action->conditions    = '[]';
                    $action->hooks         = helper::jsonEncode($hooks);
                    $action->status        = 'disable';
                    $action->createdBy     = $this->app->user->account;
                    $action->createdDate   = helper::now();
                    $action->vision        = $this->config->vision;
                    $action->group         = '0';
                    $action->order         = '999';

                    $this->dao->insert(TABLE_WORKFLOWACTION)->data($action)->exec();

                    $actionID = $this->dao->lastInsertId();
                    $action->id = $actionID;
                    $this->workflowaction->createFields($action);

                    $relation = $this->createTmpRelation('jflowaction', $jiraAction, 'zworkflowaction', $action->action, $module);

                    $flowActionRelation[$module][$jiraAction] = $relation;
                    $relations[$stepKey][$jiraAction] = $action->action;

                }
                else
                {
                    $relations[$stepKey][$jiraAction] = $flowActionRelation[$module][$jiraAction]->BID;
                }
            }
        }
        return $relations;
    }

    /**
     * 为项目创建工作流分组.
     * Create group.
     *
     * @param  string    $type
     * @param  string    $name
     * @param  array     $objectList
     * @param  int       $jiraProjectID
     * @param  int       $zentaoProjectID
     * @param  array     $productRelations
     * @param  array     $projectFieldList
     * @access protected
     * @return bool
     */
    protected function createGroup(string $type, string $name, array $objectList, int $jiraProjectID, int $zentaoProjectID, array $productRelations, array $projectFieldList): bool
    {
        $group = new stdclass();
        $group->name            = substr($name, 0, 80) . $this->lang->workflowgroup->template;
        $group->projectModel    = $type == 'project' ? 'scrum'   : '';
        $group->projectType     = $type == 'project' ? 'product' : '';
        $group->type            = $type;
        $group->status          = 'normal';
        $group->createdBy       = $this->app->user->account;
        $group->createdDate     = helper::now();
        $group->disabledModules = '';
        $group->exclusive       = '1';
        $groupID = $this->workflowgroup->create($group);
        $this->action->create('workflowgroup', $groupID, 'created');

        $group->id  = $groupID;
        $flows      = $this->workflowgroup->getFlows($group);
        $actionList = array('browse', 'create', 'edit', 'view');
        foreach($flows as $flow)
        {
            if(in_array($flow->module, $objectList))
            {
                $this->workflowgroup->setExclusive($flow->id, $groupID);

                $this->dao->dbh($this->dbh)->update(TABLE_WORKFLOWACTION)->set('extensionType')->eq('extend')->where('module')->eq($flow->module)->andWhere('`group`')->eq($groupID)->andWhere('action')->in($actionList)->exec();

                /* 内置字段添加到布局。 */
                $fields = array();
                foreach($this->lang->convert->jira->buildinFields as $fieldCode => $buildinField)
                {
                    if(isset($buildinField['buildin']) && $buildinField['buildin'] == false) continue;
                    $field = new stdclass();
                    $field->field = $fieldCode;
                    $fields[$fieldCode] = $field;
                }
                if($flow->buildin) $this->createDefaultLayout($fields, $flow, $groupID);

                /* 扩展字段添加到布局。 */
                if(empty($projectFieldList[$jiraProjectID][$flow->module])) continue;
                $this->createDefaultLayout($projectFieldList[$jiraProjectID][$flow->module], $flow, $groupID);
            }
        }
        if($type == 'product') $this->dao->dbh($this->dbh)->update(TABLE_PRODUCT)->set('workflowGroup')->eq($groupID)->where('id')->eq($productRelations[$jiraProjectID])->exec();
        if($type == 'project') $this->dao->dbh($this->dbh)->update(TABLE_PROJECT)->set('workflowGroup')->eq($groupID)->where('id')->eq($zentaoProjectID)->exec();
        $this->createTmpRelation('jproject', $jiraProjectID, 'zworkflowgroup', $groupID);

        return true;
    }

    /**
     * 创建工作部流程模版。
     * Create workflow group.
     *
     * @param  array     $relations
     * @param  array     $projectRelations
     * @param  array     $productRelations
     * @access protected
     * @return array
     */
    protected function createWorkflowGroup(array $relations, array $projectRelations, array $productRelations): array
    {
        if($this->config->edition == 'open') return $relations;

        $this->loadModel('action');
        $this->loadModel('workflowgroup');
        $groupRelations       = $this->dao->dbh($this->dbh)->select('AID,BID')->from(JIRA_TMPRELATION)->where('AType')->eq('jproject')->andWhere('BType')->eq('zworkflowgroup')->fetchPairs();
        $projectList          = $this->getJiraData($this->session->jiraMethod, 'project');
        $projectIssueTypeList = $this->getIssueTypeList($relations);
        $projectFieldList     = $this->getJiraFieldGroupByProject($relations);
        foreach($projectRelations as $jiraProjectID => $zentaoProjectID)
        {
            if(!empty($groupRelations[$jiraProjectID])) continue;
            if(empty($projectList[$jiraProjectID]))     continue;

            $project       = $projectList[$jiraProjectID];
            $issueTypeList = !empty($projectIssueTypeList[$jiraProjectID]) ? $projectIssueTypeList[$jiraProjectID] : array();

            $this->createGroup('project', $project->pname, $issueTypeList, (int)$jiraProjectID, (int)$zentaoProjectID, $productRelations, $projectFieldList);
            $this->createGroup('product', $project->pname, $issueTypeList, (int)$jiraProjectID, (int)$zentaoProjectID, $productRelations, $projectFieldList);
        }

        return $relations;
    }

    /**
     * Jira解决方案新增成禅道关闭原因或解决方案。
     * Create zentao resolution.
     *
     * @param  array     $relations
     * @access protected
     * @return array
     */
    protected function createResolution(array $relations): array
    {
        $this->loadModel('custom');
        $currentLang     = $this->app->getClientLang();
        $jiraResolutions = $this->getJiraData($this->session->jiraMethod, 'resolution');
        foreach($relations as $stepKey => $resolutionList)
        {
            if(strpos($stepKey, 'zentaoResolution') === false && strpos($stepKey, 'zentaoReason') === false) continue;
            $jiraCode = strpos($stepKey, 'zentaoResolution') !== false ? str_replace('zentaoResolution', '', $stepKey) : str_replace('zentaoReason', '', $stepKey);
            $module   = $relations['zentaoObject'][$jiraCode];
            foreach($resolutionList as $jiraResolution => $zentaoResolution)
            {
                if($zentaoResolution != 'add_resolution' && $zentaoResolution != 'add_reason') continue;

                $zentaoCode = 'jira' . $jiraResolution;
                $section    = $zentaoResolution == 'add_resolution' ? 'resolutionList' : 'reasonList';

                $items = $this->loadModel('setting')->getItem("module={$module}&section={$section}");
                if(empty($items))
                {
                    $items = $this->lang->story->reasonList;
                    if($module == 'bug')  $items = $this->lang->bug->resolutionList;
                    if($module == 'task') $items = $this->lang->task->reasonList;
                    foreach($items as $key => $value) $this->custom->setItem("{$currentLang}.{$module}.{$section}.{$key}.1", $value);
                }
                $this->custom->setItem("{$currentLang}.{$module}.{$section}.{$zentaoCode}.0", zget($jiraResolutions[$jiraResolution], 'pname', ''));

                $relations[$stepKey][$jiraResolution] = $zentaoCode;
            }
        }
        return $relations;
    }

    /**
     * 更新子需求。
     * Update sub story.
     *
     * @param  array     $storyLink
     * @param  array     $issueList
     * @access protected
     * @return bool
     */
    protected function updateSubStory(array $storyLink, array $issueList): bool
    {
        foreach($storyLink as $source => $dest)
        {
            if(!isset($issueList[$source])) continue;

            $parentType = zget($issueList[$source], 'BType', '');
            if(!in_array($parentType, array('zepic', 'zrequirement', 'zstory'))) continue;

            $parentID = zget($issueList[$source], 'BID', '');
            $this->dao->dbh($this->dbh)->update(TABLE_STORY)
                ->set('parent')->eq('0')
                ->set('isParent')->eq('1')
                ->set('grade')->eq('1')
                ->set('root')->eq($parentID)
                ->set('path')->eq(",{$parentID},")
                ->where('id')->eq($parentID)
                ->exec();

            foreach($dest as $childID)
            {
                if(!isset($issueList[$childID])) continue;

                $childType = zget($issueList[$childID], 'BType', '');
                if(!in_array($childType, array('zepic', 'zrequirement', 'zstory'))) continue;

                $childrenID = zget($issueList[$childID], 'BID', '');
                $this->dao->dbh($this->dbh)->update(TABLE_STORY)
                    ->set('parent')->eq($parentID)
                    ->set('isParent')->eq('0')
                    ->set('grade')->eq('2')
                    ->set('root')->eq($parentID)
                    ->set('path')->eq(",{$parentID},{$childrenID},")
                    ->where('id')->eq($childrenID)
                    ->exec();
            }
        }

        return true;
    }

    /**
     * 更新子任务。
     * Update sub task.
     *
     * @param  array     $taskLink
     * @param  array     $issueList
     * @access protected
     * @return bool
     */
    protected function updateSubTask(array $taskLink, array $issueList): bool
    {
        foreach($taskLink as $source => $dest)
        {
            if(!isset($issueList[$source])) continue;
            if(zget($issueList[$source], 'BType', '') != 'ztask') continue;

            $parentID = zget($issueList[$source], 'BID', '');
            $this->dao->dbh($this->dbh)->update(TABLE_TASK)
                ->set('parent')->eq('0')
                ->set('isParent')->eq('1')
                ->set('path')->eq(",{$parentID},")
                ->where('id')->eq($parentID)
                ->exec();

            foreach($dest as $childID)
            {
                if(!isset($issueList[$childID])) continue;
                if(zget($issueList[$childID], 'BType', '') != 'ztask') continue;

                $childrenID = zget($issueList[$childID], 'BID', '');
                $this->dao->dbh($this->dbh)->update(TABLE_TASK)
                    ->set('parent')->eq($parentID)
                    ->set('isParent')->eq('0')
                    ->set('path')->eq(",{$parentID},{$childrenID},")
                    ->where('id')->eq($childrenID)
                    ->exec();
            }
        }

        return true;
    }

    /**
     * 更新重复的需求和bug。
     * Update duplicate story and bug.
     *
     * @param  array     $duplicateLink
     * @param  array     $issueList
     * @access protected
     * @return bool
     */
    protected function updateDuplicateStoryAndBug(array $duplicateLink, array $issueList): bool
    {
        foreach($duplicateLink as $source => $dest)
        {
            $sourceObjectType = zget($issueList[$source], 'BType', '');
            $sourceObjectID   = zget($issueList[$source], 'BID',   '');
            $destObjectType   = zget($issueList[$dest],   'BType', '');
            $destObjectID     = zget($issueList[$dest],   'BID',   '');

            if(empty($sourceObjectType) || empty($destObjectType)) continue;
            if($sourceObjectType != 'zstory' && $sourceObjectType != 'zbug') continue;
            if($sourceObjectType != $destObjectType) continue;

            if($sourceObjectType == 'zstory')
            {
                $this->dao->dbh($this->dbh)->update(TABLE_STORY)->set('duplicateStory')->eq($destObjectID)->where('id')->eq($sourceObjectID)->exec();
            }
            else
            {
                $this->dao->dbh($this->dbh)->update(TABLE_BUG)->set('duplicateBug')->eq($destObjectID)->where('id')->eq($sourceObjectID)->exec();
            }
        }

        return true;
    }

    /**
     * 更新相关对象数据。
     * Update relates object.
     *
     * @param  array     $relatesLink
     * @param  array     $issueList
     * @access protected
     * @return bool
     */
    protected function updateRelatesObject(array $relatesLink, array $issueList): bool
    {
        foreach($relatesLink as $source => $dest)
        {
            $sourceObjectType = zget($issueList[$source], 'BType', '');
            $sourceObjectID   = zget($issueList[$source], 'BID',   '');
            $destObjectType   = zget($issueList[$dest],   'BType', '');
            $destObjectID     = zget($issueList[$dest],   'BID',   '');

            if(empty($sourceObjectType) || empty($destObjectType)) continue;

            if($sourceObjectType == 'ztask' && $destObjectType == 'zstory')
            {
                $this->dao->dbh($this->dbh)->update(TABLE_TASK)->set('story')->eq($destObjectID)->where('id')->eq($sourceObjectID)->exec();
            }
            elseif($sourceObjectType == 'zstory' && $destObjectType == 'ztask')
            {
                $this->dao->dbh($this->dbh)->update(TABLE_TASK)->set('story')->eq($sourceObjectID)->where('id')->eq($destObjectID)->exec();
            }
            elseif($sourceObjectType == 'zstory' && $destObjectType == 'zbug')
            {
                $this->dao->dbh($this->dbh)->update(TABLE_BUG)->set('story')->eq($sourceObjectID)->set('storyVersion')->eq(1)->where('id')->eq($destObjectID)->exec();
            }
            elseif($sourceObjectType == 'zbug' && $destObjectType == 'zstory')
            {
                $this->dao->dbh($this->dbh)->update(TABLE_BUG)->set('story')->eq($destObjectID)->set('storyVersion')->eq(1)->where('id')->eq($sourceObjectID)->exec();
            }
            elseif($sourceObjectType == 'zstory' && $destObjectType == 'zstory')
            {
                $this->dao->dbh($this->dbh)->update(TABLE_STORY)->set("linkStories=concat(linkStories, ',{$destObjectID}')")->where('id')->eq($sourceObjectID)->exec();
            }
            elseif($sourceObjectType == 'zbug' && $destObjectType == 'zbug')
            {
                $this->dao->dbh($this->dbh)->update(TABLE_BUG)->set("relatedBug=concat(relatedBug, ',{$destObjectID}')")->where('id')->eq($sourceObjectID)->exec();
            }
        }

        return true;
    }
}
