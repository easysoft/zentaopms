#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 projectModel::getBudgetUnitList();
cid=1
pid=1


*/
class Tester
{
    public function __construct($user)
    {
        global $tester;

        su($user);
        $this->project = $tester->loadModel('project');
    }

    /**
     * Check budget unit list.
     * 
     * @param  string $checkList 
     * @access public
     * @return bool
     */
    public function checkBudgetUnitList($checkList = array('CNY' => '人民币', 'USD' => '美元'))
    {
        $budgetList = $this->project->getBudgetUnitList();
        foreach($budgetList as $enBudget => $zhBudget)
        {
            if($checkList[$enBudget] != $zhBudget) return false;
        }
        return true;
    }
}

$t = new Tester('admin');

/* GetBudgetUnitList(). */
r($t->checkBudgetUnitList()) && p() && e('1'); //检查翻译
