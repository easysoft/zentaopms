<?php
class jira
{
    /**
     * Jira接口的域名。
     * Jira api domain.
     *
     * @var string
     * @access public
     */
    public $jiraDomain;

    /**
     * Jira接口的用户名。
     * Jira api account.
     *
     * @var string
     * @access public
     */
    public $jiraAccount;

    /**
     * Jira接口的Token。
     * Jira api token.
     *
     * @var string
     * @access public
     */
    public $jiraToken;

    /**
     * 构造方法。
     * The construct function.
     *
     * @param  string $jiraDomain
     * @param  string $jiraAccount
     * @param  string $jiraToken
     * @access public
     * @return void
     */
    public function __construct(string $jiraDomain, string $jiraAccount, string $jiraToken)
    {
        $this->jiraDomain  = $jiraDomain;
        $this->jiraAccount = $jiraAccount;
        $this->jiraToken   = $jiraToken;
    }

    /**
     * 获取Jira中所有项目。
     *
     * @param string $url      Jira地址
     * @param string $account  Jira账号
     * @param string $password Jira密码
     * @return array
     */
    public function getProjects($startAt = 0): array
    {
        if($startAt > 0) return array(); // 项目没有分页，第二次直接返回空数组

        $url      = $this->jiraDomain . '/rest/api/3/project';
        $account  = $this->jiraAccount;
        $password = $this->jiraToken;

        $authHeader = base64_encode($account . ':' . $password);
        $header     = array('Authorization: Basic ' . $authHeader);
        $result     = common::http($url, null, array(), $header, 'data', 'GET');

        $result = json_decode($result, true);
        if(!$result) return array();

        $projects = array();
        foreach($result as $project)
        {
            $project['versions']      = $this->getBuilds($project['id']);
            $projects[$project['id']] = $project;
        }

        return $projects;
    }

    /**
     * 获取Jira中所有的版本。
     *
     * @param  int $startAt    开始位置
     * @param  int $maxResults 最大返回数量
     * @return array
     */
    public function getBuilds($projectID = 0, $startAt = 0, $maxResults = 50): array
    {
        $url      = $this->jiraDomain . '/rest/api/3/project/' . $projectID . '/versions?startAt=' . $startAt . '&maxResults=' . $maxResults;
        $account  = $this->jiraAccount;
        $password = $this->jiraToken;

        $authHeader = base64_encode($account . ':' . $password);
        $header     = array('Authorization: Basic ' . $authHeader);
        $result     = common::http($url, null, array(), $header, 'data', 'GET');

        $versions = json_decode($result, true);
        if(!$versions) return array();

        if(count($versions) == $maxResults) $versions = arrayUnion($versions, $this->getBuilds($startAt + $maxResults, $maxResults));

        return $versions;
    }

    /**
     * 获取Jira中所有的Issue链接。
     *
     * @param  int $startAt    开始位置
     * @param  int $maxResults 最大返回数量
     * @return array
     */
    public function getIssueLinks($startAt = 0, $maxResults = 50): array
    {
        $url      = $this->jiraDomain . '/rest/api/2/issueLink?startAt=' . $startAt . '&maxResults=' . $maxResults;
        $account  = $this->jiraAccount;
        $password = $this->jiraToken;

        $authHeader = base64_encode($account . ':' . $password);
        $header     = array('Authorization: Basic ' . $authHeader);
        $result     = common::http($url, null, array(), $header, 'data', 'GET');

        $issueLinks = json_decode($result, true);
        if(!$issueLinks) return array();

        if(count($issueLinks) == $maxResults) $issueLinks = arrayUnion($issueLinks, $this->getIssueLinks($startAt + $maxResults, $maxResults));

        return $issueLinks;
    }

    /**
     * 获取Jira中所有的Issue类型。
     *
     * @return array
     */
    public function getIssueTypes(): array
    {
        $url      = $this->jiraDomain . '/rest/api/2/issuetype/';
        $account  = $this->jiraAccount;
        $password = $this->jiraToken;

        $authHeader = base64_encode($account . ':' . $password);
        $header     = array('Authorization: Basic ' . $authHeader);
        $result     = common::http($url, null, array(), $header, 'data', 'GET');

        $issueTypes = json_decode($result, true);
        if(!$issueTypes) return array();

        $typeList = array();
        foreach($issueTypes as $issueType) $typeList[$issueType['id']] = $issueType;

        return $typeList;
    }

    /**
     * 获取Jira中所有的Issue链接类型。
     *
     * @return array
     */
    public function getIssueLinkTypes()
    {
        $url      = $this->jiraDomain . '/rest/api/2/issueLinkType';
        $account  = $this->jiraAccount;
        $password = $this->jiraToken;

        $authHeader = base64_encode($account . ':' . $password);
        $header     = array('Authorization: Basic ' . $authHeader);
        $result     = common::http($url, null, array(), $header, 'data', 'GET');

        $linkTypes = json_decode($result, true);
        if(!$linkTypes) return array();

        return $linkTypes['issueLinkTypes'];
    }

    /**
     * 获取Jira中的所有issue。
     *
     * @param  int    $lastID
     * @param  int    $maxResults
     * @return string
     */
    public function getIssues($lastID = 0, $maxResults = 5000)
    {
        if(!$lastID) unset($_SESSION['nextPageToken']);
        if(!empty($_SESSION['nextPageToken']) && $_SESSION['nextPageToken'] == 'isLast')
        {
            unset($_SESSION['nextPageToken']);
            return array();
        }

        $url      = $this->jiraDomain . '/rest/api/3/search/jql';
        $account  = $this->jiraAccount;
        $password = $this->jiraToken;

        $maxResults    = $maxResults ?: 5000;
        $nextPageToken = !empty($_SESSION['nextPageToken']) ? $_SESSION['nextPageToken'] : '';
        $authHeader    = base64_encode($account . ':' . $password);
        $header        = array('Authorization: Basic ' . $authHeader);
        $jql           = 'created<=' . date('Y-m-d', strtotime('+1 day'));
        $url          .= '?jql=' . urlencode($jql) . "&fields=*all&maxResults=$maxResults&nextPageToken=$nextPageToken&expand=renderedFields,changelog";
        $result        = common::http($url, null, array(), $header, 'data', 'GET');

        $result = json_decode($result, true);
        if(!$result || empty($result['issues'])) return array();

        $issueList = array();
        foreach($result['issues'] as $issue)
        {
            if(!empty($issue['fields']))
            {
                foreach($issue['fields'] as $field => $value) $issue[$field] = $value;
            }
            if(!empty($issue['priority']['id']))                $issue['priority']    = $issue['priority']['id'];
            if(!empty($issue['project']['id']))                 $issue['project']     = $issue['project']['id'];
            if(!empty($issue['status']['id']))                  $issue['status']      = $issue['status']['id'];
            if(!empty($issue['creator']['accountId']))          $issue['creator']     = $issue['creator']['accountId'];
            if(!empty($issue['issuetype']['id']))               $issue['type']        = $issue['issuetype']['id'];
            if(!empty($issue['assignee']['accountId']))         $issue['assignee']    = $issue['assignee']['accountId'];
            if(!empty($issue['resolution']['id']))              $issue['resolution']  = $issue['resolution']['id'];
            if(!empty($issue['renderedFields']['description'])) $issue['description'] = $issue['renderedFields']['description'];

            if(!empty($issue['changelog']['histories']))
            {
                $changeGroups = array();
                $changeItems  = array();
                foreach($issue['changelog']['histories'] as $history)
                {
                    $changeGroup = array();
                    $changeGroup['id']      = $history['id'];
                    $changeGroup['issue']   = $issue['id'];
                    $changeGroup['author']  = $history['author']['accountId'];
                    $changeGroup['created'] = date('Y-m-d H:i:s', strtotime($history['created']));
                    $changeGroups[$changeGroup['id']] = $changeGroup;

                    foreach($history['items'] as $index => $item)
                    {
                        $changeItem = array();
                        $changeItem['id']        = $changeGroup['id'] . '_' . $index;
                        $changeItem['group']     = $changeGroup['id'];
                        $changeItem['fieldtype'] = $item['fieldtype'];
                        $changeItem['field']     = $item['field'];
                        $changeItem['oldvalue']  = $item['from'];
                        $changeItem['oldstring'] = $item['fromString'];
                        $changeItem['newvalue']  = $item['to'];
                        $changeItem['newstring'] = $item['toString'];
                        $changeItems[$changeItem['id']] = $changeItem;
                    }
                }
                $issue['changeGroups'] = $changeGroups;
                $issue['changeItems']  = $changeItems;
            }

            if(!empty($issue['comment']['comments']))
            {
                $comments = array();
                foreach($issue['comment']['comments'] as $index => $comment)
                {
                    $commentItem = array();
                    $commentItem['id']      = $comment['id'];
                    $commentItem['issue']   = $issue['id'];
                    $commentItem['body']    = $issue['renderedFields']['comment']['comments'][$index]['body'];
                    $commentItem['author']  = $comment['author']['accountId'];
                    $commentItem['created'] = date('Y-m-d H:i:s', strtotime($comment['created']));
                    $comments[$commentItem['id']] = $commentItem;
                }
                $issue['comments'] = $comments;
            }

            if(!empty($issue['worklog']['worklogs']))
            {
                $worklogs = array();
                foreach($issue['worklog']['worklogs'] as $index => $work)
                {
                    $worklog = array();
                    $worklog['id']         = $work['id'];
                    $worklog['issue']      = $issue['id'];
                    $worklog['body']       = !empty($issue['renderedFields']['worklog']['worklogs'][$index]['comment']) ? $issue['renderedFields']['worklog']['worklogs'][$index]['comment'] : '';
                    $worklog['author']     = $work['author']['accountId'];
                    $worklog['timeworked'] = $work['timeSpentSeconds'];
                    $worklog['created']    = date('Y-m-d H:i:s', strtotime($work['created']));
                    $worklogs[$worklog['id']] = $worklog;
                }
                $issue['worklogs'] = $worklogs;
            }

            if(!empty($issue['attachment']))
            {
                $files = array();
                foreach($issue['attachment'] as $index => $attachment)
                {
                    $file = array();
                    $file['issue']    = $issue['id'];
                    $file['id']       = $attachment['id'];
                    $file['filename'] = $attachment['filename'];
                    $file['mimetype'] = $attachment['mimeType'];
                    $file['filesize'] = $attachment['size'];
                    $file['author']   = $attachment['author']['accountId'];
                    $file['created']  = date('Y-m-d H:i:s', strtotime($attachment['created']));
                    $file['content']  = $attachment['content'];
                    $files[$file['id']] = $file;
                }
                $issue['files'] = $files;
            }

            if(!empty($issue['issuelinks']))
            {
                $links = array();
                foreach($issue['issuelinks'] as $index => $issueLink)
                {
                    $link = array();
                    $link['id']          = $issueLink['id'];
                    $link['linktype']    = $issueLink['type']['id'];
                    $link['source']      = $issue['id'];
                    $link['destination'] = !empty($issueLink['inwardIssue']['id']) ? $issueLink['inwardIssue']['id'] : '';
                    $links[$link['id']] = $link;
                }
                $issue['links'] = $links;
            }

            if(!empty($issue['subtasks']))
            {
                $links = !empty($issue['links']) ? $issue['links'] : array();
                foreach($issue['subtasks'] as $index => $subtask)
                {
                    $link = array();
                    $link['id']          = 'subtask' . $subtask['id'];
                    $link['linktype']    = 'jiraSubTask';
                    $link['source']      = $issue['id'];
                    $link['destination'] = $subtask['id'];
                    $links[$link['id']] = $link;
                }
                $issue['links'] = $links;
            }

            $issue['created'] = date('Y-m-d H:i:s', strtotime($issue['created']));
            $issueList[$issue['id']] = $issue;
        }

        if(empty($result['isLast']) && !empty($result['nextPageToken'])) $_SESSION['nextPageToken'] = $result['nextPageToken'];
        if(!empty($result['isLast'])) $_SESSION['nextPageToken'] = 'isLast';

        return $issueList;
    }

    /**
     * 获取Jira中的用户。
     *
     * @param  int $startAt    开始位置
     * @param  int $maxResults 最大返回数量
     * @return string
     */
    public function getUsers($startAt = 0, $maxResults = 50)
    {
        $url      = $this->jiraDomain . '/rest/api/2/user/search?query=.&startAt=' . $startAt . '&maxResults=' . $maxResults;
        $account  = $this->jiraAccount;
        $password = $this->jiraToken;

        $authHeader = base64_encode($account . ':' . $password);
        $header     = array('Authorization: Basic ' . $authHeader);
        $result     = common::http($url, null, array(), $header, 'data', 'GET');
        $result     = json_decode($result, true);

        $users = array();
        foreach($result as $index => $user)
        {
            $user['id'] = $index;
            $user['lowerUserName']    = $user['accountId'];
            $user['lowerDisplayName'] = $user['displayName'];
            $user['createdDate']      = '';

            $users[$index] = $user;
        }

        if(count($result) == $maxResults) $users = arrayUnion($users, $this->getUsers($startAt + $maxResults, $maxResults));
        return $users;
    }

    /**
     * 从jira接口下载文件内容。
     * Download jira File.
     *
     * @param  string $fileURL
     * @access public
     * @return void
     */
    public function downloadFile(string $fileURL)
    {
        $account    = $this->jiraAccount;
        $password   = $this->jiraToken;
        $authHeader = base64_encode($account . ':' . $password);
        $header     = array('Authorization: Basic ' . $authHeader);
        return common::http($fileURL, null, array(), $header, 'data', 'GET');
    }

    /**
     * 获取Jira中指定项目的Board。
     *
     * @param  int    $projectID 项目ID
     * @return string
     */
    public function getBoardId($projectID)
    {
        $url      = $this->jiraDomain . '/rest/agile/1.0/board?projectKeyOrId=' . $projectID;
        $account  = $this->jiraAccount;
        $password = $this->jiraToken;

        $authHeader = base64_encode($account . ':' . $password);
        $header     = array('Authorization: Basic ' . $authHeader);
        $result     = common::http($url, null, array(), $header, 'data', 'GET');

        return json_decode($result, true);
    }

    /**
     * 获取Jira中的解决方式。
     *
     * @return array
     */
    public function getResolutions()
    {
        $url      = $this->jiraDomain . '/rest/api/2/resolution';
        $account  = $this->jiraAccount;
        $password = $this->jiraToken;

        $authHeader = base64_encode($account . ':' . $password);
        $header     = array('Authorization: Basic ' . $authHeader);
        $result     = common::http($url, null, array(), $header, 'data', 'GET');

        return json_decode($result, true);
    }

    /**
     * 获取Jira中所有的优先级。
     *
     * @return array
     */
    public function getPriority()
    {
        $url      = $this->jiraDomain . '/rest/api/2/priority';
        $account  = $this->jiraAccount;
        $password = $this->jiraToken;

        $authHeader = base64_encode($account . ':' . $password);
        $header     = array('Authorization: Basic ' . $authHeader);
        $result     = common::http($url, null, array(), $header, 'data', 'GET');

        return json_decode($result, true);
    }

    /**
     * 获取Jira中所有的状态。
     *
     * @return array
     */
    public function getStatus()
    {
        $url      = $this->jiraDomain . '/rest/api/2/status';
        $account  = $this->jiraAccount;
        $password = $this->jiraToken;

        $authHeader = base64_encode($account . ':' . $password);
        $header     = array('Authorization: Basic ' . $authHeader);
        $result     = common::http($url, null, array(), $header, 'data', 'GET');

        $result = json_decode($result, true);
        if(!$result) return array();

        $statusList = array();
        foreach($result as $status) $statusList[$status['id']] = $status;

        return $statusList;
    }

    /**
     * 获取Jira中的自定义字段。
     *
     * @return array
     */
    public function getCustomFields()
    {
        $url      = $this->jiraDomain . '/rest/api/3/issue/createmeta?expand=projects.issuetypes.fields';
        $account  = $this->jiraAccount;
        $password = $this->jiraToken;

        $authHeader = base64_encode($account . ':' . $password);
        $header     = array('Authorization: Basic ' . $authHeader);
        $result     = common::http($url, null, array(), $header, 'data', 'GET');

        $result = json_decode($result, true);
        if(!$result['projects']) return array();

        $customFields = array();
        foreach($result['projects'] as $project)
        {
            if(empty($project['issuetypes'])) continue;
            foreach($project['issuetypes'] as $issuetype)
            {
                foreach($issuetype['fields'] as $key => $field)
                {
                    if(strpos($key, 'customfield_') === false) continue;

                    $customField = array();
                    $customField['id']                     = str_replace('customfield_', '', $key);
                    $customField['name']                   = $field['name'];
                    $customField['description']            = '';
                    $customField['customfieldtypekey']     = $field['schema']['custom'];
                    $customField['customfieldsearcherkey'] = '';
                    $customField['issueTypeIds']           = '';
                    $customFields[$issuetype['id']][$customField['id']] = $customField;
                }
            }
        }

        return $customFields;
    }

    /**
     * 获取Jira中的自定义字段选项。
     *
     * @return array
     */
    public function getCustomFieldOption()
    {
        $url      = $this->jiraDomain . '/rest/api/3/issue/createmeta?expand=projects.issuetypes.fields';
        $account  = $this->jiraAccount;
        $password = $this->jiraToken;

        $authHeader = base64_encode($account . ':' . $password);
        $header     = array('Authorization: Basic ' . $authHeader);
        $result     = common::http($url, null, array(), $header, 'data', 'GET');

        $result = json_decode($result, true);
        if(!$result['projects']) return array();

        $customFieldOptions = array();
        foreach($result['projects'] as $project)
        {
            if(empty($project['issuetypes'])) continue;
            foreach($project['issuetypes'] as $issuetype)
            {
                foreach($issuetype['fields'] as $key => $field)
                {
                    if(strpos($key, 'customfield_') === false || empty($field['allowedValues']))  continue;
                    foreach($field['allowedValues'] as $allowedValue)
                    {
                        $customFieldOption = array();
                        $customFieldOption['id']                = str_replace('customfield_', '', $key);
                        $customFieldOption['customfield']       = str_replace('customfield_', '', $key);
                        $customFieldOption['customfieldconfig'] = $field['schema']['custom'];
                        $customFieldOption['value']             = $allowedValue['value'];
                        $customFieldOption['disabled']          = false;
                        $customFieldOptions[$allowedValue['id']] = $customFieldOption;
                    }
                }
            }
        }

        return $customFieldOptions;
    }
}
