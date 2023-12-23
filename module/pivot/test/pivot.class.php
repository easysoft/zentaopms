<?php
declare(strict_types=1);
class pivotTest
{
    private $objectModel;

    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('pivot');
        $this->initPivot();
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
        $tables = $this->loadModel('chart')->getTables($sql);
        $sql    = $tables['sql'];
        $fields = json_decode(json_encode($pivot->fieldSettings), true);
        $langs  = json_decode($pivot->langs, true) ?? array();

        return array($pivot, $sql, $filterFormat,$fields, $langs);
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
