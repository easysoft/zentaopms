<?php
class deliverableModel extends model
{
    /**
     * 获取交付物列表。
     * Get deliverable list.
     *
     * @access public
     * @return array
     */
    public function getList()
    {
        $deliverables = $this->dao->select('*')->from(TABLE_DELIVERABLE)->fetchAll();
        return $deliverables;
    }

    /**
     * 创建交付物。
     * Create deliverable.
     *
     * @param  object $deliverable
     * @access public
     * @return bool
     */
    public function create($deliverable)
    {
        $this->dao->insert(TABLE_DELIVERABLE)->data($deliverable)->exec();
        $deliverableID = $this->dao->lastInsertID();

        $files = $this->loadModel('file')->saveUpload('deliverable', $deliverableID);
        if(!empty($files))
        {
            $fileIdList = implode(',', array_keys($files));
            $this->dao->update(TABLE_DELIVERABLE)->set('files')->eq($fileIdList)->where('id')->eq($deliverableID)->exec();
        }

        $this->loadModel('action')->create('deliverable', $deliverableID, 'opened');

        return !dao::isError();
    }

    /**
     * 构造交付物适用范围列表。
     * Build deliverable model list.
     *
     * @param string $type all|project|execution
     * @access public
     * @return array
     */
    public function buildModelList($type = 'all')
    {
        $this->app->loadLang('stage');
        $this->app->loadLang('execution');

        $modelList = array();

        if($type == 'all' || $type == 'project') $modelList = $this->lang->deliverable->modelList;
        if($type == 'all' || $type == 'execution')
        {
            $stageList    = $this->lang->stage->typeList;
            $lifeTimeList = $this->lang->execution->lifeTimeList;
            foreach($this->lang->deliverable->modelList as $key => $value)
            {
                if(strpos($key, 'waterfall') !== false)
                {
                    foreach($stageList as $stageKey => $stageValue)
                    {
                        $modelList[$key . '_' . $stageKey] = $value . '/' . $stageValue . $this->lang->execution->typeList['stage'];
                    }
                }
                elseif(strpos($key, 'scrum') !== false)
                {
                    foreach($lifeTimeList as $lifeTimeKey => $lifeTimeValue)
                    {
                        $modelList[$key . '_' . $lifeTimeKey] = $value . '/' . $lifeTimeValue . $this->lang->execution->typeList['sprint'];
                    }
                }
            }
        }

        return $modelList;
    }
}
