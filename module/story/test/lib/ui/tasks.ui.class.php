<?php
require_once dirname(__FILE__, 6) . '/test/lib/ui.php';

define('HEADERS', ['id','name','pri','status','assignedTo','estimate','consumed','left','progress']);

class tasksTester extends tester
{
    /**
     * 验证任务列表页
     * Verify product tasks view
     *
     * @param int $storyID     需求ID
     * @param int $executionID 执行ID
     * @return                 成功或者失败对象
     */
    public function verifyTasksView($storyID, $executionID = 0)
    {
        $this->login();
        $form = $this->initForm('story', 'tasks', array('storyID' => $storyID, 'executionID' => $executionID), 'appIframe-product');
        $form->wait(2);

        $displayed = new stdClass();
        foreach(HEADERS as $c)
        {
            $displayed->$c = $form->dom->getElementListByXpathKey($c, true);
            if(empty($displayed->$c)) return $this->failed("任务页列{$c}无内容");
        }

        global $uiTester;
        $rows = $uiTester->dao->select('id,name,assignedTo,pri,status,estimate,consumed,`left`')
            ->from(TABLE_TASK)
            ->where('story')->eq($storyID)
            ->andWhere('execution')->eq($executionID)
            ->andWhere('deleted')->eq('0')
            ->fetchAll();
        $users = $uiTester->dao->select('account,realname')->from(TABLE_USER)->fetchPairs('account','realname');

        $expectedCount = count($rows);
        if($expectedCount == 0) return $this->failed('数据库无任务数据');
        if(count($displayed->id) != $expectedCount) return $this->failed('任务数量不匹配');

        $byTaskName = array();
        foreach($rows as $r) $byTaskName[$r->name] = $r;

        $statusMap = (array)$this->lang->task->statusList;

        for($i = 0; $i < $expectedCount; $i++)
        {
            $row = $byTaskName[$displayed->name[$i]];
            foreach(HEADERS as $c)
            {
                switch($c)
                {
                    case 'progress':
                        $expectProgress = '';
                        if((float)$row->left > 0)
                        {
                            $sum = (float)$row->consumed + (float)$row->left;
                            $expectProgress = (string)(round(((float)$row->consumed / $sum), 2) * 100);
                        }
                        else $expectProgress = ((float)$row->consumed == 0) ? '0' : '100';
                        if($displayed->$c[$i] !== $expectProgress) return $this->failed("第{$i}条任务{$c}不匹配");
                        break;
                    case 'status':
                        $expectStatus = isset($statusMap[$row->status]) ? $statusMap[$row->status] : $row->status;
                        if($displayed->$c[$i] !== $expectStatus) return $this->failed("第{$i}条任务{$c}不匹配");
                        break;
                    case 'assignedTo':
                        $expectUser   = isset($users[$row->assignedTo]) ? $users[$row->assignedTo] : $row->assignedTo;
                        if($displayed->$c[$i] !== $expectUser) return $this->failed("第{$i}条任务{$c}不匹配");
                        break;
                    default:
                        if($displayed->$c[$i] !== (string)$row->$c) return $this->failed("第{$i}条任务{$c}不匹配");
                        break;
                }
            }
        }
        return $this->success('开源版m=story&f=tasks测试通过');
    }
}
