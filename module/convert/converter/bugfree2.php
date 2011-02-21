<?php
/**
 * The model file of bugfree2 convert of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class bugfree2ConvertModel extends bugfreeConvertModel
{
    /**
     * Execute the converter.
     * 
     * @access public
     * @return array
     */
    public function execute()
    {
        $this->clear();
        $this->setTable();
        $this->convertGroup();
        $result['users']    = $this->convertUser();
        $result['projects'] = $this->convertProject();
        $result['modules']  = $this->convertModule();
        $result['bugs']     = $this->convertBug();
        $result['cases']    = $this->convertCase();
        $result['results']  = $this->convertResult();
        $result['actions']  = $this->convertAction();
        $result['files']    = $this->convertFile();
        $this->dao->dbh($this->dbh);
        $this->loadModel('tree')->fixModulePath();
        return $result;
    }

    /**
     * Set table names. 
     * 
     * @access public
     * @return void
     */
    public function setTable()
    {
        $dbPrefix = $this->post->dbPrefix;
        define('BUGFREE_TABLE_OPTION',     $dbPrefix . 'TestOptions');
        define('BUGFREE_TABLE_USER',       $dbPrefix . 'TestUser');
        define('BUGFREE_TABLE_PROJECT',    $dbPrefix . 'TestProject');
        define('BUGFREE_TABLE_MODULE',     $dbPrefix . 'TestModule');
        define('BUGFREE_TABLE_BUGINFO',    $dbPrefix . 'BugInfo');
        define('BUGFREE_TABLE_CASEINFO',   $dbPrefix . 'CaseInfo');
        define('BUGFREE_TABLE_RESULTINFO', $dbPrefix . 'ResultInfo');
        define('BUGFREE_TABLE_ACTION',     $dbPrefix . 'TestAction');
        define('BUGFREE_TABLE_FILE',       $dbPrefix . 'TestFile');
        define('BUGFREE_TABLE_HISTORY',    $dbPrefix . 'TestHistory');
        define('BUGFREE_TABLE_GROUP',      $dbPrefix . 'TestGroup');
    }

    /**
     * Get the version of bugfree2.x.
     * 
     * @access public
     * @return int
     */
    public function getBugFreeVersion()
    {
        return $this->dao->dbh($this->sourceDBH)
            ->select("optionValue as version")->from(BUGFREE_TABLE_OPTION)
            ->where('OptionName')->eq('dbVersion')
            ->fetch('version', $autoCompany = false);

    }

    /**
     * Convert user.
     * 
     * @access public
     * @return int      converted user count
     */
    public function convertUser()
    {
        /* Get all user list. */
        $users = $this->dao
            ->dbh($this->sourceDBH)
            ->select("username AS account, userpassword AS password, realname, email, isDroped AS deleted")
            ->from(BUGFREE_TABLE_USER)
            ->orderBy('userID ASC')
            ->fetchAll('account', $autoCompany = false);

        /* Insert into zentao. */
        $convertCount = 0;
        foreach($users as $account => $user)
        {
            if(!$this->dao->dbh($this->dbh)->findByAccount($account)->from(TABLE_USER)->fetch('account'))
            {
                $this->dao->dbh($this->dbh)->insert(TABLE_USER)->data($user)->exec();
                $convertCount ++;
            }
            else
            {
                self::$info['users'][] = sprintf($this->lang->convert->errorUserExists, $account);
            }
        }
        return $convertCount;
    }

    /**
     * Convert groups.
     * 
     * @access public
     * @return void     converted group count.
     */
    public function convertGroup()
    {
        if(!$this->tableExists(BUGFREE_TABLE_GROUP)) return false;
        $groups = $this->dao->dbh($this->sourceDBH)
            ->select("groupID AS id, groupName AS name, groupUser AS users")
            ->from(BUGFREE_TABLE_GROUP)
            ->fetchAll('id', $autoCompany = false);
        foreach($groups as $groupID => $group)
        {
            /* Fix the group data. */
            if($group->name == '[All Users]') continue;
            $groupUsers = explode(',', $group->users);
            unset($group->id);
            unset($group->users);

            /* Insert into zentao's group table. */
            $this->dao->dbh($this->dbh)->insert(TABLE_GROUP)->data($group)->exec();
            $zentaoGroupID = $this->dao->lastInsertId();

            /* Insert into zentao's usergroup table. */
            foreach($groupUsers as $account)
            {
                if(empty($account)) continue;
                $this->dao->dbh($this->dbh)->insert(TABLE_USERGROUP)
                    ->set('`group`')->eq($zentaoGroupID)
                    ->set('account')->eq($account)
                    ->exec();
            }
        }
    }

    /**
     * Convert projects.
     * 
     * @access public
     * @return int      converted projects count.
     */
    public function convertProject()
    {
        $projects = $this->dao->dbh($this->sourceDBH)
            ->select("projectID AS id, projectName AS name, isDroped AS deleted")
            ->from(BUGFREE_TABLE_PROJECT)
            ->fetchAll('id', $autoComapny = false);
        foreach($projects as $projectID => $project)
        {
            unset($project->id);
            $this->dao->dbh($this->dbh)->insert(TABLE_PRODUCT)->data($project)->exec();
            $this->map['product'][$projectID] = $this->dao->lastInsertID();
        }
        return count($projects);
    }

    /**
     * Convert modules.
     * 
     * @access public
     * @return int      converted modules count.
     */
    public function convertModule()
    {
        $this->map['module'][0] = 0;
        $modules = $this->dao
            ->dbh($this->sourceDBH)
            ->select(
                'moduleID AS id, 
                moduleType as type,
                projectID AS root, 
                moduleName AS name, 
                moduleGrade AS grade, 
                parentID AS parent, 
                displayOrder AS `order`')
            ->from(BUGFREE_TABLE_MODULE)
            ->orderBy('id ASC')
            ->fetchAll('id', $autoCompany = false);
        foreach($modules as $moduleID => $module)
        {
            $module->root = $this->map['product'][$module->root];
            $module->type = strtolower($module->type);
            unset($module->id);
            $this->dao->dbh($this->dbh)->insert(TABLE_MODULE)->data($module)->exec();
            $this->map['module'][$moduleID] = $this->dao->lastInsertID();
        }

        /* Update parent. */
        foreach($modules as $oldModuleID => $module)
        {
            $newModuleID = $this->map['module'][$oldModuleID];
            $newParentID = $this->map['module'][$module->parent];
            $this->dao->dbh($this->dbh)->update(TABLE_MODULE)->set('parent')->eq($newParentID)->where('id')->eq($newModuleID)->exec();
        }
        return count($modules);
    }

    /**
     * Convert bugs.
     * 
     * @access public
     * @return int      converted bugs count.
     */
    public function convertBug()
    {
        $bugs = $this->dao
            ->dbh($this->sourceDBH)
            ->select('
            bugID AS id, 
            projectID AS product, 
            moduleID AS module,
            bugTitle AS title,
            bugSeverity AS severity,
            bugPriority AS pri,
            bugType AS type,
            bugOS AS os,
            bugBrowser AS browser, 
            bugMachine AS hardware,
            howFound   AS found, 
            reproSteps AS steps,
            bugStatus AS status,
            linkID    AS linkBug,
            duplicateID AS duplicateBug,
            caseID AS `case`,
            1      AS caseVersion,
            resultID AS result,
            mailto,
            openedBy, openedDate, openedBuild,
            assignedTo, assignedDate,
            resolvedBy, resolution, resolvedBuild, resolvedDate,
            closedBy, closedDate,
            lastEditedBy, lastEditedDate,
            bugKeyword AS keywords
            ')
            ->from(BUGFREE_TABLE_BUGINFO)
            ->where('isDroped')->eq(0)
            ->orderBy('bugID')
            ->fetchAll('id', $autoCompany = false);
        foreach($bugs as $bugID => $bug)
        {
            /* Fix some fileds of bug. */
            $bugID = (int)$bugID;
            unset($bug->id);

            if($bug->assignedTo == 'Closed') $bug->assignedTo = 'closed';
            if($bug->assignedTo == 'Active') $bug->assignedTo = '';

            $bug->type   = strtolower($bug->type);
            $bug->found  = strtolower($bug->found);
            $bug->status = strtolower($bug->status);
            $bug->os     = strtolower($bug->os);
            $bug->browser= strtolower($bug->browser);
            $bug->steps  = nl2br($bug->steps);

            if($bug->os == 'winvista')        $bug->os      = 'vista';
            if($bug->browser == 'firefox3.0') $bug->browser = 'firefox3';
            if($bug->browser == 'firefox2.0') $bug->browser = 'firefox2';
            if($bug->openedBuild == 'N/A')    $bug->openedBuild = '';
            if(!$bug->case) $bug->caseVersion = 0;

            $bug->resolution = str_replace(' ', '', strtolower($bug->resolution));
            $bug->product    = $this->map['product'][$bug->product];
            $bug->module     = $this->map['module'][$bug->module];
            $this->dao->dbh($this->dbh)->insert(TABLE_BUG)->data($bug)->exec();
            $this->map['bug'][$bugID] = $this->dao->lastInsertID();
        }

        /* Update duplicated bugs. */
        foreach($this->map['bug'] as $oldBugID => $newBugID)
        {
            $this->dao->dbh($this->dbh)->update(TABLE_BUG)->set('duplicateBug')->eq($newBugID)->where('duplicateBug')->eq($oldBugID)->exec();
        }
        return count($bugs);
    }

    /**
     * Convert cases.
     * 
     * @access public
     * @return int      converted cases count.
     */
    public function convertCase()
    {
        $cases = $this->dao
            ->dbh($this->sourceDBH)
            ->select('
            caseID AS id, 
            projectID AS product, 
            moduleID AS module,
            caseTitle AS title,
            caseSteps AS step,
            casePriority AS pri,
            caseType AS type,
            caseStatus AS status,
            caseMethod AS howRun,
            casePlan AS stage,
            openedBy, openedDate,
            lastEditedBy, lastEditedDate,
            scriptedBy, scriptedDate, scriptStatus, scriptLocation,
            linkID AS linkCase,
            casekeyword AS keywords,
            1 AS version,
            bugID
            ')
            ->from(BUGFREE_TABLE_CASEINFO)
            ->where('isDroped')->eq(0)
            ->orderBy('caseID')
            ->fetchAll('id', $autoCompany = false);
        foreach($cases as $caseID => $case)
        {
            /* Fix fields of case. */
            $caseID = (int)$caseID;
            $step   = $case->step;
            $bugs   = explode(',', $case->bugID);
            unset($case->id);
            unset($case->step);
            unset($case->bugID);

            $case->type   = strtolower($case->type);
            $case->status = strtolower($case->status);
            $case->howRun = strtolower($case->howRun);
            $case->stage  = strtolower($case->stage);

            if($case->type == 'configuration') $case->type   = 'config';
            if($case->type == 'setup')         $case->type   = 'install';
            if($case->type == 'functional')    $case->type   = 'feature';
            if($case->status == 'active')      $case->status = 'normal';
            
            /* Change product and module by zentao's product and module. */
            $case->product = $this->map['product'][$case->product];
            $case->module  = $this->map['module'][$case->module];

            /* Insert into case table. */
            $this->dao->dbh($this->dbh)->insert(TABLE_CASE)->data($case)->exec();
            $zentaoCaseID = $this->dao->lastInsertID();
            $this->map['case'][$caseID] = $zentaoCaseID;

            /* Insert into case step table. */
            $caseStep->case    = $zentaoCaseID;
            $caseStep->version = 1;
            $caseStep->desc    = $step;
            $this->dao->dbh($this->dbh)->insert(TABLE_CASESTEP)->data($caseStep)->exec();

            /* Update related bugs. */
            foreach($bugs as $bugID)
            {
                if(!isset($this->map['bug'][$bugID])) continue;
                $zentaoBugID = $this->map['bug'][$bugID];
                $this->dao->dbh($this->dbh)->update(TABLE_BUG)->set('`case`')->eq($zentaoCaseID)->where('id')->eq($zentaoBugID)->limit(1)->exec();
            }
        }
        return count($cases);
    }

    /**
     * Convert results.
     * 
     * @access public
     * @return int      converted results count.
     */
    public function convertResult()
    {
        $results = $this->dao->dbh($this->sourceDBH)
            ->select('
            resultID AS id,
            caseID AS `case`,
            resultValue AS caseResult,
            1 AS version,
            openedDate as date,
            bugID
            ')
            ->from(BUGFREE_TABLE_RESULTINFO)
            ->orderBy('id')
            ->fetchAll('id', $autoCompany = false);
        foreach($results as $resultID => $result)
        {
            unset($result->id);

            /* The bug id of zentao. */
            $bugID = (int)$result->bugID;
            $zentaoBugID = $this->map['bug'][$bugID];
            unset($result->bugID);

            /* Insert into test result table. */
            $this->dao->dbh($this->dbh)->insert(TABLE_TESTRESULT)->data($result)->exec();
            $zentaoResultID = $this->dao->lastInsertId();
            $this->map['result'][$resultID] = $zentaoResultID;

            /* Update result table. */
            $this->dao->dbh($this->dbh)->update(TABLE_BUG)->set('result')->eq($zentaoResultID)->where('id')->eq($zentaoBugID)->limit(1)->exec();
        }
        return count($results);
    }

    /**
     * Convert actions.
     * 
     * @access public
     * @return int      converted actions count.
     */
    public function convertAction()
    {
        $actions = $this->dao
            ->dbh($this->sourceDBH)
            ->select("actionID AS id,
                actionTarget AS objectType,
                idValue AS objectID,
                actionUser AS actor,
                actionType AS action,
                actionDate AS date,
                actionNote AS comment
                ")
            ->from(BUGFREE_TABLE_ACTION)
            ->where('actionTarget' != 'Result')
            ->orderBy('actionID')
            ->fetchAll('id', $autoComapny = false);

        foreach($actions as $actionID => $action)
        {
            $actionID = (int)$action->id;
            unset($action->id);
            $action->objectType = strtolower($action->objectType);
            $action->action     = strtolower($action->action);
            $action->objectID   = $this->map[$action->objectType][$action->objectID];

            $this->dao->dbh($this->dbh)->insert(TABLE_ACTION)->data($action)->exec();
            $this->map['action'][$actionID] = $this->dao->lastInsertID();
        }
        return count($actions);
    }

    /**
     * Convert histories.
     * 
     * @access public
     * @return int      the converted histories count.
     */
    public function convertHistory()
    {
        $histories = $this->dao->dbh($this->sourceDBH)
            ->select('actioID, actionField AS field, oldValue AS old, newValue AS new')
            ->from(BUGFREE_TABLE_HISTORY)
            ->orderBy('historyID')
            ->fetchAll('', $autoCompany = false);
        foreach($histories as $history)
        {
            $history->actionID = $this->map['action'][$history->actionID];
            $this->dao->dbh($this->dbh)->insert(TABLE_HISTORY)->data($history)->exec();
        }
    }

    /**
     * Convert attachments.
     * 
     * @access public
     * @return int      the converted files count.
     */
    public function convertFile()
    {
        $this->setPath();
        $files = $this->dao->dbh($this->sourceDBH)
            ->select("
                actionID,
                fileName AS pathname,
                fileTitle AS title,
                fileType AS extension,
                fileSize AS size
                ")
            ->from(BUGFREE_TABLE_FILE)
            ->orderBy('fileID')
            ->fetchAll('', $autoCompany = false);
        foreach($files as $file)
        {
            /* Get the actionID in zentao, to get file info. */
            $zentaoActionID = $this->map['action'][$file->actionID];
            $zentaoAction   = $this->dao->dbh($this->dbh)->findById($zentaoActionID)->from(TABLE_ACTION)->fetch();
            $file->objectType = $zentaoAction->objectType;
            $file->objectID   = $zentaoAction->objectID;
            $file->addedBy    = $zentaoAction->actor;
            $file->addedDate  = $zentaoAction->date;
            unset($file->actionID);

            /* Compute the file size. */
            if(strpos($file->size, 'KB')) $file->size = (int)(str_replace('KB', '', $file->size) * 1024); 
            if(strpos($file->size, 'MB')) $file->size = (int)(str_replace('MB', '', $file->size) * 1024 * 1024); 

            /* Insert into database. */
            $this->dao->dbh($this->dbh)->insert(TABLE_FILE)->data($file)->exec();

            /* Copy file. */
            $soureFile = $this->filePath . $file->pathname;
            if(!file_exists($soureFile))
            {
                self::$info['files'][] = sprintf($this->lang->convert->errorFileNotExits, $soureFile);
                continue;
            }
            $targetFile = $this->app->getAppRoot() . "www/data/upload/{$this->app->company->id}/" . $file->pathname;
            $targetPath = dirname($targetFile);
            if(!is_dir($targetPath)) mkdir($targetPath, 0777, true);
            if(!copy($soureFile, $targetFile))
            {
                self::$info['files'][] = sprintf($this->lang->convert->errorCopyFailed, $targetFile);
            }
        }
        return count($files);
    }

    /**
     * Clear the converted records.
     * 
     * @access public
     * @return void
     */
    public function clear()
    {
        foreach($this->session->state as $table => $maxID)
        {
            $this->dao->dbh($this->dbh)->delete()->from($table)->where('id')->gt($maxID)->exec();
        }
    }
}
