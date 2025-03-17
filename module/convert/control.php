<?php
declare(strict_types=1);
/**
 * The control file of convert currentModule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id: control.php 4976 2013-07-02 08:15:31Z wyd621@gmail.com $
 * @link        https://www.zentao.net
 */
class convert extends control
{
    /**
     * 数据导入首页。
     * Index page of convert.
     *
     * @param  string $mode
     * @access public
     * @return void
     */
    public function index(string $mode = '')
    {
        $jiraRelation = $this->session->jiraRelation;
        $jiraRelation = $jiraRelation ? json_decode($jiraRelation, true) : array();
        if($jiraRelation && $mode == 'restore')
        {
            $currentStep = 'object';
            $stepStatus  = $this->session->stepStatus;
            $stepStatus  = $stepStatus ? json_decode($stepStatus, true) : array();
            $stepList    = $this->convert->getJiraStepList($jiraRelation);
            foreach($stepList as $step => $stepLabel)
            {
                if(!empty($stepStatus[$step]) && $stepStatus[$step] == 'done') $currentStep = $step;
            }
            $confirmedURL = $currentStep == 'user' ? inlink('initJiraUser', "method={$this->session->jiraMethod}&dbName={$this->session->jiraDB}") : inlink('mapJira2Zentao', "method={$this->session->jiraMethod}&dbName={$this->session->jiraDB}&step=$currentStep");
            $canceledURL  = inlink('index', 'type=reset');
            $this->send(array('result' => 'success', 'load' => array('confirm' => $this->lang->convert->jira->restore, 'confirmed' => $confirmedURL, 'canceled' => $canceledURL)));
        }
        if($mode == 'reset')
        {
            unset($_SESSION['jiraDB']);
            unset($_SESSION['jiraMethod']);
            unset($_SESSION['jiraRelation']);
            unset($_SESSION['stepStatus']);
            unset($_SESSION['jiraUser']);
        }

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
        $this->view->title = $this->lang->convert->common . $this->lang->hyphen . $this->lang->convert->start;
        $this->display();
    }

    /**
     * 生成配置文件。
     * Set configs of converter.
     *
     * This is the extrance of every system. It will call the set function of corresponding module.
     *
     * @access public
     * @return void
     */
    public function setConfig()
    {
        if(!$this->post->source) return $this->sendError($this->lang->convert->mustSelectSource, 'back');

        list($sourceName, $version) = explode('_', $this->post->source);
        $setFunc = "set$sourceName";
        $this->view->title   = $this->lang->convert->common . $this->lang->hyphen . $this->lang->convert->setting;
        $this->view->source  = $sourceName;
        $this->view->version = $version;
        $this->view->setting = $this->fetch('convert', $setFunc, "version=$version");
        $this->display();
    }

    /**
     * 设置bugfree页面。
     * The setting page of bugfree.
     *
     * @param  int    $version
     * @access public
     * @return void
     */
    public function setBugFree(int $version)
    {
        $this->view->source      = 'BugFree';
        $this->view->version     = $version;
        $this->view->tablePrefix = $version > 1 ? 'bf_' : '';
        $this->view->dbName      = $version > 1 ? 'bugfree2' : 'BugFree';
        $this->view->dbCharset   = 'utf8';
        $this->display();
    }

    /**
     * 设置Redmine页面。
     * The setting page of Redmine.
     *
     * @param  string    $version
     * @access public
     * @return void
     */
    public function setRedmine(string $version)
    {
        $this->view->source    = 'Redmine';
        $this->view->version   = $version;
        $this->view->dbName    = 'redmine';
        $this->view->dbCharset = 'utf8';
        $this->display();
    }

    /**
     * 检查配置。
     * Check config. Same as setConfig.
     *
     * @access public
     * @return void
     */
    public function checkConfig()
    {
        $checkFunc = 'check' . $this->post->source;
        $this->view->title       = $this->lang->convert->common . $this->lang->hyphen . $this->lang->convert->checkConfig;
        $this->view->source      = $this->post->source;
        $this->view->checkResult = $this->fetch('convert', $checkFunc, "version={$this->post->version}");
        $this->display();
    }

    /**
     * 检查bugfree设置。
     * Check settings of bugfree.
     *
     * @param  int    $version
     * @access public
     * @return void
     */
    public function checkBugFree(int $version)
    {
        helper::import('./converter/bugfree.php');
        $converter = new bugfreeConvertModel();

        /* Check it. */
        $checkInfo['db']   = $converter->connectDB();
        $checkInfo['path'] = $converter->checkPath();

        /* Compute the checking result. */
        $result = 'pass';
        if(!is_object($checkInfo['db']) || !$checkInfo['path']) $result = 'fail';

        /* Assign. */
        $this->view->version   = $version;
        $this->view->source    = 'bugfree';
        $this->view->result    = $result;
        $this->view->checkInfo = $checkInfo;
        $this->display();
    }

    /**
     * 检查Redmine的设置。
     * Check settings of Redmine.
     *
     * @param  int    $version
     * @access public
     * @return void
     */
    public function checkRedmine(int $version)
    {
        helper::import('./converter/redmine.php');
        $converter = new redmineConvertModel();

        /* Check it. */
        $checkInfo['db']   = $converter->connectDB();
        $checkInfo['path'] = $converter->checkPath();

        $this->view->trackers = $this->dao->dbh($converter->sourceDBH)->select('id, name')->from('trackers')->fetchAll('id');
        $this->view->statuses = $this->dao->dbh($converter->sourceDBH)->select('id, name')->from('issue_statuses')->fetchAll('id');
        $this->view->pries    = $this->dao->dbh($converter->sourceDBH)->select('id, name')->from('enumerations')->where('type')->eq('IssuePriority')->fetchAll('id');

        /* Compute the checking result. */
        $result = 'pass';
        if(!is_object($checkInfo['db']) || !$checkInfo['path']) $result = 'fail';

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
     * 执行数据导入。
     * Execute the converting.
     *
     * @access public
     * @return void
     */
    public function execute()
    {
        $convertFunc = 'convert' . $this->post->source;
        $this->view->title         = $this->lang->convert->common . $this->lang->hyphen . $this->lang->convert->execute;
        $this->view->source        = $this->post->source;
        $this->view->version       = $this->post->version;
        $this->view->executeResult = $this->fetch('convert', $convertFunc, "version={$this->post->version}");
        $this->display();
    }

    /**
     * 导入bugfree。
     * Convert bugfree.
     *
     * @param  int    $version
     * @access public
     * @return void
     */
    public function convertBugFree(int $version)
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
     * 导入redmine。
     * convert redmine.
     *
     * @param  int    $version
     * @access public
     * @return void
     */
    public function convertRedmine(int $version)
    {
        helper::import('./converter/redmine.php');
        helper::import("./converter/redmine$version.php");

        $redmine = new stdclass();
        $redmine->aimTypes             = $this->post->aimTypes;
        $redmine->statusTypes['bug']   = $this->post->statusTypesOfBug;
        $redmine->statusTypes['story'] = $this->post->statusTypesOfStory;
        $redmine->statusTypes['task']  = $this->post->statusTypesOfTask;
        $redmine->priTypes['bug']      = $this->post->priTypesOfBug;
        $redmine->priTypes['story']    = $this->post->priTypesOfStory;
        $redmine->priTypes['task']     = $this->post->priTypesOfTask;

        $className = "redmine11ConvertModel";
        $converter = new $className($redmine);
        $this->view->version = $version;
        $this->view->result  = $converter->execute($version);
        $this->view->info    = redmineConvertModel::$info;
        $this->display();
    }

    /**
     * Jira数据导入提示。
     * Import jira notice.
     *
     * @param  string $mehotd db|file
     * @access public
     * @return void
     */
    public function importJiraNotice(string $method = 'db')
    {
        if($this->server->request_method == 'POST')
        {
            $domain = $this->post->jiraDomain;
            if($domain && strpos($domain, 'http') === false) $domain = 'http://' . $domain;

            $jiraApi = array();
            $jiraApi['domain'] = $domain ? trim($domain, '/')     : '';
            $jiraApi['admin']  = $domain ? $this->post->jiraAdmin : '';
            $jiraApi['token']  = $domain ? $this->post->jiraToken : '';

            $this->session->set('jiraApi',    json_encode($jiraApi));
            $this->session->set('jiraDB',     $this->post->dbName);
            $this->session->set('jiraMethod', $method);

            if($method == 'db')
            {
                $dbName = $this->post->dbName;
                if(!$dbName) return $this->send(array('result' => 'fail', 'message' => array('dbName' => sprintf($this->lang->error->notempty, $this->lang->convert->jira->database))));
                if(!$this->convert->dbExists($dbName)) return $this->send(array('result' => 'fail', 'message' => array('dbName' => $this->lang->convert->jira->invalidDB)));
                if(!$this->convert->tableExistsOfJira($dbName, 'nodeassociation')) return $this->send(array('result' => 'fail', 'message' => array('dbName' => $this->lang->convert->jira->invalidTable)));
            }
            else
            {
                if($domain)
                {
                    $this->convert->checkJiraApi();
                    if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
                }
                $this->convert->deleteJiraFile();
                $jiraFilePath = $this->app->getTmpRoot() . 'jirafile/';
                if(!is_readable($jiraFilePath) || !is_writable($jiraFilePath)) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->convert->jira->notReadAndWrite, $jiraFilePath)));
                if(!file_exists($jiraFilePath . 'entities.xml')) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->convert->jira->notExistEntities, $jiraFilePath . 'entities.xml')));

                /* 解析entities.xml文件。 */
                $this->convert->splitFile();
            }

            $link = $this->createLink('convert', 'mapJira2Zentao', "method={$method}&dbName={$this->post->dbName}");
            return $this->send(array('result' => 'success', 'load' => $link));
        }

        $this->view->title   = $this->lang->convert->jira->method;
        $this->view->method  = $method;
        $this->view->jiraApi = !empty($_SESSION['jiraApi']) ? json_decode($this->session->jiraApi) : array();
        $this->display();
    }

    /**
     * 获取下一步。
     * Get next key.
     *
     * @param  array  $array
     * @param  string $currentKey
     * @access public
     * @return void
     */
    public function getNextKey($array, $currentKey)
    {
        $keys = array_keys($array);
        $currentIndex = array_search($currentKey, $keys);
        if($currentIndex !== false && isset($keys[$currentIndex + 1])) return $keys[$currentIndex + 1];
        return false;
    }

    /**
     * 获取上一步。
     * Get back key.
     *
     * @param  array  $array
     * @param  string $currentKey
     * @access public
     * @return void
     */
    public function getBackKey($array, $currentKey)
    {
        $keys = array_keys($array);
        $currentIndex = array_search($currentKey, $keys);
        if($currentIndex !== false && isset($keys[$currentIndex - 1])) return $keys[$currentIndex - 1];
        return false;
    }

    /**
     * 将jira对象映射到zentao。
     * Map jira objects to zentao.
     *
     * @param  string $method db|file
     * @param  string $dbName
     * @param  string $step
     * @access public
     * @return void
     */
    public function mapJira2Zentao(string $method = 'db', string $dbName = '', string $step = 'object')
    {
        $stepStatus   = $this->session->stepStatus;
        $stepStatus   = $stepStatus ? json_decode($stepStatus, true) : array();
        $jiraRelation = $this->session->jiraRelation;
        $jiraRelation = $jiraRelation ? json_decode($jiraRelation, true) : array();
        if($step != 'object' && empty($jiraRelation)) $this->locate(inlink('index'));

        if($_POST)
        {
            $this->convert->checkImportJira($step);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            foreach($_POST as $key => $value) $jiraRelation[$key] = $value;
            $this->session->set('jiraRelation', json_encode($jiraRelation));

            $stepStatus[$step] = 'done';
            $this->session->set('stepStatus', json_encode($stepStatus));

            /* 提交后要获取新的步骤列表。 */
            $stepList  = $this->convert->getJiraStepList($jiraRelation);
            $nextSteps = $this->getNextKey($stepList, $step);

            $link = $step == 'relation' ? inlink('initJiraUser', "method={$method}&dbName={$dbName}") : inlink('mapJira2Zentao', "method={$method}&dbName={$dbName}&step={$nextSteps}");
            return $this->send(array('result' => 'success', 'load' => $link));
        }

        $this->loadModel('story');
        $this->loadModel('bug');
        $this->loadModel('task');
        $objectRelation = !empty($jiraRelation['zentaoObject']) && in_array($step, array_keys($jiraRelation['zentaoObject']));
        $resolutionList = $objectRelation ? $this->convert->getJiraData($method, 'resolution')       : array();
        $statusList     = $objectRelation ? $this->convert->getJiraStatusList($step, $jiraRelation)  : array();
        $jiraFields     = $objectRelation ? $this->convert->getJiraCustomField($step, $jiraRelation) : array();
        $issueTypeList  = $this->convert->getJiraTypeList();
        $linkTypeList   = $step == 'relation' ? $this->convert->getJiraData($method, 'issuelinktype') : array();
        $stepList       = $this->convert->getJiraStepList($jiraRelation, $issueTypeList);
        $backSteps      = $this->getBackKey($stepList, $step);

        $this->view->title          = $this->lang->convert->jira->mapJira2Zentao;
        $this->view->method         = $method;
        $this->view->step           = $step;
        $this->view->dbName         = $dbName;
        $this->view->stepStatus     = $stepStatus;
        $this->view->stepList       = $stepList;
        $this->view->jiraRelation   = $jiraRelation;
        $this->view->issueTypeList  = $issueTypeList;
        $this->view->zentaoObjects  = $this->convert->getZentaoObjectList();
        $this->view->fieldList      = $jiraFields;
        $this->view->statusList     = $statusList;
        $this->view->jiraActions    = $this->convert->getJiraWorkflowActions();
        $this->view->resolutionList = $resolutionList;
        $this->view->defaultValue   = $this->convert->getObjectDefaultValue($step);
        $this->view->linkTypeList   = $linkTypeList;
        $this->view->backUrl        = $backSteps ? inlink('mapJira2Zentao', "method={$method}&dbName={$dbName}&step={$backSteps}") : '';
        $this->display();
    }

    /**
     * 初始化jira用户。
     * Init jira user.
     *
     * @param  string $method db|file
     * @param  string $dbName
     * @access public
     * @return void
     */
    public function initJiraUser(string $method = 'db', string $dbName = '')
    {
        $this->app->loadLang('user');
        $stepStatus = $this->session->stepStatus;
        $stepStatus = $stepStatus ? json_decode($stepStatus, true) : array();

        if($_POST)
        {
            $errors = array();
            if(!$this->post->password1) $errors['password1'][] = sprintf($this->lang->error->notempty, $this->lang->user->password);
            if(!$this->post->password2) $errors['password2'][] = sprintf($this->lang->error->notempty, $this->lang->user->password2);
            if($this->post->password1 && strlen(trim($this->post->password1)) < 6) $errors['password1'][] = $this->lang->convert->jira->passwordLess;
            if($this->post->password1 && $this->post->password2 && $this->post->password1 != $this->post->password2) $errors['password2'][] = $this->lang->convert->jira->passwordDifferent;
            if($errors) return $this->send(array('result' => 'fail', 'message' => $errors));

            $jiraUser['password'] = md5($this->post->password1);
            $jiraUser['group']    = $this->post->group;
            $jiraUser['mode']     = $this->post->mode;
            $this->session->set('jiraUser', $jiraUser);

            $stepStatus['user'] = 'done';
            $this->session->set('stepStatus', json_encode($stepStatus));

            return $this->send(array('result' => 'success', 'load' => inlink('importJira', "method={$method}")));
        }

        $jiraRelation  = $this->session->jiraRelation;
        $jiraRelation  = $jiraRelation ? json_decode($jiraRelation, true) : array();
        if(empty($jiraRelation)) $this->locate(inlink('index'));

        $stepList  = $this->convert->getJiraStepList($jiraRelation);
        $backSteps = $this->getBackKey($stepList, 'user');

        $this->view->title      = $this->lang->convert->jira->initJiraUser;
        $this->view->method     = $method;
        $this->view->dbName     = $dbName;
        $this->view->step       = 'user';
        $this->view->stepList   = $stepList;
        $this->view->stepStatus = $stepStatus;
        $this->view->backUrl    = $backSteps ? inlink('mapJira2Zentao', "method={$method}&dbName={$dbName}&step={$backSteps}") : '';
        $this->view->groups     = $this->loadModel('group')->getPairs();
        $this->display();
    }

    /**
     * 导入jira数据。
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
    public function importJira(string $method = 'db', string $mode = 'show', string $type = 'user', int $lastID = 0, bool $createTable = false)
    {
        set_time_limit(0);

        if($mode == 'import')
        {
            $result = $this->convert->importJiraData($type, $lastID, $createTable);
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

        $jiraRelation = $this->session->jiraRelation;
        $jiraRelation = $jiraRelation ? json_decode($jiraRelation, true) : array();
        if(empty($jiraRelation)) $this->locate(inlink('index'));

        $stepStatus = $this->session->stepStatus;
        $stepStatus = $stepStatus ? json_decode($stepStatus, true) : array();

        $stepList  = $this->convert->getJiraStepList($jiraRelation);
        $backSteps = $this->getBackKey($stepList, 'user');

        $this->view->title      = $this->lang->convert->jira->importJira;
        $this->view->method     = $method;
        $this->view->dbName     = $this->session->jiraDB;
        $this->view->step       = 'confirme';
        $this->view->stepList   = $stepList;
        $this->view->stepStatus = $stepStatus;
        $this->view->backUrl    = $backSteps ? inlink('initJiraUser', "method={$method}&dbName={$this->session->jiraDB}") : '';
        $this->display();
    }
}
