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
}
