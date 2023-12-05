<?php
/**
 * The model file of convert module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
     * @param  string $dbName
     * @access public
     * @return object|string
     */
    public function connectDB(string $dbName = ''): object|string
    {
        try
        {
            $params = clone $this->config->db;
            $params->name = $dbName;

            $dbh = new dbh($params);
            $dbh->exec("SET NAMES {$params->encoding}");
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
     * @param  string $dbName
     * @access public
     * @return object|false
     */
    public function dbExists(string $dbName = ''): object|false
    {
        if(!$this->checkDBName($dbName)) return false;

        return $this->dbh->execute('SHOW DATABASES like ?', array($dbName))->fetch();
    }

    /**
     * Check table exits or not.
     *
     * @param  string  $table
     * @access public
     * @return bool
     */
    public function tableExists($table)
    {
        $sql = "SHOW tables like '$table'";
        return $this->dbh->query($sql)->fetch();
    }

    /**
     * Check table of jira databases exits or not.
     *
     * @param  string  $dbName
     * @param  string  $table
     * @access public
     * @return bool
     */
    public function tableExistsOfJira($dbName, $table)
    {
        $this->connectDB($dbName);
        $sql = "SHOW tables like '$table'";
        return $this->dao->dbh($this->sourceDBH)->query($sql)->fetch();
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
     * Get jira data from db.
     *
     * @param  string $module
     * @param  int    $lastID
     * @param  int    $limit
     * @access public
     * @return void
     */
    public function getJiraDataFromDB($module = '', $lastID = 0, $limit = 0)
    {
        $dataList = array();
        if($module == 'user')
        {
            $dataList = $this->dao->dbh($this->sourceDBH)->select('t1.`ID`, t1.`lower_user_name` as account, t1.`lower_display_name` as realname, t1.`lower_email_address` as email, t1.created_date as `join`, t2.user_key as userCode')->from(JIRA_USERINFO)->alias('t1')
                ->leftJoin(JIRA_USER)->alias('t2')->on('t1.`lower_user_name` = t2.`lower_user_name`')
                ->where('1 = 1')
                ->beginIF($lastID)->andWhere('t1.ID')->gt($lastID)->fi()
                ->orderBy('t1.ID asc')->limit($limit)
                ->fetchAll('ID');
        }
        elseif($module == 'project')
        {
            $dataList = $this->dao->dbh($this->sourceDBH)->select('*')->from(JIRA_PROJECT)
                ->where('1 = 1')
                ->beginIF($lastID)->andWhere('ID')->gt($lastID)->fi()
                ->orderBy('ID asc')->limit($limit)
                ->fetchAll('ID');
        }
        elseif($module == 'issue')
        {
            $dataList = $this->dao->dbh($this->sourceDBH)->select('*')->from(JIRA_ISSUE)
                ->where('1 = 1')
                ->beginIF($lastID)->andWhere('ID')->gt($lastID)->fi()
                ->orderBy('ID asc')->limit($limit)
                ->fetchAll('ID');
        }
        elseif($module == 'build')
        {
            $dataList = $this->dao->dbh($this->sourceDBH)->select('*')->from(JIRA_BUILD)
                ->where('1 = 1')
                ->beginIF($lastID)->andWhere('ID')->gt($lastID)->fi()
                ->orderBy('ID asc')->limit($limit)
                ->fetchAll('ID');
        }
        elseif($module == 'issuelink')
        {
            $dataList = $this->dao->dbh($this->sourceDBH)->select('*')->from(JIRA_ISSUELINK)
                ->where('1 = 1')
                ->beginIF($lastID)->andWhere('ID')->gt($lastID)->fi()
                ->orderBy('ID asc')->limit($limit)
                ->fetchAll('ID');
        }
        elseif($module == 'action')
        {
            $dataList = $this->dao->dbh($this->sourceDBH)->select('*')->from(JIRA_ACTION)
                ->where('1 = 1')
                ->beginIF($lastID)->andWhere('ID')->gt($lastID)->fi()
                ->orderBy('ID asc')->limit($limit)
                ->fetchAll('ID');
        }
        elseif($module == 'file')
        {
            $dataList = $this->dao->dbh($this->sourceDBH)->select('*')->from(JIRA_FILE)
                ->where('1 = 1')
                ->beginIF($lastID)->andWhere('ID')->gt($lastID)->fi()
                ->orderBy('ID asc')->limit($limit)
                ->fetchAll('ID');
        }

        return $dataList;
    }

    /**
     * Get jira data from file.
     *
     * @param  string $module
     * @param  int    $lastID
     * @param  int    $limit
     * @access public
     * @return void
     */
    public function getJiraDataFromFile($module, $lastID = 0, $limit = 0)
    {
        $fileName = $module;
        if($module == 'build') $fileName = 'version';
        if($module == 'file')  $fileName = 'fileattachment';

        $filePath = $this->app->getTmpRoot() . 'jirafile/' . $fileName . '.xml';
        if(!file_exists($filePath)) return array();

        $xmlContent = file_get_contents($filePath);
        $xmlContent = preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $xmlContent);
        $parsedXML  = simplexml_load_string($xmlContent, 'SimpleXMLElement', LIBXML_NOCDATA);

        $dataList  = array();
        $parsedXML = $this->object2Array($parsedXML);
        foreach($parsedXML as $key => $xmlArray)
        {
            if(strtolower($key) != strtolower($fileName)) continue;
            foreach($xmlArray as $key => $attributes)
            {
                $desc    = isset($attributes['description']) ? $attributes['description'] : '';
                $summary = isset($attributes['summary']) ? $attributes['summary'] : '';
                $body    = isset($attributes['body']) ? $attributes['body'] : '';

                if(is_numeric($key))
                {
                    foreach($attributes as $value)
                    {
                        if(!is_array($value)) continue;
                        if(!empty($desc))    $value['description'] = $desc;
                        if(!empty($summary)) $value['summary']     = $summary;
                        if(!empty($body))    $value['body']        = $body;
                        $dataList[$value['id']] = $value;
                    }
                }
                else
                {
                    $dataList[$attributes['id']] = $attributes;
                }
            }
        }

        if($limit)
        {
            $dataList = array_slice($dataList, $lastID, $limit, true);
            if(empty($dataList)) return array();
        }

        foreach($dataList as $key => $data)
        {
            if($module == 'user')
            {
                $user = new stdclass();
                $user->account  = $data['lowerUserName'];
                $user->realname = $data['lowerDisplayName'];
                $user->email    = $data['emailAddress'];
                $user->join     = $data['createdDate'];

                $dataList[$key] = $user;
            }
            elseif($module == 'project')
            {
                $project = new stdclass();
                $project->ID          = $data['id'];
                $project->pname       = $data['name'];
                $project->pkey        = $data['key'];
                $project->ORIGINALKEY = $data['originalkey'];
                $project->DESCRIPTION = isset($data['description']) ? $data['description'] : '';
                $project->LEAD        = $data['lead'];

                $dataList[$key] = $project;
            }
            elseif($module == 'issue')
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

                $dataList[$key] = $issue;
            }
            elseif($module == 'build')
            {
                $build = new stdclass();
                $build->ID          = $data['id'];
                $build->PROJECT     = $data['project'];
                $build->vname       = $data['name'];
                $build->RELEASEDATE = isset($data['releasedate']) ? $data['releasedate'] : '';
                $build->DESCRIPTION = isset($data['description']) ? $data['description'] : '';

                $dataList[$key] = $build;
            }
            elseif($module == 'issuelink')
            {
                $issueLink = new stdclass();
                $issueLink->LINKTYPE    = $data['linktype'];
                $issueLink->SOURCE      = $data['source'];
                $issueLink->DESTINATION = $data['destination'];

                $dataList[$key] = $issueLink;
            }
            elseif($module == 'action')
            {
                $action = new stdclass();
                $action->issueid    = $data['issue'];
                $action->actionbody = $data['body'];
                $action->AUTHOR     = $data['author'];
                $action->CREATED    = $data['created'];

                $dataList[$key] = $action;
            }
            elseif($module == 'file')
            {
                $file = new stdclass();
                $file->issueid  = $data['issue'];
                $file->ID       = $data['id'];
                $file->FILENAME = $data['filename'];
                $file->MIMETYPE = $data['mimetype'];
                $file->FILESIZE = $data['filesize'];
                $file->CREATED  = $data['created'];
                $file->AUTHOR   = $data['author'];

                $dataList[$key] = $file;
            }
        }

        return $dataList;
    }

    /**
     * Get version group from jira file.
     *
     * @access public
     * @return void
     */
    public function getVersionGroup()
    {
        $xmlContent = file_get_contents($this->app->getTmpRoot() . 'jirafile/nodeassociation.xml');
        $xmlContent = preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $xmlContent);
        $parsedXML  = simplexml_load_string($xmlContent, 'SimpleXMLElement', LIBXML_NOCDATA);

        $dataList  = array();
        $parsedXML = $this->object2Array($parsedXML);
        foreach($parsedXML as $key => $xmlArray)
        {
            if(strtolower($key) != 'nodeassociation') continue;
            foreach($xmlArray as $key => $attributes)
            {
                foreach($attributes as $value)
                {
                    if(!is_array($value)) continue;
                    if($value['sinkNodeEntity'] != 'Version') continue;
                    $dataList[$value['sinkNodeId']][] = $value['sinkNodeId'];
                    $dataList[$value['sinkNodeId']][] = $value['sourceNodeId'];
                }
            }
        }

        return $dataList;
    }

    /**
     * Convert object to array.
     *
     * @param  object $parsedXML
     * @access public
     * @return void
     */
    public function object2Array($parsedXML)
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
                $dataList = $this->getJiraDataFromDB($module, $lastID, $limit);

                if(empty($dataList))
                {
                    $lastID = 0;
                    break;
                }

                if($module == 'user')      $this->importJiraUser($dataList);
                if($module == 'project')   $this->importJiraProject($dataList);
                if($module == 'issue')     $this->importJiraIssue($dataList);
                if($module == 'build')     $this->importJiraBuild($dataList);
                if($module == 'issuelink') $this->importJiraIssueLink($dataList);
                if($module == 'action')    $this->importJiraAction($dataList);
                if($module == 'file')      $this->importJiraFile($dataList);

                return array('type' => $module, 'count' => count($dataList), 'lastID' => max(array_keys($dataList)));
            }
        }

        $this->afterExec();
        return array('finished' => true);
    }

    /**
     * Import jira from file.
     *
     * @param  string   $type
     * @param  int      $lastID
     * @param  bool     $createTable
     * @access public
     * @return void
     */
    public function importJiraFromFile($type = '', $lastID = 0, $createTable = false)
    {
        if($createTable) $this->createTmpTable4Jira();

        $limit = 1000;
        $nextObject = false;
        if(empty($type)) $type = key($this->lang->convert->jira->objectList);

        foreach($this->lang->convert->jira->objectList as $module => $moduleName)
        {
            if($module != $type and !$nextObject) continue;
            if($module == $type) $nextObject = true;

            while(true)
            {
                $dataList = $this->getJiraDataFromFile($module, $lastID, $limit);

                if(empty($dataList))
                {
                    $lastID = 0;
                    break;
                }

                if($module == 'user')      $this->importJiraUser($dataList, 'file');
                if($module == 'project')   $this->importJiraProject($dataList, 'file');
                if($module == 'issue')     $this->importJiraIssue($dataList, 'file');
                if($module == 'build')     $this->importJiraBuild($dataList, 'file');
                if($module == 'issuelink') $this->importJiraIssueLink($dataList, 'file');
                if($module == 'action')    $this->importJiraAction($dataList, 'file');
                if($module == 'file')      $this->importJiraFile($dataList, 'file');

                $offset = $lastID + $limit;
                return array('type' => $module, 'count' => count($dataList), 'lastID' => $offset);
            }
        }

        $this->afterExec('file');
        return array('finished' => true);
    }

    /**
     * Import jira user.
     *
     * @param  object $dataList
     * @param  string $method
     * @access public
     * @return void
     */
    public function importJiraUser($dataList, $method = 'db')
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
     * Import jira project.
     *
     * @param  object $dataList
     * @param  string $method
     * @access public
     * @return void
     */
    public function importJiraProject($dataList, $method = 'db')
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
            $project->end    = date('Y-m-d', time() + 30 * 24 * 3600);

            $project->PM            = $this->getJiraAccount($data->LEAD, $method);
            $project->openedBy      = $this->getJiraAccount($data->LEAD, $method);
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

            $keyRelation['AType'] = 'joldkey';
            $keyRelation['BType'] = 'jnewkey';
            $keyRelation['AID']   = $data->ORIGINALKEY;
            $keyRelation['BID']   = $data->pkey;
            $keyRelation['extra'] = $data->ID;
            $this->dao->dbh($this->dbh)->insert(JIRA_TMPRELATION)->data($keyRelation)->exec();
        }
    }

    /**
     * Import jira issue.
     *
     * @param  object $dataList
     * @param  string $method
     * @access public
     * @return void
     */
    public function importJiraIssue($dataList, $method = 'db')
    {
        $relations = $this->session->jiraRelation;

        $this->app->loadLang('story');
        $this->app->loadLang('task');
        $this->app->loadLang('bug');

        $projectRelation = $this->dao->dbh($this->dbh)->select('AID,BID')->from(JIRA_TMPRELATION)
            ->where('AType')->eq('jproject')
            ->andWhere('BType')->eq('zproject')
            ->fetchPairs();

        $projectKeys = $this->dao->dbh($this->dbh)->select('extra as ID, AID as oldKey, BID as newKey')->from(JIRA_TMPRELATION)
            ->where('AType')->eq('joldkey')
            ->andWhere('BType')->eq('jnewkey')
            ->fetchAll('ID');

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

    /**
     * Import jira build.
     *
     * @param  object $dataList
     * @param  string $method
     * @access public
     * @return void
     */
    public function importJiraBuild($dataList, $method = 'db')
    {
        $issueObjectType = $this->dao->dbh($this->dbh)->select('AID,extra')->from(JIRA_TMPRELATION)
            ->where('AType')->eq('jissueid')
            ->andWhere('BType')->eq('zissuetype')
            ->fetchPairs();

        $issueBugs = $this->dao->dbh($this->dbh)->select('AID,BID')->from(JIRA_TMPRELATION)
            ->where('AType')->eq('jbug')
            ->andWhere('BType')->eq('zbug')
            ->fetchPairs();

        $issueStories = $this->dao->dbh($this->dbh)->select('AID,BID')->from(JIRA_TMPRELATION)
            ->where('AType')->eq('jstory')
            ->andWhere('BType')->eq('zstory')
            ->fetchPairs();

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
            $versionID    = $data->ID;
            $buildProject = $data->PROJECT;
            $projectID    = $projectRelation[$buildProject];
            $productID    = $projectProduct[$projectID];

            $build = new stdclass();
            $build->product     = $productID;
            $build->project     = $projectID;
            $build->name        = $data->vname;
            $build->date        = substr($data->RELEASEDATE, 0, 10);
            $build->builder     = $this->app->user->account;
            $build->createdBy   = $this->app->user->account;
            $build->createdDate = helper::now();

            $this->dao->dbh($this->dbh)->insert(TABLE_BUILD)->data($build)->exec();
            $buildID = $this->dao->dbh($this->dbh)->lastInsertID();

            /* Process build data. */
            if(isset($versionGroup[$versionID]))
            {
                foreach($versionGroup[$versionID] as $issue)
                {
                    $issueID   = $method == 'db' ? $issue->issueID : $issue;
                    $issueType = zget($issueObjectType, $issueID, '');
                    if(!$issueType || ($issueType != 'story' and $issueType != 'bug')) continue;
                    $objectID  = $issueType == 'bug' ? zget($issueBugs, $issueID) : zget($issueStories, $issueID);

                    $field = $issueType == 'story' ? 'stories' : 'bugs';
                    if($issueType == 'story')
                    {
                        $this->dao->dbh($this->dbh)->update(TABLE_BUILD)->set("stories = CONCAT(stories, ',$objectID')")->where('id')->eq($buildID)->exec();
                    }
                    else
                    {
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

            if(empty($data->RELEASEDATE)) continue;

            $release = new stdclass();
            $release->product     = $build->product;
            $release->build       = $buildID;
            $release->name        = $build->name;
            $release->date        = $build->date;
            $release->desc        = $data->DESCRIPTION;
            $release->status      = 'normal';
            $release->createdBy   = $this->app->user->account;
            $release->createdDate = helper::now();

            $this->dao->dbh($this->dbh)->insert(TABLE_RELEASE)->data($release)->exec();
            $releaseID = $this->dao->dbh($this->dbh)->lastInsertID();

            /* Process release data. */
            if(isset($versionGroup[$versionID]))
            {
                foreach($versionGroup[$versionID] as $issue)
                {
                    $issueID   = $method == 'db' ? $issue->issueID : $issue;
                    $issueType = zget($issueObjectType, $issueID, '');
                    if(!$issueType || ($issueType != 'story' and $issueType != 'bug')) continue;
                    $objectID  = $issueType == 'bug' ? zget($issueBugs, $issueID) : zget($issueStories, $issueID);

                    $field = $issueType == 'story' ? 'stories' : 'bugs';
                    if($issueType == 'story')
                    {
                        $this->dao->dbh($this->dbh)->update(TABLE_RELEASE)->set("stories = CONCAT(stories, ',$objectID')")->where('id')->eq($releaseID)->exec();
                    }
                    else
                    {
                        $this->dao->dbh($this->dbh)->update(TABLE_RELEASE)->set("bugs = CONCAT(bugs, ',$objectID')")->where('id')->eq($releaseID)->exec();
                    }
                }
            }
        }
    }

    /**
     * Import jira issue link.
     *
     * @param  object $dataList
     * @param  string $method
     * @access public
     * @return void
     */
    public function importJiraIssueLink($dataList, $method = 'db')
    {
        $issueLinkTypeList = array();
        $relations = $this->session->jiraRelation;

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

        foreach($duplicateLink as $source => $dest)
        {
            $objectType = $issueObjectType[$source];

            if($objectType != 'story' and $objectType != 'bug') continue;
            if($issueObjectType[$source] != $issueObjectType[$dest]) continue;

            if(!isset($relation[$objectType][$source]) or !isset($relation[$objectType][$dest])) continue;

            if($objectType == 'story')
            {
                if(empty($issueStories[$dest]) or empty($issueStories[$source])) continue;
                $this->dao->dbh($this->dbh)->update(TABLE_STORY)->set('duplicateStory')->eq($$issueStories[$dest])->where('id')->eq($issueStories[$source])->exec();
            }
            elseif($objectType == 'bug')
            {
                if(empty($issueBugs[$dest]) or empty($issueBugs[$source])) continue;
                $this->dao->dbh($this->dbh)->update(TABLE_BUG)->set('duplicateBug')->eq($issueBugs[$dest])->where('id')->eq($issueBugs[$source])->exec();
            }
        }

        foreach($relatesLink as $source => $dest)
        {
            if(empty($issueObjectType[$source]) or empty($issueObjectType[$dest])) continue;

            $sourceObjectType = $issueObjectType[$source];
            $destObjectType   = $issueObjectType[$dest];

            if($sourceObjectType == 'task' and $destObjectType == 'story')
            {
                $this->dao->dbh($this->dbh)->update(TABLE_TASK)->set('story')->eq($issueStories[$dest])->where('id')->eq($issueTasks[$source])->exec();
            }
            elseif($sourceObjectType == 'story' and $destObjectType == 'task')
            {
                $this->dao->dbh($this->dbh)->update(TABLE_TASK)->set('story')->eq($issueStories[$source])->where('id')->eq($issueTasks[$dest])->exec();
            }
            elseif($sourceObjectType == 'story' and $destObjectType == 'bug')
            {
                $this->dao->dbh($this->dbh)->update(TABLE_BUG)->set('story')->eq($issueStories[$source])->set('storyVersion')->eq(1)->where('id')->eq($issueBugs[$dest])->exec();
            }
            elseif($sourceObjectType == 'bug' and $destObjectType == 'story')
            {
                $this->dao->dbh($this->dbh)->update(TABLE_BUG)->set('story')->eq($issueStories[$dest])->set('storyVersion')->eq(1)->where('id')->eq($issueBugs[$source])->exec();
            }
            elseif($sourceObjectType == 'story' and $destObjectType == 'story')
            {
                $this->dao->dbh($this->dbh)->update(TABLE_STORY)->set("linkStories=concat(linkStories, ',{$issueStories[$dest]}')")->where('id')->eq($issueStories[$source])->exec();
            }
            elseif($sourceObjectType == 'bug' and $destObjectType == 'bug')
            {
                $this->dao->dbh($this->dbh)->update(TABLE_BUG)->set("relatedBug=concat(relatedBug, ',{$issueBugs[$dest]}')")->where('id')->eq($issueBugs[$source])->exec();
            }
        }
    }

    /**
     * Import jira action.
     *
     * @param  object $dataList
     * @param  string $method
     * @access public
     * @return void
     */
    public function importJiraAction($dataList, $method = 'db')
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

        $projectRelation = $this->dao->dbh($this->dbh)->select('AID,BID')->from(JIRA_TMPRELATION)
            ->where('AType')->eq('jproject')
            ->andWhere('BType')->eq('zproject')
            ->fetchPairs();

        $projectProduct = $this->dao->dbh($this->dbh)->select('project,product')->from(TABLE_PROJECTPRODUCT)
            ->where('project')->in(array_values($projectRelation))
            ->fetchPairs();

        foreach($dataList as $data)
        {
            $action = new stdclass();

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
     * Import jira file.
     *
     * @param  object $dataList
     * @param  string $method
     * @access public
     * @return void
     */
    public function importJiraFile($dataList, $method = 'db')
    {
        $this->loadModel('file');

        $issueObjectType = $this->dao->dbh($this->dbh)->select('AID,extra')->from(JIRA_TMPRELATION)
            ->where('AType')->eq('jissueid')
            ->andWhere('BType')->eq('zissuetype')
            ->fetchPairs();

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

        $filePaths = $this->dao->dbh($this->dbh)->select('AID,extra')->from(JIRA_TMPRELATION)
            ->where('AType')->eq('jissueid')
            ->andWhere('BType')->eq('jfilepath')
            ->fetchPairs();

        foreach($dataList as $fileAttachment)
        {
            $issueID    = $fileAttachment->issueid;
            if(!isset($issueObjectType[$issueID])) continue;

            $objectType = $issueObjectType[$issueID];
            if($objectType != 'bug' and $objectType != 'task' and $objectType != 'story') continue;

            $fileID     = $fileAttachment->ID;
            $fileName   = $fileAttachment->FILENAME;
            list($mime, $extension) = explode('/', $fileAttachment->MIMETYPE);

            if($objectType == 'bug')   $objectID = $issueBugs[$issueID];
            if($objectType == 'task')  $objectID = $issueTasks[$issueID];
            if($objectType == 'story') $objectID = $issueStories[$issueID];
            if(empty($objectID)) continue;

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
     * Convert jira status.
     *
     * @param  string $objectType
     * @param  string $jiraStatus
     * @access public
     * @return void
     */
    public function convertStatus($objectType, $jiraStatus)
    {
        $status = 'active';
        if($objectType == 'task') $status = 'wait';
        $relations = $this->session->jiraRelation;

        $arrayKey       = "{$objectType}Status";
        $jiraStatusList = array_flip($relations['jiraStatus']);
        $statusID       = $jiraStatusList[$jiraStatus];

        if(!empty($relations[$arrayKey][$statusID])) $status = $relations[$arrayKey][$statusID];

        return $status;
    }

    /**
     * Convert stage.
     *
     * @param  string $jiraStatus
     * @access public
     * @return void
     */
    public function convertStage($jiraStatus)
    {
        $stage = 'wait';
        $relations = $this->session->jiraRelation;

        $jiraStatusList = array_flip($relations['jiraStatus']);
        $stageID        = $jiraStatusList[$jiraStatus];

        if(!empty($relations['storyStage'][$stageID])) $stage = $relations['storyStage'][$stageID];

        return $stage;
    }

    /**
     * Get jira account.
     *
     * @param  string $userKey
     * @param  string $method
     * @access public
     * @return void
     */
    public function getJiraAccount($userKey, $method = 'db')
    {
        if(strpos($userKey, 'JIRAUSER') === false) return $userKey;

        if($method == 'db')
        {
            return $this->dao->dbh($this->sourceDBH)->select('lower_user_name')->from(JIRA_USER)->where('user_key')->eq($userKey)->fetch('lower_user_name');
        }
        else
        {
            $appUsers = $this->getJiraAppUser();
            return zget($appUsers, $userKey, $userKey);
        }
    }

    /**
     * Get jira app user pairs.
     *
     * @access public
     * @return void
     */
    public function getJiraAppUser()
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
     * Split jira file.
     *
     * @access public
     * @return void
     */
    public function splitFile()
    {
        $filePath = $this->app->getTmpRoot() . 'jirafile/';
        $fileName = 'entities.xml';
        $file     = $filePath . $fileName;
        $handle   = fopen($file, "r");

        $usingData  = array();
        $headerList = array('<Action', '<Project', '<Status', '<Resolution', '<User', '<Issue', '<ChangeGroup', '<ChangeItem', '<IssueLink', '<IssueLinkType', '<FileAttachment', '<Version', '<IssueType', '<NodeAssociation', '<ApplicationUser');
        $footerList = array('<Action' => '</Action>', '<Project' => '</Project>', '<Status' => '</Status>', '<Resolution' => '</Resolution>', '<User' => '</User>', '<Issue' => '</Issue>', '<ChangeGroup' => '</ChangeGroup>', '<ChangeItem' => '</ChangeItem>', '<IssueLink' => '</IssueLink>', '<IssueLinkType' => '</IssueLinkType>', '<FileAttachment' => '</FileattAchment>', '<Version' => '</Version>', '<IssueType' => '</IssueType>', '<NodeAssociation' => '</NodeAssociation>', '<ApplicationUser' => '</ApplicationUser>');

        while(!feof($handle))
        {
            $itemStr = fgets($handle);
            foreach($headerList as $object)
            {
                $itemName  = $object;
                $itemName .= ' ';

                if(strpos($itemStr, $itemName) === false) continue;

                if(strpos($itemStr, '/>') === false)
                {
                    $end = $footerList[$object];
                    while(true)
                    {
                        $followItemStr = fgets($handle);
                        $itemStr      .= $followItemStr;
                        if(strpos($itemStr, $end) !== false) break;
                    }
                }

                $object = str_replace('<', '', $object);
                $object = strtolower($object);
                $data   = preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $itemStr);
                if(!file_exists($filePath . $object . '.xml')) $data = "<?xml version='1.0' encoding='UTF-8'?>\n  <entity-engine-xml>\n" . $data;
                file_put_contents($filePath . $object . '.xml', $data, FILE_APPEND);
            }
        }

        foreach($headerList as $object)
        {
            $object   = str_replace('<', '', $object);
            $object   = strtolower($object);
            $filename = $filePath . $object . '.xml';
            if(file_exists($filename)) file_put_contents($filename, '</entity-engine-xml>', FILE_APPEND);
        }

        fclose($handle);
    }

    /**
     * Create tmp table for import jira.
     *
     * @access public
     * @return void
     */
    public function createTmpTable4Jira()
    {
$sql = <<<EOT
CREATE TABLE `jiratmprelation`(
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `AType` char(30) NOT NULL,
  `AID` char(30) NOT NULL,
  `BType` char(30) NOT NULL,
  `BID` char(30) NOT NULL,
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

    /**
     * After exec.
     *
     * @param  string $method
     * @access public
     * @return void
     */
    public function afterExec($method = 'db')
    {
        /* Set project min start date. */
        $minDate            = date('Y-m-d', time() - 30 * 24 * 3600);
        $executionProject   = $this->dao->dbh($this->dbh)->select('id,project')->from(TABLE_PROJECT)->where('type')->eq('sprint')->andWhere('project')->ne(0)->fetchPairs();
        $minOpenedDatePairs = $this->dao->dbh($this->dbh)->select('execution,min(openedDate) as minOpenedDate')->from(TABLE_TASK)->where('execution')->in(array_keys($executionProject))->fetchPairs('execution', 'minOpenedDate');

        foreach($executionProject  as $executionID => $projectID)
        {
            $minOpenedDate = isset($minOpenedDatePairs[$executionID]) ? $minOpenedDatePairs[$executionID] : $minDate;
            $minOpenedDate = substr($minOpenedDate, 0, 11);
            $minOpenedDate = helper::isZeroDate($minOpenedDate) ? $minDate : $minOpenedDate;
            $this->dao->update(TABLE_PROJECT)->set('begin')->eq($minOpenedDate)->where('id')->eq($projectID)->orWhere('id')->eq($executionID)->exec();
        }

        if($method == 'file') $this->deleteJiraFile();

        $this->dbh->exec("DROP TABLE" . JIRA_TMPRELATION);
    }

    /**
     * Delete jira backip file.
     *
     * @access public
     * @return void
     */
    public function deleteJiraFile()
    {
        $fileList = array('action', 'project', 'status', 'resolution', 'user', 'issue', 'changegroup', 'changeitem', 'issuelink', 'issuelinktype', 'fileattachment', 'version', 'issuetype', 'nodeassociation', 'applicationuser');
        foreach($fileList as $fileName)
        {
            $filePath = $this->app->getTmpRoot() . 'jirafile/' . $fileName . '.xml';
            if(file_exists($filePath)) @unlink($filePath);
        }
    }

    /**
     * Check dbName is valide.
     *
     * @param  string $dbName
     * @access public
     * @return bool
     */
    public function checkDBName($dbName)
    {
        if(preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $dbName)) return true;
        return false;
    }
}
