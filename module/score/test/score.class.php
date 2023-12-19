<?php
class scoreTest
{
    public function __construct(string $account = 'admin')
    {
        global $tester, $app;
        $this->objectModel = $tester->loadModel('score');

        su($account);

        $app->rawModule = 'score';
        $app->rawMethod = 'index';
        $app->setModuleName('score');
        $app->setMethodName('index');
    }

    /**
     * Get user score list.
     *
     * @param string $account
     * @param object $pager
     * @param bool   $needCount
     *
     * @access public
     * @return array|int
     */
    public function getListByAccountTest($account, $pager, $needCount = false)
    {
        $objects = $this->objectModel->getListByAccount($account, $pager);

        if(dao::isError()) return dao::getError();

        return $needCount ? count($objects) : $objects;
    }

    /**
     * Add score logs.
     *
     * @param string $module
     * @param string $method
     * @param string $param
     * @param string $account
     * @param string $time
     *
     * @access public
     * @return string|object
     */
    public function createTest($module = '', $method = '', $param = '', $account = '', $time = '')
    {
        global $tester;

        $object = $this->objectModel->create($module, $method, $param, $account, $time);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            $object = (!empty((array) $object) and is_object($object)) ? $object : '';
        }

        return $object;
    }

    /**
     * Score reset.
     *
     * @param int $lastID
     *
     * @access public
     * @return array
     */
    public function resetTest($lastID = 0)
    {
        $result = $this->objectModel->reset($lastID);
        while($result['status'] != 'finish')
        {
            $result = $this->objectModel->reset($result['lastID']);
        }

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Fix action type for score.
     *
     * @param string $string
     *
     * @access public
     * @return string
     */
    public function fixKeyTest($string)
    {
        $objects = $this->objectModel->fixKey($string);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 计算任务积分。
     * Compute task score.
     *
     * @param  int        $taskID
     * @param  string     $method
     * @access public
     * @return array|bool
     */
    public function computeTaskScoreTest(int $taskID, string $method): array|bool
    {
        $rule     = $this->objectModel->config->score->rule->task->finish;
        $extended = isset($this->objectModel->config->score->ruleExtended['task']['finish']) ? $this->objectModel->config->score->ruleExtended['task']['finish'] : array();

        return $this->objectModel->computeTaskScore('task', $method, $taskID, $rule, $extended);
    }

    /**
     * 计算Bug积分。
     * Compute bug score.
     *
     * @param  int    $caseID
     * @param  string $method
     * @access public
     * @return array
     */
    public function computeBugScoreTest(int $param, string $method): array
    {
        $rule     = isset($this->objectModel->config->score->rule->bug->{$method}) ? $this->objectModel->config->score->rule->bug->{$method} : array();
        $extended = isset($this->objectModel->config->score->ruleExtended['bug'][$method]) ? $this->objectModel->config->score->ruleExtended['bug'][$method] : array();

        if(in_array($method, array('confirm', 'resolve')))
        {
            $object = $this->objectModel->dao->select('*')->from(TABLE_BUG)->where('id')->eq($param)->fetch();
            if(!$object)
            {
                $object = new stdclass();
                $object->id       = $param;
                $object->openedBy = 'admin';
                $object->severity = 1;
            }
        }
        else
        {
            $object = $param;
        }

        return $this->objectModel->computeBugScore('bug', $method, $object, $rule, '', 'admin', $extended);
    }
}
