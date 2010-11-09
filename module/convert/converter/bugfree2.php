<?php
/**
 * The model file of bugfree2 convert of ZenTaoMS.
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
    /* 执行转换。*/
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

    /* 设置表名。*/
    public function setTable()
    {
        $dbPrefix = $this->post->dbPrefix;
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

    /* 转换用户。*/
    public function convertUser()
    {
        /* 获得所有的用户列表。*/
        $users = $this->dao
            ->dbh($this->sourceDBH)
            ->select("username AS account, userpassword AS password, realname, email, isDroped AS deleted")
            ->from(BUGFREE_TABLE_USER)
            ->orderBy('userID ASC')
            ->fetchAll('account', $autoCompany = false);

        /* 导入到zentao数据库中。*/
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

    /* 转换用户分组。*/
    public function convertGroup()
    {
        $groups = $this->dao->dbh($this->sourceDBH)
            ->select("groupID AS id, groupName AS name, groupUser AS users")
            ->from(BUGFREE_TABLE_GROUP)
            ->fetchAll('id', $autoCompany = false);
        foreach($groups as $groupID => $group)
        {
            /* 处理group数据。*/
            if($group->name == '[All Users]') continue;
            $groupUsers = explode(',', $group->users);
            unset($group->id);
            unset($group->users);

            /* 插入到group表。*/
            $this->dao->dbh($this->dbh)->insert(TABLE_GROUP)->data($group)->exec();
            $zentaoGroupID = $this->dao->lastInsertId();

            /* 插入到userGroup表。*/
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

    /* 转换项目为产品。*/
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

    /* 转换原来的模块为Bug视图模块。*/
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

        /* 更新parent。*/
        foreach($modules as $oldModuleID => $module)
        {
            $newModuleID = $this->map['module'][$oldModuleID];
            $newParentID = $this->map['module'][$module->parent];
            $this->dao->dbh($this->dbh)->update(TABLE_MODULE)->set('parent')->eq($newParentID)->where('id')->eq($newModuleID)->exec();
        }
        return count($modules);
    }

    /* 转换Bug。*/
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
            /* 修正Bug数据。*/
            $bugID = (int)$bugID;
            unset($bug->id);

            if($bug->assignedTo == 'Closed') $bug->assignedTo = 'closed';
            if($bug->assignedTo == 'Active') $bug->assignedTo = '';

            $bug->type   = strtolower($bug->type);
            $bug->found  = strtolower($bug->found);
            $bug->status = strtolower($bug->status);
            $bug->os     = strtolower($bug->os);
            $bug->browser= strtolower($bug->browser);

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

        /* 更新duplicateBug。 */
        foreach($this->map['bug'] as $oldBugID => $newBugID)
        {
            $this->dao->dbh($this->dbh)->update(TABLE_BUG)->set('duplicateBug')->eq($newBugID)->where('duplicateBug')->eq($oldBugID)->exec();
        }
        return count($bugs);
    }

    /* 转换case。*/
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
            /* 修正case的数据。*/
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
            
            /* 将产品和模块替换成禅道系统中的id。*/
            $case->product = $this->map['product'][$case->product];
            $case->module  = $this->map['module'][$case->module];

            /* 插入到case表中。*/
            $this->dao->dbh($this->dbh)->insert(TABLE_CASE)->data($case)->exec();
            $zentaoCaseID = $this->dao->lastInsertID();
            $this->map['case'][$caseID] = $zentaoCaseID;

            /* 用例步骤表。*/
            $caseStep->case    = $zentaoCaseID;
            $caseStep->version = 1;
            $caseStep->desc    = $step;
            $this->dao->dbh($this->dbh)->insert(TABLE_CASESTEP)->data($caseStep)->exec();

            /* 更新相关bug。*/
            foreach($bugs as $bugID)
            {
                if(!isset($this->map['bug'][$bugID])) continue;
                $zentaoBugID = $this->map['bug'][$bugID];
                $this->dao->dbh($this->dbh)->update(TABLE_BUG)->set('`case`')->eq($zentaoCaseID)->where('id')->eq($zentaoBugID)->limit(1)->exec();
            }
        }
        return count($cases);
    }

    /* 转换测试执行结果。*/
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

            /* 记录对应的bug信息。*/
            $bugID = (int)$result->bugID;
            $zentaoBugID = $this->map['bug'][$bugID];
            unset($result->bugID);

            /* 插入到testResult表中。*/
            $this->dao->dbh($this->dbh)->insert(TABLE_TESTRESULT)->data($result)->exec();
            $zentaoResultID = $this->dao->lastInsertId();
            $this->map['result'][$resultID] = $zentaoResultID;

            /* 更新bug表中的result字段。*/
            $this->dao->dbh($this->dbh)->update(TABLE_BUG)->set('result')->eq($zentaoResultID)->where('id')->eq($zentaoBugID)->limit(1)->exec();
        }
        return count($results);
    }

    /* 转换历史记录。*/
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

    /* 转换历史修改记录。*/
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

    /* 转换附件。*/
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
            /* 查找对应的action信息，以获得文件的相关字段。*/
            $zentaoActionID = $this->map['action'][$file->actionID];
            $zentaoAction   = $this->dao->dbh($this->dbh)->findById($zentaoActionID)->from(TABLE_ACTION)->fetch();
            $file->objectType = $zentaoAction->objectType;
            $file->objectID   = $zentaoAction->objectID;
            $file->addedBy    = $zentaoAction->actor;
            $file->addedDate  = $zentaoAction->date;
            unset($file->actionID);

            /* 处理文件大小。*/
            if(strpos($file->size, 'KB')) $file->size = (int)(str_replace('KB', '', $file->size) * 1024); 
            if(strpos($file->size, 'MB')) $file->size = (int)(str_replace('MB', '', $file->size) * 1024 * 1024); 

            /* 插入到数据库。*/
            $this->dao->dbh($this->dbh)->insert(TABLE_FILE)->data($file)->exec();

            /* 拷贝文件。*/
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

    /* 清空导入之后的数据。*/
    public function clear()
    {
        foreach($this->session->state as $table => $maxID)
        {
            $this->dao->dbh($this->dbh)->delete()->from($table)->where('id')->gt($maxID)->exec();
        }
    }
}
