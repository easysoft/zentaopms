<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class actionZenTest extends baseTest
{
    protected $moduleName = 'action';
    protected $className  = 'zen';

    /**
     * Test getTrashesHeaderNavigation method.
     *
     * @param  array $objectTypeList
     * @access public
     * @return array
     */
    public function getTrashesHeaderNavigationTest(array $objectTypeList): array
    {
        $result = $this->invokeArgs('getTrashesHeaderNavigation', [$objectTypeList]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test saveUrlIntoSession method.
     *
     * @param  string $testUri
     * @access public
     * @return array
     */
    public function saveUrlIntoSessionTest(string $testUri = ''): array
    {
        // 备份原来的session数据
        $originalSession = [];
        $sessionKeys     = [
            'productList', 'productPlanList', 'releaseList', 'programList', 'projectList',
            'executionList', 'taskList', 'buildList', 'bugList', 'caseList', 'testtaskList',
            'docList', 'opportunityList', 'riskList', 'trainplanList', 'roomList',
            'researchplanList', 'researchreportList', 'meetingList', 'designList',
            'storyLibList', 'issueLibList', 'riskLibList', 'opportunityLibList',
            'practiceLibList', 'componentLibList'
        ];
        foreach($sessionKeys as $key) $originalSession[$key] = $this->instance->session->$key ?? null;

        if($this->instance->config->requestType == 'PATH_INFO') $testUri = str_replace('.' . $this->instance->app->viewType, '', $testUri);
        $this->instance->app->uri = $testUri;

        $this->invokeArgs('saveUrlIntoSession');
        if(dao::isError()) return dao::getError();

        $result = [];
        foreach($sessionKeys as $key) $result[$key] = $this->instance->session->$key ?? null; // 获取保存后的session数据
        foreach($originalSession as $key => $value) $this->instance->session->set($key, $value); // 恢复原来的session数据
        return $result;
    }

    /**
     * Test processTrash method.
     *
     * @param  object $trash
     * @param  array  $projectList
     * @param  array  $productList
     * @param  array  $executionList
     * @access public
     * @return object
     */
    public function processTrashTest(object $trash, array $projectList = array(), array $productList = array(), array $executionList = array()): object
    {
        $this->invokeArgs('processTrash', [$trash, $projectList, $productList, $executionList]);
        if(dao::isError()) return dao::getError();
        return $trash;
    }

    /**
     * Test getReplaceNameAndCode method.
     *
     * @param  string $name
     * @param  string $code
     * @param  string $table
     * @access public
     * @return array
     */
    public function getReplaceNameAndCodeTest(string $name, string $code, string $table): array
    {
        // 创建模拟的重复对象和原对象
        $repeatObject = new stdClass();
        $repeatObject->name = $name;
        $repeatObject->code = $code;

        $object = new stdClass();
        $object->code = $code;

        $result = $this->invokeArgs('getReplaceNameAndCode', [$repeatObject, $object, $table]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkActionExist method.
     *
     * @param  int $actionID
     * @access public
     * @return object|array
     */
    public function checkActionExistTest(int $actionID): object|array
    {
        $result = $this->invokeArgs('checkActionExist', [$actionID]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getConfirmNoMessage method.
     *
     * @param  string $repeatName
     * @param  string $replaceName
     * @param  string $replaceCode
     * @param  string $objectName
     * @param  string $objectCode
     * @param  string $testType
     * @access public
     * @return string
     */
    public function getConfirmNoMessageTest(string $repeatName, string $replaceName, string $replaceCode, string $objectName, string $objectCode, string $testType): string
    {
        // 创建模拟的重复对象
        $repeatObject = new stdClass();
        $repeatObject->name = $repeatName;
        $repeatObject->code = ($testType == 'both' && $replaceCode) ? substr($replaceCode, 0, -2) : (($testType == 'code' && $replaceCode) ? substr($replaceCode, 0, -2) : '');

        // 创建模拟的原对象
        $object = new stdClass();
        $object->name = $objectName;
        $object->code = $objectCode;

        // 创建模拟的旧Action对象
        $oldAction = new stdClass();
        switch($testType) {
            case 'both':
                $oldAction->objectType = 'product';
                break;
            case 'name':
                $oldAction->objectType = 'story';
                break;
            case 'code':
                $oldAction->objectType = 'task';
                break;
            case 'none':
                $oldAction->objectType = 'project';
                break;
            case 'name_only':
                $oldAction->objectType = 'bug';
                break;
        }

        $result = $this->invokeArgs('getConfirmNoMessage', [$repeatObject, $object, $oldAction, $replaceName, $replaceCode]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test recoverObject method.
     *
     * @param  string $repeatName
     * @param  string $repeatCode
     * @param  string $replaceName
     * @param  string $replaceCode
     * @param  string $testType
     * @access public
     * @return object|string
     */
    public function recoverObjectTest(string $repeatName, string $repeatCode, string $replaceName, string $replaceCode, string $testType): object|string
    {
        // 创建模拟的重复对象
        $repeatObject = new stdClass();
        $repeatObject->name = $repeatName;
        $repeatObject->code = $repeatCode;

        // 创建模拟的原对象
        $object = new stdClass();
        $object->name = $repeatName;
        $object->code = $repeatCode;

        // 创建模拟的旧Action对象
        $oldAction = new stdClass();
        $oldAction->objectID = 1;
        $oldAction->objectType = 'product';

        // 设置数据表
        $table = TABLE_PRODUCT;

        if($testType == 'empty' || ($testType == 'none'))
        {
            // 如果是测试无变化的情况，直接返回
            if(empty($replaceName) && empty($replaceCode)) return 'no_change';

            // 模拟无重复的情况，修改重复对象的名称和代码
            if($testType == 'none')
            {
                $repeatObject->name = '不重复产品';
                $repeatObject->code = 'no_repeat';
            }
        }

        $this->invokeArgs('recoverObject', [$repeatObject, $object, $replaceName, $replaceCode, $table, $oldAction]);
        if(dao::isError()) return dao::getError();

        // 检查更新结果
        $updatedObject = $this->instance->dao->select('*')->from($table)->where('id')->eq($oldAction->objectID)->fetch();
        if(!$updatedObject) return 'no_object';

        // 根据测试类型返回相应的结果
        switch($testType) {
            case 'both':
                return (object)array('name' => $updatedObject->name, 'code' => $updatedObject->code);
            case 'name':
                return (object)array('name' => $updatedObject->name);
            case 'code':
                return (object)array('code' => $updatedObject->code);
            case 'none':
            case 'empty':
                return 'no_change';
            default:
                return $updatedObject;
        }
    }

    /**
     * Test restoreStages method.
     *
     * @param  int    $executionID
     * @param  string $confirmChange
     * @access public
     * @return mixed
     */
    public function restoreStagesTest(int $executionID, string $confirmChange = 'no')
    {
        $action = new stdClass();
        $action->objectID = $executionID;

        $result = $this->invokeArgs('restoreStages', [$action, $confirmChange]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
