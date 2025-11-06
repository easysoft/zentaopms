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
}
