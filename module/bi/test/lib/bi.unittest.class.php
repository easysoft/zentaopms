<?php
declare(strict_types=1);
class biTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('bi');
    }

    /**
     * Parse sql test
     *
     * @param  string    $sql
     * @access public
     * @return array
     */
    public function parseSqlTest($sql)
    {
        $columns = $this->objectModel->parseSql($sql);

        $result = array();
        foreach($columns as $field => $column)
        {
            if(empty($column['table'])) continue;

            $result[$field] = "{$column['table']['originTable']}=>{$column['table']['column']}";
        }

        arsort($result);

        return $result;
    }

    /**
     * get expression test.
     *
     * @param  string    $table
     * @param  string    $column
     * @param  string    $alias
     * @param  string    $function
     * @access public
     * @return string
     */
    public function getExpressionTest($table = null, $column = null, $alias = null, $function = null)
    {
        $expression = $this->objectModel->getExpression($table, $column, $alias, $function);
        return $expression->build($expression);
    }

    /**
     * get condition test.
     *
     * @param  mixed  $tableA
     * @param  mixed  $columnA
     * @param  string $operator
     * @param  mixed  $tableB
     * @param  mixed  $columnB
     * @param  int    $group
     * @access public
     * @return string
     */
    public function getConditionTest(mixed $tableA = null, mixed $columnA = null, string $operator = '', mixed $tableB = null, mixed $columnB = null, int $group = 1): string
    {
        $condition = $this->objectModel->getCondition($tableA, $columnA, $operator, $tableB, $columnB, $group);
        return $condition->build($condition);
    }

    /**
     * get columns native type
     *
     * @param  string $sql
     * @access public
     * @return array
     */
    public function getColumns($sql)
    {
        $columns = $this->objectModel->getColumns($sql, 'mysql');

        $nativeTypes = array();
        foreach($columns as $field => $fieldInfo)
        {
            $nativeTypes[$field] = $fieldInfo['native_type'];
        }

        return $nativeTypes;
    }

    /**
     * get tables and fields
     *
     * @param  string $sql
     * @access public
     * @return array
     */
    public function getTableAndFields($sql)
    {
        $tableAndFields = $this->objectModel->getTableAndFields($sql);
        return $tableAndFields;
    }
}
