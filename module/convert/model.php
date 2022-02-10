<?php
/**
 * The model file of convert module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id: model.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php
class convertModel extends model
{
    /**
     * Connect to db.
     * 
     * @access public
     * @return void
     */
    public function connectDB($dbName = '')
    {
        $dsn = "mysql:host={$this->config->db->host}; port={$this->config->db->port};dbname={$dbName}";
        try 
        {
            $dbh = new PDO($dsn, $this->config->db->user, $this->config->db->password);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->exec("SET NAMES {$this->config->db->encoding}");
            $this->sourceDBH = $dbh;
            return $dbh;
        }
        catch (PDOException $exception)
        {
            return $exception->getMessage();
        }
    }
 
    /**
     * Check database exits or not.
     * 
     * @access public
     * @return bool
     */
    public function dbExists($dbName = '')
    {
        $sql = "SHOW DATABASES like '{$dbName}'";
        return $this->dbh->query($sql)->fetch();
    }

    /**
     * Check table exits or not.
     * 
     * @access public
     * @return bool
     */
    public function tableExists($table)
    {
        $sql = "SHOW tables like '$table'";
        return $this->dbh->query($sql)->fetch();
    }

    /**
     * Save the max id of every table. Thus when we convert again, when can delete id larger then the saved max id.
     * 
     * @access public
     * @return void
     */
    public function saveState()
    {
        /* Get user defined tables. */
        $constants     = get_defined_constants(true);
        $userConstants = $constants['user'];

        /* These tables needn't save. */
        unset($userConstants['TABLE_BURN']);
        unset($userConstants['TABLE_GROUPPRIV']);
        unset($userConstants['TABLE_PROJECTPRODUCT']);
        unset($userConstants['TABLE_PROJECTSTORY']);
        unset($userConstants['TABLE_STORYSPEC']);
        unset($userConstants['TABLE_TEAM']);
        unset($userConstants['TABLE_USERGROUP']);
        unset($userConstants['TABLE_STORYSTAGE']);
        unset($userConstants['TABLE_SEARCHDICT']);

        /* Get max id of every table. */
        foreach($userConstants as $key => $value)
        {
            if(strpos($key, 'TABLE') === false) continue;
            if($key == 'TABLE_COMPANY') continue;
            $state[$value] = (int)$this->dao->select('MAX(id) AS id')->from($value)->fetch('id');
        }
        $this->session->set('state', $state);
    }

    /**
     * Import jira from db.
     * 
     * @param  string $type 
     * @param  int    $lastID 
     * @access public
     * @return void
     */
    public function importJiraFromDB($type = '', $lastID = 0, $createTable = false)
    {
        if($createTable) $this->createTmpTable4Jira();

        $this->connectDB($this->session->jiraDB);

        $limit = 1000;
        $nextObject = false;
        if(empty($type)) $type = key($this->lang->convert->jira->objectList);

        foreach($this->lang->convert->jira->objectList as $module => $moduleName)
        {
            if($module != $type and !$nextObject) continue;
            if($module == $type) $nextObject = true;

            while(true)
            {
                $dataList = $this->getJiraData($module, $lastID, $limit);

                if(empty($dataList))
                {
                    $lastID = 0;
                    break;
                }

                if($module == 'user')    $this->importJiraUser($dataList);
                if($module == 'project') $this->importJiraProject($dataList);
                if($module == 'issue')   $this->importJiraIssue($dataList);

                return array('type' => $module, 'count' => count($dataList), 'lastID' => max(array_keys($dataList)));
            }
        }

        $this->dbh->exec("DROP TABLE" . JIRA_TMPRELATION);
        return array('finished' => true);
    }

    /**
     * Get jira data.
     * 
     * @param  int    $module 
     * @access public
     * @return void
     */
    public function getJiraData($module = '', $lastID = 0, $limit = 0)
    {
        $dataList = array();
        if($module == 'user')
        {
            $dataList = $this->dao->dbh($this->sourceDBH)->select('t1.`ID`, t1.`lower_user_name` as account, t1.`lower_display_name` as realname, t1.`lower_email_address` as email, t1.created_date as `join`, t2.user_key as userCode')->from(JIRA_USERINFO)->alias('t1')
                ->leftJoin(JIRA_USER)->alias('t2')->on('t1.`lower_user_name` = t2.`lower_user_name`')
                ->where(1)
                ->beginIF($lastID)->andWhere('t1.ID')->gt($lastID)->fi()
                ->orderBy('t1.ID asc')->limit($limit)
                ->fetchAll('ID');
        }
        elseif($module == 'project')
        {
            $dataList = $this->dao->dbh($this->sourceDBH)->select('*')->from(JIRA_PROJECT)
                ->where(1)
                ->beginIF($lastID)->andWhere('ID')->gt($lastID)->fi()
                ->orderBy('ID asc')->limit($limit)
                ->fetchAll('ID');
        }
        elseif($module == 'issue')
        {
            $dataList = $this->dao->dbh($this->sourceDBH)->select('*')->from(JIRA_ISSUE)
                ->where(1)
                ->beginIF($lastID)->andWhere('ID')->gt($lastID)->fi()
                ->orderBy('ID asc')->limit($limit)
                ->fetchAll('ID');
        }

        return $dataList;
    }

    public function importJiraUser($dataList)
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
            $user->gender   = 'f';
            $user->type     = 'inside';
            $user->join     = $data->join;

            $this->dao->dbh($this->dbh)->insert(TABLE_USER)->data($user, 'group')->exec();

            if(!dao::isError())
            {   
                $data = new stdclass();
                $data->account = $user->account;
                $data->group   = $user->group;

                $this->dao->dbh($this->dbh)->replace(TABLE_USERGROUP)->set('account')->eq($user->account)->set('`group`')->eq($user->group)->exec();
            }
        }
    }

    public function importJiraProject($dataList)
    {
        global $app;
        $app->loadConfig('execution');
        $app->loadLang('doc');
        $now = helper::now();

        foreach($dataList as $id => $data)
        {
            $projectRelation   = array();
            $executionRelation = array();
            $productRelation   = array();

            /* Create project. */
            $project = new stdclass();
            $project->name   = $data->pname;
            $project->code   = $data->pkey;
            $project->desc   = $data->DESCRIPTION;
            $project->status = 'wait';
            $project->type   = 'project';
            $project->model  = 'scrum';
            $project->grade  = 1;
            $project->acl    = 'open';
            $project->end    = date('Y-m-d', time() + 24 * 3600);

            $project->PM            = $this->getJiraAccount($data->LEAD);
            $project->openedBy      = $this->getJiraAccount($data->LEAD);
            $project->openedDate    = $now;
            $project->openedVersion = $this->config->version;
            $this->dao->dbh($this->dbh)->insert(TABLE_PROJECT)->data($project)->exec();

            $projectID = $this->dao->dbh($this->dbh)->lastInsertID();
            $this->dao->dbh($this->dbh)->update(TABLE_PROJECT)->set('`order`')->eq($projectID * 5)->set('path')->eq(",$projectID,")->where('id')->eq($projectID)->exec();

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

            /* Create execution. */
            $execution = new stdclass();
            $execution->name    = $data->pname;
            $execution->code    = $data->pkey;
            $execution->desc    = $data->DESCRIPTION;
            $execution->status  = 'wait';
            $execution->project = $projectID;
            $execution->parent  = $projectID;
            $execution->grade   = 1;
            $execution->type    = 'sprint';
            $execution->acl     = 'open';
            $execution->end     = date('Y-m-d', time() + 24 * 3600);

            $execution->PM            = $project->PM;
            $execution->openedBy      = $project->openedBy;
            $execution->openedDate    = $now;
            $execution->openedVersion = $this->config->version;
            $this->dao->dbh($this->dbh)->insert(TABLE_PROJECT)->data($execution)->exec();

            $executionID = $this->dao->dbh($this->dbh)->lastInsertID();
            $this->dao->dbh($this->dbh)->update(TABLE_PROJECT)->set('`order`')->eq($executionID * 5)->set('path')->eq(",$projectID,$executionID,")->where('id')->eq($executionID)->exec();

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
            $lib->project   = $projectID;
            $lib->execution = $executionID;
            $lib->name      = $this->lang->doclib->main['project'];
            $lib->type      = 'execution';
            $lib->main      = '1';
            $lib->acl       = 'default';
            $this->dao->dbh($this->dbh)->insert(TABLE_DOCLIB)->data($lib)->exec();

            /* Create product. */
            $product = new stdclass();
            $product->name   = $project->name;
            $product->code   = $project->code;
            $product->type   = 'normal';
            $product->status = 'normal';
            $product->acl    = 'open';

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
            $this->dao->dbh($this->dbh)->replace(TABLE_PROJECTPRODUCT)->set('project')->eq($projectID)->set('product')->eq($productID)->exec();
            $this->dao->dbh($this->dbh)->replace(TABLE_PROJECTPRODUCT)->set('project')->eq($executionID)->set('product')->eq($productID)->exec();

            $projectRelation['AType'] = 'jproject'; 
            $projectRelation['BType'] = 'zproject'; 
            $projectRelation['AID']   = $id; 
            $projectRelation['BID']   = $projectID; 

            $this->dao->dbh($this->dbh)->insert(JIRA_TMPRELATION)->data($projectRelation)->exec();

            $executionRelation['AType'] = 'jproject'; 
            $executionRelation['BType'] = 'zexecution'; 
            $executionRelation['AID']   = $id; 
            $executionRelation['BID']   = $executionID; 

            $this->dao->dbh($this->dbh)->insert(JIRA_TMPRELATION)->data($executionRelation)->exec();
        }
    }

    public function importJiraIssue($dataList)
    {
        $relations = $this->session->jiraRelation;

        $this->app->loadLang('story');
        $this->app->loadLang('task');
        $this->app->loadLang('bug');

        $projectRelation = $this->dao->dbh($this->dbh)->select('AID,BID')->from(JIRA_TMPRELATION)
            ->where('AType')->eq('jproject')
            ->andWhere('BType')->eq('zproject')
            ->fetchPairs();

        $projectProduct = $this->dao->dbh($this->dbh)->select('project,product')->from(TABLE_PROJECTPRODUCT)
            ->where('project')->in(array_values($projectRelation))
            ->fetchPairs();

        $projectExecution = $this->dao->dbh($this->dbh)->select('project,id')->from(TABLE_PROJECT)
            ->where('project')->in(array_values($projectRelation))
            ->fetchPairs();

        $issueTypeList = array();
        foreach($relations['jiraObject'] as $id => $jiraCode)
        {
            $issueTypeList[$jiraCode] = $relations['zentaoObject'][$id];
        }

        $reasonList     = array();
        $resolutionList = array();
        foreach($relations['jiraResolution'] as $id => $jiraCode)
        {
            if(!empty($relations['zentaoReason'][$id]))     $reasonList[$jiraCode]     = $relations['zentaoReason'][$id];
            if(!empty($relations['zentaoResolution'][$id])) $resolutionList[$jiraCode] = $relations['zentaoResolution'][$id];
        }

        $projectKeys = $this->dao->dbh($this->sourceDBH)->select('ID, ORIGINALKEY as oldKey, pkey as newKey')->from(JIRA_PROJECT)->fetchAll('ID');

        $issueObjectType = array();
        foreach($dataList as $id => $data)
        {
            $issueType    = isset($issueTypeList[$data->issuetype]) ? $issueTypeList[$data->issuetype] : 'task';
            $issueID      = $data->ID;
            $issueProject = $data->PROJECT;
            
            if(!isset($projectRelation[$issueProject])) continue;

            $projectID   = $projectRelation[$issueProject];
            $productID   = $projectProduct[$projectID];
            $executionID = $projectExecution[$projectID];

            if($issueType == 'requirement' or $issueType == 'story')
            {
                $story             = new stdclass();
                $story->product    = $productID;
                $story->title      = $data->SUMMARY;
                $story->type       = $issueType;
                $story->pri        = $data->PRIORITY;
                $story->version    = 1;
                $story->stage      = $this->convertStage($data->issuestatus);
                $story->status     = $this->convertStatus('story', $data->issuestatus);
                $story->openedBy   = $this->getJiraAccount($data->CREATOR);
                $story->openedDate = substr($data->CREATED, 0, 19);
                $story->assignedTo = $this->getJiraAccount($data->ASSIGNEE);

                if($data->RESOLUTION)
                {
                    $story->closedReason = zget($reasonList, $data->RESOLUTION, '');
                    if($story->closedReason and !isset($this->lang->story->reasonList[$story->closedReason])) $story->closedReason = 'done';
                }

                $this->dao->dbh($this->dbh)->insert(TABLE_STORY)->data($story)->exec();

                if(!dao::isError())
                {
                    $storyID  = $this->dao->dbh($this->dbh)->lastInsertID();

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

                    $storyRelation['AType'] = 'jstory';
                    $storyRelation['BType'] = 'zstory';
                    $storyRelation['AID']   = $issueID;
                    $storyRelation['BID']   = $storyID;
                    $this->dao->dbh($this->dbh)->insert(JIRA_TMPRELATION)->data($storyRelation)->exec();

                    $issueRelation['AType'] = 'jissueid';
                    $issueRelation['BType'] = 'zissuetype';
                    $issueRelation['AID']   = $issueID;
                    $issueRelation['extra'] = 'story';
                    $this->dao->dbh($this->dbh)->insert(JIRA_TMPRELATION)->data($issueRelation)->exec();
                }
            }
            elseif($issueType == 'task' or $issueType == 'subTask')
            {
                $task = new stdclass();
                $task->project    = $projectID;
                $task->execution  = $executionID;
                $task->name       = $data->SUMMARY;
                $task->type       = 'devel';
                $task->pri        = $data->PRIORITY;
                $task->status     = $this->convertStatus('task', $data->issuestatus);
                $task->desc       = $data->DESCRIPTION;
                $task->openedBy   = $this->getJiraAccount($data->CREATOR);
                $task->openedDate = substr($data->CREATED, 0, 19);
                $task->assignedTo = $this->getJiraAccount($data->ASSIGNEE);
                if($data->RESOLUTION)
                {
                    $task->closedReason = zget($reasonList, $data->RESOLUTION, '');
                    if($task->closedReason and !isset($this->lang->task->reasonList[$task->closedReason])) $task->closedReason = 'cancel';
                }

                $this->dao->dbh($this->dbh)->insert(TABLE_TASK)->data($task)->exec();
                $taskID = $this->dao->dbh($this->dbh)->lastInsertID();

                $taskRelation['AType'] = 'jtask';
                $taskRelation['BType'] = 'ztask';
                $taskRelation['AID']   = $issueID;
                $taskRelation['BID']   = $taskID;
                $this->dao->dbh($this->dbh)->insert(JIRA_TMPRELATION)->data($taskRelation)->exec();

                $issueRelation['AType'] = 'jissueid';
                $issueRelation['BType'] = 'zissuetype';
                $issueRelation['AID']   = $issueID;
                $issueRelation['extra'] = 'task';
                $this->dao->dbh($this->dbh)->insert(JIRA_TMPRELATION)->data($issueRelation)->exec();
            }
            elseif($issueType == 'bug')
            {
                $bug = new stdclass();
                $bug->product     = $productID;
                $bug->project     = $projectID;
                $bug->title       = $data->SUMMARY;
                $bug->pri         = $data->PRIORITY;
                $bug->status      = $this->convertStatus('bug', $data->issuestatus);
                $bug->steps       = $data->DESCRIPTION;
                $bug->openedBy    = $this->getJiraAccount($data->CREATOR); 
                $bug->openedDate  = substr($data->CREATED, 0, 19);
                $bug->openedBuild = 'trunk';
                $bug->assignedTo  = $this->getJiraAccount($data->ASSIGNEE);

                if($data->RESOLUTION)
                {
                    $bug->resolution = zget($resolutionList, $data->RESOLUTION, '');
                    if($bug->resolution and !isset($this->lang->bug->resolutionList[$bug->resolution])) $bug->resolution = 'fixed';
                }

                $this->dao->dbh($this->dbh)->insert(TABLE_BUG)->data($bug)->exec();
                $bugID = $this->dao->dbh($this->dbh)->lastInsertID();

                $bugRelation['AType'] = 'jbug';
                $bugRelation['BType'] = 'zbug';
                $bugRelation['AID']   = $issueID;
                $bugRelation['BID']   = $bugID;
                $this->dao->dbh($this->dbh)->insert(JIRA_TMPRELATION)->data($bugRelation)->exec();

                $issueRelation['AType'] = 'jissueid';
                $issueRelation['BType'] = 'zissuetype';
                $issueRelation['AID']   = $issueID;
                $issueRelation['extra'] = 'bug';
                $this->dao->dbh($this->dbh)->insert(JIRA_TMPRELATION)->data($issueRelation)->exec();
            }

            $oldKey   = zget($projectKeys[$issueProject], 'oldKey', '');
            $newKey   = zget($projectKeys[$issueProject], 'newKey', '');
            $issueKey = $oldKey ? $oldKey . '-' . $data->issuenum : $newKey . '-' . $data->issuenum;

            $fileRelation['AType'] = 'jissueid';
            $fileRelation['BType'] = 'jfilepath';
            $fileRelation['AID']   = $issueID;
            $fileRelation['extra'] = "{$oldKey}/10000/{$issueKey}/";

            $this->dao->dbh($this->dbh)->insert(JIRA_TMPRELATION)->data($fileRelation)->exec();
        }
    }

    public function convertStatus($objectType, $jiraStatus)
    {
        $relations = $this->session->jiraRelation;

        $status = 'active';
        if($objectType == 'task') $status = 'wait';

        $arrayKey = "{$objectType}Status";
        foreach($relations['jiraStatus'] as $id => $jiraCode)
        {
            if($jiraCode == $jiraStatus)
            {
                if(!empty($relations[$arrayKey][$id])) $status = $relations[$arrayKey][$id];
            }
        }

        return $status;
    }

    public function convertStage($jiraStatus)
    {
        $stage = 'wait';
        $relations = $this->session->jiraRelation;

        foreach($relations['jiraStatus'] as $id => $jiraCode)
        {
            if($jiraCode == $jiraStatus)
            {
                if(!empty($relations['storyStage'][$id])) $stage = $relations['storyStage'][$id];
            }
        }

        return $stage;
    }

    public function getJiraAccount($userKey)
    {
        if(strpos($userKey, 'JIRAUSER') === false) return $userKey;

        return $this->dao->dbh($this->sourceDBH)->select('lower_user_name')->from(JIRA_USER)->where('user_key')->eq($userKey)->fetch('lower_user_name'); 
    }

    public function createTmpTable4Jira()
    {
$sql = <<<EOT
CREATE TABLE `jiratmprelation`(
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `AType` char(30) NOT NULL,
  `AID` mediumint(8) NOT NULL,
  `BType` char(30) NOT NULL,
  `BID` mediumint(8) NOT NULL,
  `extra` char(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `relation` (`AType`,`BType`,`AID`,`BID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
EOT;

        try  
        {    
            $this->dbh->exec($sql);
        }    
        catch(Exception $e){}
    }
}
