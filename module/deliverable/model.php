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
     * 构造交付物适用范围列表。
     * Build deliverable model list.
     *
     * @access public
     * @return array
     */
    public function buildModelList()
    {
        $this->app->loadLang('stage');
        $this->app->loadLang('execution');
        $modelList    = $this->lang->deliverable->modelList;
        $stageList    = $this->lang->stage->typeList;
        $lifeTimeList = $this->lang->execution->lifeTimeList;
        foreach($modelList as $key => $value)
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

        return $modelList;
    }
}
