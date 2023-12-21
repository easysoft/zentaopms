<?php
declare(strict_types=1);
class upgradeTest
{
    private $objectModel;

    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('upgrade');
    }

    /**
     * 测试获取升级版本。
     * Test get update version.
     *
     * @param  string $openVersion
     * @param  string $fromEdition
     * @access public
     * @return string
     */
    public function getVersionsToUpdateTest(string $openVersion, string $fromEdition): string
    {
        $versions = $this->objectModel->getVersionsToUpdate($openVersion, $fromEdition);
        $return   = '';
        foreach($versions[$openVersion] as $edition => $version)
        {
            if(!isset($version[0])) $version[0] = '0';
            $return .="{$edition}:{$version[0]};";
        }
        return trim($return, ';');
    }

    /**
     * __call魔术方法，如果比较简单的方法可以直接调用，不需要单独写方法。
     * __call magic method, if the method is simple, you can call it directly, no need to write a method.
     *
     * @param  string $method
     * @param  array  $args
     * @access public
     * @return mixed
     */
    public function __call(string $method, array $args): mixed
    {
        return call_user_func_array(array($this->objectModel, $method), $args);
    }

    /**
     * 测试通过版本号获取产品版本类型。
     * Test get edition by version.
     *
     * @param  string $version
     * @access public
     * @return string
     */
    public function getEditionByVersionTest(string $version): string
    {
        return $this->objectModel->getEditionByVersion($version);
    }

    /**
     * 测试获取开源版版本。
     * Test get open version.
     *
     * @param  string $version
     * @access public
     * @return string
     */
    public function getOpenVersionTest(string $version): string
    {
        return $this->objectModel->getOpenVersion($version);
    }

    /**
     * 测试打开UR开关。
     * Test set UR switch status.
     *
     * @param  string $version
     * @access public
     * @return bool
     */
    public function setURSwitchStatusTest(string $version): bool
    {
        $this->objectModel->fromVersion = $version;
        return $this->objectModel->setURSwitchStatus();
    }

    /**
     * 测试删除临时 model 文件。
     * Test delete tmp model files.
     *
     * @access public
     * @return int
     */
    public function deleteTmpModelTest(): int
    {
        $this->objectModel->deleteTmpModel();
        global $tester;
        return count(glob($tester->app->getTmpRoot() . 'model/*.php'));
    }

    /**
     * 测试处理devOps上线步骤的历史记录。
     * Test process deploy step action.
     *
     * @param  int deployStepID
     * @access public
     * @return object|false
     */
    public function processDeployStepActionTest(int $deployStepID): object|false
    {
        $this->objectModel->processDeployStepAction();

        global $tester;
        return $tester->dao->select('*')->from(TABLE_ACTION)->where('objectID')->eq($deployStepID)->andWhere('objectType')->eq('deploystep')->fetch();
    }

    /**
     * 测试删除补丁记录。
     * Test delete patch records.
     *
     * @access public
     * @return int
     */
    public function deletePatchTest(): int
    {
        $this->objectModel->deletePatch();
        global $tester;
        return $tester->dao->select('count(1) as count')->from(TABLE_EXTENSION)->where('type')->eq('patch')->orWhere('code')->in('zentaopatch,patch')->fetch('count');
    }

    /**
     * 测试获取升级 sql 文件路径。
     * Test get the upgrade sql file.
     *
     * @param  string $version
     * @access public
     * @return string
     */
    public function getUpgradeFileTest(string $version): string
    {
        $filepath = $this->objectModel->getUpgradeFile($version);
        global $tester;
        return str_replace($tester->app->getAppRoot() . 'db' . DS, '', $filepath);
    }

    /**
     * 测试获取项目集下的项目键值对。
     * Test get the project of the program it belongs to.
     *
     * @param  int    $programID
     * @access public
     * @return string
     */
    public function getProjectPairsByProgramTest(int $programID): string
    {
        $projects = $this->objectModel->getProjectPairsByProgram($programID);
        $return = '';
        foreach($projects as $projectID => $projectName) $return .= "{$projectID}:{$projectName};";
        return trim($return, ';');
    }

    /**
     * 测试将 sql 文件解析为 sql 语句。
     * Test parse sql file to sqls.
     *
     * @param  string $version
     * @access public
     * @return string
     */
    public function parseToSqlsTest(string $version): string
    {
        $sqlFile = $this->objectModel->getUpgradeFile($version);
        $sqls    = $this->objectModel->parseToSqls($sqlFile);
        return substr($sqls[0], 0, 30);
    }

    /**
     * 测试将 sql 文件解析为 sql 语句。
     * Test parse sql file to sqls.
     *
     * @access public
     * @return string
     */
    public function addORPrivTest(): string
    {
        $this->objectModel->addORPriv();

        global $tester;
        $groups     = $tester->dao->select('count(1) as count')->from(TABLE_GROUP)->fetch('count');
        $groupPrivs = $tester->dao->select('count(1) as count')->from(TABLE_GROUPPRIV)->fetch('count');
        return "groups:{$groups},groupPrivs:{$groupPrivs}";
    }

    /**
     * 测试判断是否出现错误。
     * Test judge any error occurs.
     *
     * @param  array  $errors
     * @access public
     * @return bool
     */
    public function isErrorTest(array $errors): bool
    {
        $this->objectModel::$errors = $errors;
        return $this->objectModel->isError();
    }

    /**
     * 测试获取升级时的错误。
     * Test get errors during the upgrading.
     *
     * @param  array  $errors
     * @access public
     * @return string
     */
    public function getErrorTest(array $errors): string
    {
        $this->objectModel::$errors = $errors;
        $return = $this->objectModel->getError();
        return implode(',', $return);
    }

    /**
     * 测试检查流程。
     * Test check weither process or not.
     *
     * @param  string $installedVersion
     * @param  string $systemMode
     * @access public
     * @return string
     */
    public function checkProcessTest(string $installedVersion, string $systemMode): string
    {
        global $tester;
        $tester->config->installedVersion = $installedVersion;
        $tester->config->systemMode       = $systemMode;
        $process = $this->objectModel->checkProcess();
        $return  = '';
        foreach($process as $key => $value) $return .= "{$key}:{$value};";
        return trim($return, ';');
    }

    /**
     * 测试更新BI相关zt_pivot表的sql字段。
     * Update BI SQL for 18.4.stable new function: dataview model.php checkUniColumn().
     *
     * @param  int    $pivotID
     * @param  string $errorValue
     * @param  string $rightValue
     * @param  string $testField
     * @access public
     * @return bool
     */
    public function updateBISQLTest(int $pivotID, string $errorValue, string $rightValue, string $testField): bool
    {
        global $tester;

        /* Init pivot table data. */
        $pivotSqlFile = $tester->app->getAppRoot() . 'test' . DS . 'data' . DS . 'pivot.sql';
        $pivotSQL     = explode(";", file_get_contents($pivotSqlFile));
        $tester->dao->delete()->from(TABLE_PIVOT)->exec();
        $tester->dbh->exec($pivotSQL[1]);

        $tester->dao->update(TABLE_PIVOT)->set('sql')->eq($errorValue)->where('id')->eq($pivotID)->exec();
        $this->objectModel->updateBISQL();

        $pivot = $tester->dao->select('*')->from(TABLE_PIVOT)->where('id')->eq($pivotID)->fetch();
        return $pivot->$testField == $rightValue;
    }

    /**
     * 测试初始化用户视图。
     * Test initialize user view.
     *
     * @access public
     * @return int
     */
    public function initUserViewTest(): int
    {
        $this->objectModel->initUserView();
        global $tester;
        return $tester->dao->select('count(1) as count')->from(TABLE_USERVIEW)->fetch('count');
    }

    /**
     * 测试存储日志。
     * Test save logs.
     *
     * @param  string $log
     * @access public
     * @return string
     */
    public function saveLogsTest(string $log): string
    {
        $this->objectModel->saveLogs($log);
        $logFile = $this->getLogFile();
        $logContent = file_get_contents($logFile);
        $logContent = explode("\n", $logContent);
        array_pop($logContent);
        return substr(array_pop($logContent), 20, 30);
    }

    /**
     * 测试设置项目集默认权限。
     * Test set program default priv.
     *
     * @access public
     * @return bool
     */
    public function setDefaultPrivTest(): bool
    {
        $this->objectModel->setDefaultPriv();
        return true;
    }

    /**
     * 测试更新透视表的阶段。
     * Test update pivot stage.
     *
     * @param  array $changeFields
     * @access public
     * @return void
     */
    public function updatePivotStageTest(array $changeFields)
    {
        global $tester;

        /* Init pivot table data. */
        $pivotSqlFile = $tester->app->getAppRoot() . 'test' . DS . 'data' . DS . 'pivot.sql';
        $pivotSQL     = explode(";", file_get_contents($pivotSqlFile));
        $tester->dao->delete()->from(TABLE_PIVOT)->exec();
        $tester->dbh->exec($pivotSQL[1]);

        $tester->dao->update(TABLE_PIVOT)->data($changeFields)->where('id')->eq($changeFields['id'])->exec();

        $this->objectModel->updatePivotStage();

        $afterUpdatePivot = $tester->dao->select('*')->from(TABLE_PIVOT)->where('id')->eq($changeFields['id'])->fetch();
        return $afterUpdatePivot;
    }

    /**
     * 测试获取自定义的模块。
     * Test get custom modules.
     *
     * @param  array  $allModules
     * @access public
     * @return string
     */
    public function getCustomModulesTest(array $allModules): string
    {
        $return = $this->objectModel->getCustomModules($allModules);
        return implode(',', $return);
    }

    /**
     * 测试为用户故事添加创建动作。
     * Test add create action for story.
     *
     * @param  string $version
     * @access public
     * @return void
     */
    public function addCreateAction4StoryTest(string $version): void
    {
        $this->objectModel->fromVersion = $version;
        $this->objectModel->addCreateAction4Story();
    }

    /**
     * 测试更新透视表中字段的类型。
     * Test update pivot fields type.
     *
     * @access public
     * @return bool
     */
    public function updatePivotFieldsTypeTest(int $pivotID, array $changeFields, array $rightValue): bool
    {
        global $tester;

        /* Init pivot table data. */
        $pivotSqlFile = $tester->app->getAppRoot() . 'test' . DS . 'data' . DS . 'pivot.sql';
        $pivotSQL     = explode(";", file_get_contents($pivotSqlFile));
        $tester->dao->delete()->from(TABLE_PIVOT)->exec();
        $tester->dbh->exec($pivotSQL[1]);

        $tester->dao->update(TABLE_PIVOT)->data($changeFields)->where('id')->eq($pivotID)->exec();

        $this->objectModel->updatePivotFieldsType();

        $afterUpdatePivot = $tester->dao->select('*')->from(TABLE_PIVOT)->where('id')->eq($pivotID)->fetch();

        $result = $afterUpdatePivot->fields == $rightValue[$pivotID]['fields'];
        if(isset($rightValue[$pivotID]['langs'])) $result = $afterUpdatePivot->langs == $rightValue[$pivotID]['langs'];
        return  $result;
    }

    /**
     * 测试为项目创建项目主库。
     * Test create doc lib for project.
     *
     * @param  object  $project
     * @access public
     * @return object
     */
    public function createProjectDocLibTest(int $projectID): object
    {
         global $tester;
         $project = $tester->loadModel('project')->getByID($projectID);
         $this->objectModel->createProjectDocLib($project);

         return $tester->dao->select('*')->from(TABLE_DOCLIB)->where('project')->eq($projectID)->fetch();
    }

    /**
     * 测试更新在编辑器中的文件 objectID。
     * Test update file objectID in editor.
     *
     * @param  string $type
     * @param  int    $lastID
     * @access public
     * @return string
     */
    public function updateFileObjectIDTest(string $type, int $lastID): string
    {
        global $tester;
        $tester->dao->update(TABLE_FILE)->set('extra')->eq('editor')->exec();

        $result = $this->objectModel->updateFileObjectID($type, $lastID);

        $return = '';
        foreach($result as $key => $value) $return .= "{$key}:{$value},";
        $files = $tester->dao->select('id')->from(TABLE_FILE)->where('extra')->eq('editor')->beginIF($type != '' && $type != 'comment')->andWhere('objectType')->eq($type)->fi()->fetchPairs();
        return $return . 'files:' . implode(',', $files);
    }

    /**
     * 测试更新文件对象。
     * Test update file objects.
     *
     * @param  string $type
     * @param  int    $lastID
     * @param  int    $limit
     * @access public
     * @return string
     */
    public function updateFileObjectsTest(string $type, int $lastID, int $limit): string
    {
        global $tester;
        $tester->dao->update(TABLE_FILE)->set('extra')->eq('editor')->exec();

        list($objectCount, $lastID) = $this->objectModel->updateFileObjects($type, $lastID, $limit);

        $files = $tester->dao->select('id')->from(TABLE_FILE)->where('objectType')->eq($type)->andWhere('extra')->eq('editor')->fetchPairs();

        return "count:{$objectCount},lastID:{$lastID},fileID:" . implode(',', $files);
    }

    /**
     * 测试获取没有合并的产品的数量。
     * Get no merged product count test.
     *
     * @access public
     * @return int
     */
    public function getNoMergedProductCountTest(): int
    {
        return $this->objectModel->getNoMergedProductCount();
    }

    /**
     * 测试计算项目、产品、迭代的成员。
     * Test compute project, product and sprint members.
     *
     * @param  bool   $execute
     * @param  int    $objectID
     * @param  string $objectType
     * @access public
     * @return string
     */
    public function computeObjectMembersTest(bool $execute = false, int $objectID = 0, string $objectType = ''): string
    {
        if($execute) $this->objectModel->computeObjectMembers();
        $return = '';
        if($objectID && $objectType)
        {
            global $tester;
            $acls  = $tester->dao->select('account')->from(TABLE_ACL)->where('objectType')->eq($objectType)->andWhere('objectID')->eq($objectID)->fetchPairs();
            $teams = $tester->dao->select('account')->from(TABLE_TEAM)->where('type')->eq($objectType)->andWhere('root')->eq($objectID)->fetchPairs();
            $return = 'acls:' . implode(',', $acls) . ';' . 'teams:' . implode(',', $teams);
        }
        return $return;
    }

    /**
     * 测试获取没有合并的迭代的数量。
     * Get no merged sprint count test.
     *
     * @access public
     * @return int
     */
    public function getNoMergedSprintCountTest(): int
    {
        return $this->objectModel->getNoMergedSprintCount();
    }

    /**
     * 测试计算产品的 acl。
     * Test compute product acl.
     *
     * @param  array      $productIdList
     * @param  int        $programID
     * @param  int|object $lineID
     * @access public
     * @return string
     */
    public function computeProductAclTest(array $productIdList = array(), int $programID = 0, int|object $lineID = null): string
    {
        $this->objectModel->computeProductAcl($productIdList, $programID, $lineID);

        global $tester;
        $return = '';
        $products = $tester->dao->select('id,program,acl,line')->from(TABLE_PRODUCT)->where('id')->in($productIdList)->fetchAll();
        foreach($products as $product) $return .= "{$product->id}:{$product->program},{$product->acl},{$product->line}; ";
        return $return;
    }

    /**
     * 测试将历史的项目作为执行升级。
     * Test historical projects are upgraded by execution.
     *
     * @param  int    $programID
     * @access public
     * @return array
     */
    public function upgradeInExecutionModeTest(int $programID): array
    {
        $_POST['projectType'] = 'execution';

        $this->objectModel->upgradeInExecutionMode($programID);

        global $tester;
        return $tester->dao->select('*')->from(TABLE_PROJECT)->fetchAll('id');
    }

    /**
     * 测试创建一个项目。
     * Test create a project.
     *
     * @param  int           $programID
     * @param  object        $data
     * @access public
     * @return string|array
     */
    public function createProjectTest(int $programID = 0, object $data = null): string|array
    {
        $projectID = $this->objectModel->createProject($programID, $data);
        if(dao::isError()) return dao::getError();
        global $tester;
        $actionCount = $tester->dao->select('count(1) as count')->from(TABLE_ACTION)->where('objectType')->eq('project')->andWhere('objectID')->eq($projectID)->fetch('count');
        $project     = $tester->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch();
        $program     = $tester->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($programID)->fetch();
        return "project:{$project->id},{$project->team},{$project->end},{$project->acl}; program:{$program->begin},{$program->end}; actionCount:{$actionCount}";
    }

    /**
     * 测试计算开始和结束日期中间的工作日数。
     * Test compute delta between the begin and end date.
     *
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return int
     */
    public function computeDaysDeltaTest(string $begin, string $end): int
    {
        return $this->objectModel->computeDaysDelta($begin, $end);
    }

    /**
     * 测试将历史的项目作为香米升级。
     * Test historical projects are upgraded by project.
     *
     * @param  int    $programID
     * @param  string $fromMode
     * @access public
     * @return string
     */
    public function upgradeInProjectModeTest(int $programID, string $fromMode): string
    {
        $this->objectModel->upgradeInProjectMode($programID, $fromMode);

        global $tester;
        $return   = '';
        $projects = $tester->dao->select('id,parent,multiple')->from(TABLE_PROJECT)->where('type')->eq('project')->fetchAll();
        foreach($projects as $project) $return .= "{$project->id}:{$project->parent},{$project->multiple};";
        return trim($return, ';');
    }
}
