<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class customZenTest extends baseTest
{
    protected $moduleName = 'custom';
    protected $className  = 'zen';

    /**
     * Test assignFieldListForSet method.
     *
     * @param  string $module
     * @param  string $field
     * @param  string $lang
     * @param  string $currentLang
     * @access public
     * @return object
     */
    public function assignFieldListForSetTest(string $module = 'story', string $field = 'priList', string $lang = '', string $currentLang = ''): object
    {
        $this->invokeArgs('assignFieldListForSet', array($module, $field, $lang, $currentLang));
        if(dao::isError()) return (object)dao::getError();

        $result = new stdclass();
        $result->fieldListCount = is_array($this->instance->view->fieldList ?? null) ? count($this->instance->view->fieldList) : 0;
        $result->dbFieldsCount  = is_array($this->instance->view->dbFields ?? null) ? count($this->instance->view->dbFields) : 0;
        $result->lang2Set       = $this->instance->view->lang2Set ?? '';
        $result->fieldListType  = is_array($this->instance->view->fieldList ?? null) ? 'array' : 'notArray';
        $result->dbFieldsType   = is_array($this->instance->view->dbFields ?? null) ? 'array' : 'notArray';

        return $result;
    }

    /**
     * Test assignVarsForSet method.
     *
     * @param  string $module
     * @param  string $field
     * @param  string $lang
     * @param  string $currentLang
     * @access public
     * @return object
     */
    public function assignVarsForSetTest(string $module = 'story', string $field = 'priList', string $lang = '', string $currentLang = ''): object
    {
        $this->invokeArgs('assignVarsForSet', array($module, $field, $lang, $currentLang));
        if(dao::isError()) return (object)dao::getError();

        $result = new stdclass();
        $result->fieldListCount = is_array($this->instance->view->fieldList ?? null) ? count($this->instance->view->fieldList) : 0;
        $result->dbFieldsCount  = is_array($this->instance->view->dbFields ?? null) ? count($this->instance->view->dbFields) : 0;
        $result->lang2Set       = $this->instance->view->lang2Set ?? '';

        $viewVars = array('unitList', 'defaultCurrency', 'reviewRule', 'users', 'superReviewers',
                          'gradeRule', 'storyGrades', 'needReview', 'forceReview', 'forceNotReview',
                          'longlife', 'blockPairs', 'closedBlock', 'showDeleted');

        foreach($viewVars as $var)
        {
            $result->{'has' . ucfirst($var)} = isset($this->instance->view->$var);
        }

        return $result;
    }
}
