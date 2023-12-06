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
}
