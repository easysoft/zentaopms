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
}
