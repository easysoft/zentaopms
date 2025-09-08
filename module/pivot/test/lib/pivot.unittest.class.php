<?php
declare(strict_types=1);
class pivotTest
{
    private $objectModel;

    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('pivot');
        // $this->initPivot(); // 暂时注释掉初始化方法，避免数据库冲突
    }

    /**
     * 测试getByID。
     * Test getByID.
     *
     * @param  int         $id
     * @access public
     * @return object|bool
     */
    public function getByIDTest(int $id): object|bool
    {
        return $this->objectModel->getByID($id);
    }

    /**
     * 魔术方法，调用objectModel的方法。
     * Magic method, call objectModel method.
     *
     * @param  string $name
     * @param  array  $arguments
     * @access public
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        return call_user_func_array([$this->objectModel, $name], $arguments);
    }

    /**
     * 初始化透视表。
     * Init pivot table.
     *
     * @access public
     * @return void
     */
    public function initPivot()
    {
        global $tester,$app;
        $appPath = $app->getAppRoot();
        $sqlFile = $appPath . 'test/data/pivot.sql';
        $tester->dbh->exec(file_get_contents($sqlFile));
        $sqlFile = $appPath . 'test/data/screen.sql';
        $tester->dbh->exec(file_get_contents($sqlFile));
    }

    /**
     * 获取透视表配置相关信息。
     * Get pivot table config info.
     *
     * @param  int   $pivotID
     * @access public
     * @return array
     */
    public function getPivotSheetConfig(int $pivotID): array
    {
        $pivot = $this->objectModel->getByID($pivotID);

        list($sql, $filterFormat) = $this->objectModel->getFilterFormat($pivot->sql, $pivot->filters);
        $fields = json_decode(json_encode($pivot->fieldSettings), true);
        $langs  = json_decode($pivot->langs, true) ?? array();

        return array($pivot, $sql, $filterFormat,$fields, $langs);
    }

    /**
     * Test __construct method.
     *
     * @access public
     * @return array
     */
    public function __constructTest(): array
    {
        global $tester;
        
        // 创建一个新的pivot模型实例来测试构造函数
        $pivotModel = $tester->loadModel('pivot');
        
        $result = array();
        
        // 步骤1：验证对象类型
        $result['objectType'] = get_class($pivotModel);
        
        // 步骤2：验证父类初始化（检查父类属性）
        $result['parentInit'] = property_exists($pivotModel, 'app') && property_exists($pivotModel, 'dao');
        
        // 步骤3：验证BI DAO加载（检查dao属性）
        $result['biDAOLoaded'] = property_exists($pivotModel, 'dao') && is_object($pivotModel->dao);
        
        // 步骤4：验证bi模型加载（检查bi属性）
        $result['biModelLoaded'] = property_exists($pivotModel, 'bi') && is_object($pivotModel->bi);
        
        // 步骤5：验证实例完整性
        $result['instanceComplete'] = is_object($pivotModel) && 
                                     method_exists($pivotModel, 'getByID') &&
                                     method_exists($pivotModel, 'checkAccess');
        
        return $result;
    }

    /**
     * 测试 processGroupRows。
     * Test processGroupRows.
     *
     * @param  array  $columns
     * @param  string $sql
     * @param  array  $filterFormat
     * @param  array  $groups
     * @param  string $groupList
     * @param  array  $fieldS
     * @param  string $showColTotal
     * @param  array  $cols
     * @param  array  $langs
     * @access public
     * @return array
     */
    public function processGroupRowsTest(array $columns, string $sql, array $filterFormat, array $groups, string $groupList, array $fields, string $showColTotal, array &$cols, array $langs): array
    {
        return $this->objectModel->processGroupRows($columns, $sql, $filterFormat, $groups, $groupList, $fields, $showColTotal, $cols, $langs);
    }
}
