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

    /**
     * Test checkDuplicateKeys method.
     *
     * @param  string $module
     * @param  string $field
     * @param  array  $keys
     * @access public
     * @return mixed
     */
    public function checkDuplicateKeysTest(string $module = 'story', string $field = 'priList', array $keys = array())
    {
        $_POST['keys'] = $keys;
        $result = $this->invokeArgs('checkDuplicateKeys', array($module, $field));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkEmptyKeys method.
     *
     * @param  string $module
     * @param  string $field
     * @param  string $lang
     * @param  array  $keys
     * @param  array  $values
     * @param  array  $systems
     * @access public
     * @return mixed
     */
    public function checkEmptyKeysTest(string $module = 'story', string $field = 'priList', string $lang = 'zh-cn', array $keys = array(), array $values = array(), array $systems = array())
    {
        $_POST['lang']    = $lang;
        $_POST['keys']    = $keys;
        $_POST['values']  = $values;
        $_POST['systems'] = $systems;
        $result = $this->invokeArgs('checkEmptyKeys', array($module, $field));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkInvalidKeys method.
     *
     * @param  string $module
     * @param  string $field
     * @param  string $lang
     * @param  array  $keys
     * @access public
     * @return mixed
     */
    public function checkInvalidKeysTest(string $module = 'story', string $field = 'priList', string $lang = 'zh-cn', array $keys = array())
    {
        $_POST['lang'] = $lang;
        $_POST['keys'] = $keys;
        $result = $this->invokeArgs('checkInvalidKeys', array($module, $field));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkKeysForSet method.
     *
     * @param  string $module
     * @param  string $field
     * @param  string $lang
     * @param  array  $keys
     * @param  array  $values
     * @param  array  $systems
     * @access public
     * @return mixed
     */
    public function checkKeysForSetTest(string $module = 'story', string $field = 'priList', string $lang = 'zh-cn', array $keys = array(), array $values = array(), array $systems = array())
    {
        $_POST['lang']    = $lang;
        $_POST['keys']    = $keys;
        $_POST['values']  = $values;
        $_POST['systems'] = $systems;
        $result = $this->invokeArgs('checkKeysForSet', array($module, $field));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test setFieldListForSet method.
     *
     * @param  string $module
     * @param  string $field
     * @param  array  $postData
     * @access public
     * @return mixed
     */
    public function setFieldListForSetTest(string $module = 'story', string $field = 'priList', array $postData = array())
    {
        $_POST = $postData;
        $result = $this->invokeArgs('setFieldListForSet', array($module, $field));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test setGradeRule method.
     *
     * @param  string $module
     * @param  array  $data
     * @access public
     * @return mixed
     */
    public function setGradeRuleTest(string $module = 'story', array $data = array())
    {
        $result = $this->invokeArgs('setGradeRule', array($module, $data));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test setStoryReview method.
     *
     * @param  string $module
     * @param  array  $data
     * @access public
     * @return mixed
     */
    public function setStoryReviewTest(string $module = 'story', array $data = array())
    {
        $result = $this->invokeArgs('setStoryReview', array($module, $data));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test setTestcaseReview method.
     *
     * @param  array  $data
     * @access public
     * @return mixed
     */
    public function setTestcaseReviewTest(array $data = array())
    {
        $result = $this->invokeArgs('setTestcaseReview', array($data));
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
