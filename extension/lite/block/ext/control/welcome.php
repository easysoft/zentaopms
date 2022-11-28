<?php
helper::importControl('block');
class myBlock extends block
{
    /**
     * Welcome block.
     *
     * @access public
     * @return void
     */
    public function welcome()
    {
        $this->view->tutorialed = $this->loadModel('tutorial')->getTutorialed();

        $tasks = $this->dao->select('t1.id,t1.status,t1.deadline,t1.estStarted,t2.begin,t2.end')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on("t1.project = t2.id")
            ->leftJoin(TABLE_EXECUTION)->alias('t3')->on("t1.execution = t3.id")
            ->where('t1.assignedTo')->eq($this->app->user->account)
            ->andWhere('t1.status')->in('wait,doing')
            ->andWhere('(t2.status')->ne('suspended')
            ->orWhere('t3.status')->ne('suspended')
            ->markRight(1)
            ->andWhere('t1.deleted')->eq('0')
            ->beginIF($this->config->systemMode == 'ALM')->andWhere('t2.deleted')->eq('0')->fi()
            ->andWhere('t3.deleted')->eq('0')
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->fetchAll('id');

        $today             = helper::today();
        $todayTimeStamp    = strtotime($today);
        $threeDayTimeStamp = $todayTimeStamp + (86400 * 3);

        $data = array();
        $data['undone']   = count($tasks);
        $data['delaying'] = 0;
        $data['delayed']  = 0;

        foreach($tasks as $key => $task)
        {
            if(helper::isZeroDate($task->deadline)) $task->deadline = $task->end;
            if(helper::isZeroDate($task->estStarted)) $task->estStarted = $task->begin;
            $endTimeStamp   = strtotime($task->deadline);
            if($endTimeStamp < $todayTimeStamp) $data['delayed'] += 1;
            if($endTimeStamp > $todayTimeStamp and $endTimeStamp < $threeDayTimeStamp) $data['delaying'] += 1;
        }

        $this->view->data = $data;

        $time = date('H:i');
        $welcomeType = '19:00';
        foreach($this->lang->block->welcomeList as $type => $name)
        {
            if($time >= $type) $welcomeType = $type;
        }
        $this->view->welcomeType = $welcomeType;
        $this->display();
    }
}
