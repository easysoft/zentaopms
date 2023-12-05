<?php
/**
 * The control file of convert currentModule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id: control.php 4976 2013-07-02 08:15:31Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
class convert extends control
{
    /**
     * 数据导入首页。
     * Index page of convert.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        $this->convert->saveState();
        $this->view->title = $this->lang->convert->common;
        $this->display();
    }

    /**
     * 选择来源系统。
     * Select the source system.
     *
     * @access public
     * @return void
     */
    public function selectSource()
    {
        $this->view->title = $this->lang->convert->common . $this->lang->colon . $this->lang->convert->start;
        $this->display();
    }

    /**
     * Set configs of converter.
     *
     * This is the extrance of every system. It will call the set function of corresponding module.
     *
     * @access public
     * @return void
     */
    public function setConfig()
    {
        if(!$this->post->source)
        {
            echo js::alert($this->lang->convert->mustSelectSource);
            return print(js::locate('back'));
        }
        list($sourceName, $version) = explode('_', $this->post->source);
        $setFunc = "set$sourceName";
        $this->view->title      = $this->lang->convert->common . $this->lang->colon . $this->lang->convert->setting;
        $this->view->source  = $sourceName;
        $this->view->version = $version;
        $this->view->setting = $this->fetch('convert', $setFunc, "version=$version");
        $this->display();
    }

    /**
     * The setting page of bugfree.
     *
     * @param  string    $version
     * @access public
     * @return void
     */
    public function setBugFree($version)
    {
        $this->view->source      = 'BugFree';
        $this->view->version     = $version;
        $this->view->tablePrefix = $version > 1 ? 'bf_' : '';
        $this->view->dbName      = $version > 1 ? 'bugfree2' : 'BugFree';
        $this->view->dbCharset   = 'utf8';
        $this->display();
    }

    /**
     * The setting page of Redmine.
     *
     * @param  string    $version
     * @access public
     * @return void
     */
    public function setRedmine($version)
    {
        $this->view->source      = 'Redmine';
        $this->view->version     = $version;
        $this->view->dbName      = 'redmine';
        $this->view->dbCharset   = 'utf8';
        $this->display();
    }

    /**
     * Check config. Same as setConfig.
     *
     * @access public
     * @return void
     */
    public function checkConfig()
    {
        $checkFunc = 'check' . $this->post->source;
        $this->view->title       = $this->lang->convert->common . $this->lang->colon . $this->lang->convert->checkConfig;
        $this->view->source      = $this->post->source;
        $this->view->checkResult = $this->fetch('convert', $checkFunc, "version={$this->post->version}");
        $this->display();
    }

    /**
     * Check settings of bugfree.
     *
     * @param  int    $version
     * @access public
     * @return void
     */
    public function checkBugFree($version)
    {
        helper::import('./converter/bugfree.php');
        $converter = new bugfreeConvertModel();

        /* Check it. */
        $checkInfo['db'] = $converter->connectDB();
        //if(is_object($checkInfo['db'])) $checkInfo['table'] = $converter->checkTables();
        $checkInfo['path'] = $converter->checkPath();

        /* Compute the checking result. */
        $result = 'pass';
        if(!is_object($checkInfo['db']) or !$checkInfo['path']) $result = 'fail';

        /* Assign. */
        $this->view->version   = $version;
        $this->view->source    = 'bugfree';
        $this->view->result    = $result;
        $this->view->checkInfo = $checkInfo;
        $this->display();
    }

    /**
     * Check settings of Redmine.
     *
     * @param  int    $version
     * @access public
     * @return void
     */
    public function checkRedmine($version)
    {
        helper::import('./converter/redmine.php');
        $converter = new redmineConvertModel();

        /* Check it. */
        $checkInfo['db'] = $converter->connectDB();
        $checkInfo['path'] = $converter->checkPath();

        $this->view->trackers = $this->dao->dbh($converter->sourceDBH)->select('id, name')->from('trackers')->fetchAll('id');
        $this->view->statuses = $this->dao->dbh($converter->sourceDBH)->select('id, name')->from('issue_statuses')->fetchAll('id');
        $this->view->pries    = $this->dao->dbh($converter->sourceDBH)->select('id, name')->from('enumerations')->where('type')->eq('IssuePriority')->fetchAll('id');
        /* Compute the checking result. */
        $result = 'pass';
        if(!is_object($checkInfo['db']) or !$checkInfo['path']) $result = 'fail';

        $this->app->loadLang('bug');
        $this->app->loadLang('story');
        $this->app->loadLang('task');
        $this->view->aimTypeList['bug']   = 'bug';
        $this->view->aimTypeList['task']  = 'task';
        $this->view->aimTypeList['story'] = 'story';

        /* Assign. */
        $this->view->version   = $version;
        $this->view->source    = 'Redmine';
        $this->view->result    = $result;
        $this->view->checkInfo = $checkInfo;
        $this->display();
    }

   /**
     * Execute the converting.
     *
     * @access public
     * @return void
     */
    public function execute()
    {
        $convertFunc = 'convert' . $this->post->source;
        $this->view->title      = $this->lang->convert->common . $this->lang->colon . $this->lang->convert->execute;
        $this->view->source     = $this->post->source;
        $this->view->version    = $this->post->version;

        $this->view->executeResult = $this->fetch('convert', $convertFunc, "version={$this->post->version}");
        $this->display();
    }

    /**
     * Convert bugfree.
     *
     * @param  int    $version
     * @access public
     * @return void
     */
    public function convertBugFree($version)
    {
        helper::import('./converter/bugfree.php');
        helper::import("./converter/bugfree$version.php");
        $className = "bugfree{$version}ConvertModel";
        $converter = new $className();
        $this->view->version = $version;
        $this->view->result  = $converter->execute($version);
        $this->view->info    = bugfreeConvertModel::$info;
        $this->display();
    }

    /**
     * convert redmine
     *
     * @param  int    $version
     * @access public
     * @return void
     */
    public function convertRedmine($version)
    {
        helper::import('./converter/redmine.php');
        helper::import("./converter/redmine$version.php");
        $className = "redmine11ConvertModel";
        $redmine = new stdclass();
        $redmine->aimTypes             = $this->post->aimTypes;
        $redmine->statusTypes['bug']   = $this->post->statusTypesOfBug;
        $redmine->statusTypes['story'] = $this->post->statusTypesOfStory;
        $redmine->statusTypes['task']  = $this->post->statusTypesOfTask;
        $redmine->priTypes['bug']      = $this->post->priTypesOfBug;
        $redmine->priTypes['story']    = $this->post->priTypesOfStory;
        $redmine->priTypes['task']     = $this->post->priTypesOfTask;

        $converter = new $className($redmine);
        $this->view->version = $version;
        $this->view->result  = $converter->execute($version);
        $this->view->info    = redmineConvertModel::$info;
        $this->display();
    }

    /**
     * Import jira index.
     *
     * @access public
     * @return void
     */
    public function convertJira()
    {
        $this->view->title = $this->lang->convert->jira->method;
        $this->display();
    }

    /**
     * Import jira notice.
     *
     * @param  string $mehotd db|file
     * @access public
     * @return void
     */
    public function importNotice(string $method = 'db')
    {
        if($this->server->request_method == 'POST')
        {
            if($method == 'db')
            {
                $dbName = $this->post->dbName;
                if(!$dbName) return $this->send(array('result' => 'fail', 'message' => array('dbName' => sprintf($this->lang->error->notempty, $this->lang->convert->jira->database))));
                if(!$this->convert->dbExists($dbName)) return $this->send(array('result' => 'fail', 'message' => array('dbName' => $this->lang->convert->jira->invalidDB)));
                if(!$this->convert->tableExistsOfJira($dbName, 'nodeassociation')) return $this->send(array('result' => 'fail', 'message' => array('dbName' => $this->lang->convert->jira->invalidTable)));

                $this->session->set('jiraDB', $dbName);
                $link = $this->createLink('convert', 'mapJira2Zentao', "method=db&dbName={$this->post->dbName}");

                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $link));
            }

            $this->convert->deleteJiraFile();
            $jiraFilePath = $this->app->getTmpRoot() . 'jirafile/';
            if(!is_readable($jiraFilePath) || !is_writable($jiraFilePath)) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->convert->jira->notReadAndWrite, $jiraFilePath)));
            if(!file_exists($jiraFilePath . 'entities.xml')) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->convert->jira->notExistEntities, $jiraFilePath . 'entities.xml')));

            $this->convert->splitFile();

            $link = $this->createLink('convert', 'mapJira2Zentao', 'method=file');
            return $this->send(array('result' => 'success', 'load' => $link));
        }

        $this->view->title  = $this->lang->convert->jira->method;
        $this->view->method = $method;
        $this->display();
    }

    /**
     * Map jira objects to zentao.
     *
     * @param  string $method db|file
     * @param  string $dbName
     * @param  int    $step
     * @access public
     * @return void
     */
    public function mapJira2Zentao($method = 'db', $dbName = '', $step = 1)
    {
        $this->app->loadLang('story');
        $this->app->loadLang('bug');
        $this->app->loadLang('task');

        if($_POST)
        {
            foreach($_POST as $key => $value) $_SESSION['jiraRelation'][$key] = $value;

            $link = $step == 4 ? inlink('initJiraUser', "method={$method}") : inlink('mapJira2Zentao', "method={$method}&dbName={$dbName}&step=" . ++$step);
            return $this->send(array('result' => 'success', 'load' => $link));
        }

        if($method == 'db')
        {
            $dbh = $this->convert->connectDB($dbName);

            $issueTypeList  = $this->dao->dbh($dbh)->select('*')->from(JIRA_ISSUETYPE)->fetchAll('ID');
            $linkTypeList   = $this->dao->dbh($dbh)->select('*, LINKNAME as linkname')->from(JIRA_ISSUELINKTYPE)->fetchAll('ID');
            $resolutionList = $this->dao->dbh($dbh)->select('*')->from(JIRA_RESOLUTION)->fetchAll('ID');
            $statusList     = $this->dao->dbh($dbh)->select('*')->from(JIRA_ISSUESTATUS)->fetchAll('ID');
        }
        else
        {
            $issueTypeList  = $this->convert->getJiraDataFromFile('issuetype');
            $linkTypeList   = $this->convert->getJiraDataFromFile('issuelinktype');
            $resolutionList = $this->convert->getJiraDataFromFile('resolution');
            $statusList     = $this->convert->getJiraDataFromFile('status');
        }

        $this->view->title          = $this->lang->convert->jira->mapJira2Zentao;
        $this->view->issueTypeList  = $issueTypeList;
        $this->view->linkTypeList   = $linkTypeList;
        $this->view->resolutionList = $resolutionList;
        $this->view->statusList     = $statusList;
        $this->view->method         = $method;
        $this->view->step           = $step;
        $this->view->dbName         = $dbName;
        $this->display();
    }

    /**
     * Init jira user.
     *
     * @param  string $method db|file
     * @access public
     * @return void
     */
    public function initJiraUser($method = 'db')
    {
        $this->app->loadLang('user');

        if($_POST)
        {
            $errors = array();
            if(!$this->post->password1) $errors['password1'][] = sprintf($this->lang->error->notempty, $this->lang->user->password);
            if(!$this->post->password2) $errors['password2'][] = sprintf($this->lang->error->notempty, $this->lang->user->abbr->password2);
            if($this->post->password1 && strlen(trim($this->post->password1)) < 6) $errors['password1'][] = $this->lang->convert->jira->passwordLess;
            if($this->post->password1 && $this->post->password2 && $this->post->password1 != $this->post->password2) $errors['password2'][] = $this->lang->convert->jira->passwordDifferent;
            if($errors) return $this->send(array('result' => 'fail', 'message' => $errors));

            $jiraUser['password'] = md5($this->post->password1);
            $jiraUser['group']    = $this->post->group;
            $this->session->set('jiraUser', $jiraUser);

            return $this->send(array('result' => 'success', 'load' => inlink('importJira', "method={$method}")));
        }

        $this->view->title  = $this->lang->convert->jira->initJiraUser;
        $this->view->groups = $this->loadModel('group')->getPairs();
        $this->view->method = $method;
        $this->display();
    }

    /**
     * Import jira main logic.
     *
     * @param  string $method db|file
     * @param  string $mode   show|import
     * @param  string $type   user|issue|project|attachment
     * @param  int    $lastID
     * @param  bool   $createTable
     * @access public
     * @return void
     */
    public function importJira($method = 'db', $mode = 'show', $type = 'user', $lastID = 0, $createTable = false)
    {
        set_time_limit(0);

        if($mode == 'import')
        {
            $importFunc = 'importJiraFrom' . $method;
            $result     = $this->convert->$importFunc($type, $lastID, $createTable);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($result['finished'])) return $this->send(array('result' => 'finished', 'message' => $this->lang->convert->jira->importSuccessfully));

            $type = zget($this->lang->convert->jira->objectList, $result['type'], $result['type']);

            $response['result']  = 'unfinished';
            $response['type']    = $type;
            $response['count']   = $result['count'];
            $response['message'] = sprintf($this->lang->convert->jira->importResult, $type, $type, $result['count']);
            $response['next']    = inlink('importJira', "method={$method}&mode={$mode}&type={$result['type']}&lastID={$result['lastID']}");
            return $this->send($response);
        }

        $this->view->title  = $this->lang->convert->jira->importJira;
        $this->view->method = $method;
        $this->display();
    }
}
