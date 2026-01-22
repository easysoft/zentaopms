<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class searchTaoTest extends baseTest
{
    protected $moduleName = 'search';
    protected $className  = 'tao';

    /**
     * Test appendFiles method.
     *
     * @param  object $object
     * @access public
     * @return object
     */
    public function appendFilesTest(object $object): object
    {
        $result = $this->invokeArgs('appendFiles', [$object]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkDocPriv method.
     *
     * @param  array  $results
     * @param  array  $objectIdList
     * @param  string $table
     * @access public
     * @return array
     */
    public function checkDocPrivTest(array $results, array $objectIdList, string $table): array
    {
        $result = $this->invokeArgs('checkDocPriv', [$results, $objectIdList, $table]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkExecutionPriv method.
     *
     * @param  array  $results
     * @param  array  $objectIdList
     * @param  string $executions
     * @access public
     * @return array
     */
    public function checkExecutionPrivTest(array $results, array $objectIdList, string $executions): array
    {
        $result = $this->invokeArgs('checkExecutionPriv', [$results, $objectIdList, $executions]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkFeedbackAndTicketPriv method.
     *
     * @param  string $objectType
     * @param  array  $results
     * @param  array  $objectIdList
     * @param  string $table
     * @access public
     * @return array
     */
    public function checkFeedbackAndTicketPrivTest(string $objectType, array $results, array $objectIdList, string $table): array
    {
        global $tester, $app;

        $searchTao = $this->getInstance($this->moduleName, $this->className);

        $mockFeedback = new class {
            public function getGrantProducts()
            {
                global $app;
                $products = array();
                $feedbackViews = $app->dbh->query("SELECT * FROM " . TABLE_FEEDBACKVIEW . " WHERE account = '{$app->user->account}'")->fetchAll();
                foreach($feedbackViews as $view)
                {
                    if(!empty($view->product)) $products[$view->product] = $view->product;
                }
                return $products;
            }
        };

        $searchTao->feedback = $mockFeedback;

        $result = $this->invokeArgs('checkFeedbackAndTicketPriv', [$objectType, $results, $objectIdList, $table], $this->moduleName, $this->className, $searchTao);

        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkObjectPriv method.
     *
     * @param  string $objectType
     * @param  string $table
     * @param  array  $results
     * @param  array  $objectIdList
     * @param  string $products
     * @param  string $executions
     * @access public
     * @return array
     */
    public function checkObjectPrivTest(string $objectType, string $table, array $results, array $objectIdList, string $products, string $executions): array
    {
        $result = $this->invokeArgs('checkObjectPriv', [$objectType, $table, $results, $objectIdList, $products, $executions]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkPriv method.
     *
     * @param  array $results
     * @param  array $objectPairs
     * @access public
     * @return array
     */
    public function checkPrivTest(array $results, array $objectPairs = array()): array
    {
        $result = $this->invokeArgs('checkPriv', [$results, $objectPairs]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkProductPriv method.
     *
     * @param  array  $results
     * @param  array  $objectIdList
     * @param  string $products
     * @access public
     * @return array
     */
    public function checkProductPrivTest(array $results, array $objectIdList, string $products): array
    {
        $result = $this->invokeArgs('checkProductPriv', [$results, $objectIdList, $products]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkProgramPriv method.
     *
     * @param  array $results
     * @param  array $objectIdList
     * @access public
     * @return array
     */
    public function checkProgramPrivTest(array $results, array $objectIdList): array
    {
        $result = $this->invokeArgs('checkProgramPriv', [$results, $objectIdList]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkProjectPriv method.
     *
     * @param  array $results
     * @param  array $objectIdList
     * @access public
     * @return array
     */
    public function checkProjectPrivTest(array $results, array $objectIdList): array
    {
        $result = $this->invokeArgs('checkProjectPriv', [$results, $objectIdList]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkRelatedObjectPriv method.
     *
     * @param  string $objectType
     * @param  string $table
     * @param  array  $results
     * @param  array  $objectIdList
     * @param  string $products
     * @param  string $executions
     * @access public
     * @return array
     */
    public function checkRelatedObjectPrivTest(string $objectType, string $table, array $results, array $objectIdList, string $products, string $executions): array
    {
        $result = $this->invokeArgs('checkRelatedObjectPriv', [$objectType, $table, $results, $objectIdList, $products, $executions]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkTestsuitePriv method.
     *
     * @param  array  $results
     * @param  array  $objectIdList
     * @param  string $table
     * @access public
     * @return array
     */
    public function checkTestsuitePrivTest(array $results, array $objectIdList, string $table): array
    {
        $result = $this->invokeArgs('checkTestsuitePriv', [$results, $objectIdList, $table]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkTodoPriv method.
     *
     * @param  array  $results
     * @param  array  $objectIdList
     * @param  string $table
     * @access public
     * @return array
     */
    public function checkTodoPrivTest(array $results, array $objectIdList, string $table): array
    {
        $result = $this->invokeArgs('checkTodoPriv', [$results, $objectIdList, $table]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test processDocRecord method.
     *
     * @param  object $record
     * @param  array  $objectList
     * @access public
     * @return object
     */
    public function processDocRecordTest(object $record, array $objectList): object
    {
        $result = $this->invokeArgs('processDocRecord', [$record, $objectList]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test processExecutionRecord method.
     *
     * @param  object $record
     * @param  array  $objectList
     * @access public
     * @return object
     */
    public function processExecutionRecordTest(object $record, array $objectList): object
    {
        $result = $this->invokeArgs('processExecutionRecord', [$record, $objectList]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test processProjectRecord method.
     *
     * @param  object $record
     * @param  array  $objectList
     * @access public
     * @return object
     */
    public function processProjectRecordTest(object $record, array $objectList): object
    {
        $result = $this->invokeArgs('processProjectRecord', [$record, $objectList]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test processRecord method.
     *
     * @param  object $record
     * @param  array  $objectList
     * @access public
     * @return object
     */
    public function processRecordTest(object $record, array $objectList): object
    {
        $result = $this->invokeArgs('processRecord', [$record, $objectList]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test processRiskRecord method.
     *
     * @param  object $record
     * @param  string $module
     * @param  array  $objectList
     * @access public
     * @return object
     */
    public function processRiskRecordTest(object $record, string $module, array $objectList): object
    {
        $result = $this->invokeArgs('processRiskRecord', [$record, $module, $objectList]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test processStoryRecord method.
     *
     * @param  object $record
     * @param  string $module
     * @param  array  $objectList
     * @access public
     * @return object
     */
    public function processStoryRecordTest(object $record, string $module, array $objectList): object
    {
        $result = $this->invokeArgs('processStoryRecord', [$record, $module, $objectList]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test processTaskRecord method.
     *
     * @param  object $record
     * @access public
     * @return object
     */
    public function processTaskRecordTest(object $record): object
    {
        $result = $this->invokeArgs('processTaskRecord', [$record]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test unify method.
     *
     * @param  string $string
     * @param  string $to
     * @access public
     * @return string
     */
    public function unifyTest(string $string, string $to = ','): string
    {
        $result = $this->invokeArgs('unify', [$string, $to]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * 获取变量参数数据的测试用例。
     * Get param values test.
     *
     * @param  array  $fields
     * @param  array  $params
     * @access public
     * @return array
     */
    public function getParamValuesTest(array $fields, array $params): array
    {
        $_SESSION['project'] = 0;

        return $this->instance->getParamValues('bug', $fields, $params);
    }

    /**
     * 获取查询语句的参数的测试用例。
     * Get sql params test.
     *
     * @param  string $keywords
     * @access public
     * @return array
     */
    public function getSqlParamsTest(string $keywords): array
    {
        return $this->instance->getSqlParams($keywords);
    }

    /**
     * Test getSummary method.
     *
     * @param  string $content
     * @param  string $words
     * @access public
     * @return string
     */
    public function getSummaryTest(string $content, string $words): string
    {
        // 使用反射访问私有方法
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('getSummary');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->instance, array($content, $words));
        if(function_exists('dao') && dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test initOldSession method.
     *
     * @param  string $module
     * @param  array  $fields
     * @param  array  $fieldParams
     * @param  bool   $clearSession
     * @access public
     * @return array
     */
    public function initOldSessionTest(string $module, array $fields, array $fieldParams, bool $clearSession = true): array
    {
        $formSessionName = $module . 'Form';

        // 根据参数决定是否清理session数据
        if($clearSession) {
            unset($_SESSION[$formSessionName]);
        }

        $this->instance->initOldSession($module, $fields, $fieldParams);

        if(dao::isError()) return dao::getError();

        return $_SESSION[$formSessionName] ?? array();
    }

    /**
     * Test markKeywords method directly.
     *
     * @param  string $content
     * @param  string $keywords
     * @access public
     * @return string
     */
    public function markKeywordsDirectTest(string $content, string $keywords): string
    {
        // 使用反射访问私有方法
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('markKeywords');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->instance, array($content, $keywords));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test processIssueRecord method.
     *
     * @param  object $record
     * @param  array  $objectList
     * @access public
     * @return object
     */
    public function processIssueRecordTest(object $record, array $objectList): object
    {
        // 使用反射访问私有方法
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('processIssueRecord');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->instance, array($record, $objectList));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试生成查询表单数据。
     * Process query form datas test.
     *
     * @param  array  $fieldParams
     * @param  string $field
     * @param  string $andOrName
     * @param  string $operatorName
     * @param  string $valueName
     * @access public
     * @return object
     */
    public function processQueryFormDatasTest(array $fieldParams, string $field, string $andOrName, string $operatorName, string $valueName): array
    {
        $_POST = $fieldParams;

        return $this->instance->processQueryFormDatas($fieldParams, $field, $andOrName, $operatorName, $valueName);
    }

    /**
     * 测试处理搜索结果。
     * Process results test.
     *
     * @param  array  $results
     * @param  array  $objectList
     * @param  string $words
     * @access public
     * @return array
     */
    public function processResultsTest(array $results, array $objectList, string $words): array
    {
        $result = $this->instance->processResults($results, $objectList, $words);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * 测试替换日期和用户变量。
     * Replace dynamic test.
     *
     * @param  string $query
     * @access public
     * @return string
     */
    public function replaceDynamicTest(string $query): string
    {
        $result = $this->invokeArgs('replaceDynamic', [$query]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * 测试设置搜索条件。
     * Set condition test.
     *
     * @param  string     $field
     * @param  string     $operator
     * @param  string|int $value
     * @access public
     * @return string
     */
    public function setConditionTest($field, $operator, $value)
    {
        $result = $this->invokeArgs('setCondition', [$field, $operator, $value]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * 测试设置搜索条件。
     * Set where test.
     *
     * @access public
     * @return string
     */
    public function setWhereTest(string $field, string $operator, string $value, string $andOr): string
    {
        $where = '';
        return $this->instance->setWhere($where, $field, $operator, $value, $andOr);
    }
}
