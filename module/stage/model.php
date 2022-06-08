<?php
/**
 * The model file of stage module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     stage
 * @version     $Id: model.php 5079 2013-07-10 00:44:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class stageModel extends model
{
    /**
     * Create a stage.
     *
     * @access public
     * @return int|bool
     */
    public function create()
    {
        $stage = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::today())
            ->get();

        $totalPercent = $this->getTotalPercent();

        if(!is_numeric($stage->percent)) return dao::$errors['message'][] = $this->lang->stage->error->notNum;
        if(round($totalPercent + $stage->percent) > 100) return dao::$errors['message'][] = $this->lang->stage->error->percentOver;

        $this->dao->insert(TABLE_STAGE)
            ->data($stage)
            ->autoCheck()
            ->batchCheck($this->config->stage->create->requiredFields, 'notempty')
            ->checkIF($stage->percent != '', 'percent', 'float')
            ->exec();

        if(!dao::isError()) return $this->dao->lastInsertID();
        return false;
    }

    /**
     * Batch create stages.
     *
     * @access public
     * @return bool
     */
    public function batchCreate()
    {
        $data = fixer::input('post')->get();

        $totalPercent = $this->getTotalPercent();

        if(round($totalPercent + array_sum($data->percent)) > 100) return dao::$errors['message'][] = $this->lang->stage->error->percentOver;

        $this->loadModel('action');
        foreach($data->name as $i => $name)
        {
            if(!$name) continue;

            $stage = new stdclass();
            $stage->name        = $name;
            $stage->percent     = $data->percent[$i];
            $stage->type        = $data->type[$i];
            $stage->createdBy   = $this->app->user->account;
            $stage->createdDate = helper::today();

            $this->dao->insert(TABLE_STAGE)->data($stage)->autoCheck()
                ->batchCheck($this->config->stage->create->requiredFields, 'notempty')
                ->checkIF($stage->percent != '', 'percent', 'float')
                ->exec();
            
            if(dao::isError()) return false; 
            
            $stageID = $this->dao->lastInsertID();
            $this->action->create('stage', $stageID, 'Opened');
        }

        return true;
    }

    /**
     * Update a stage.
     *
     * @param  int    $stageID
     * @access public
     * @return bool
     */
    public function update($stageID)
    {
        $oldStage = $this->dao->select('*')->from(TABLE_STAGE)->where('id')->eq((int)$stageID)->fetch();

        $stage = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::today())
            ->get();

        $totalPercent = $this->getTotalPercent();

        if(round($totalPercent + $stage->percent - $oldStage->percent) > 100) return dao::$errors['message'][] = $this->lang->stage->error->percentOver;

        $this->dao->update(TABLE_STAGE)
            ->data($stage)
            ->autoCheck()
            ->batchCheck($this->config->stage->edit->requiredFields, 'notempty')
            ->checkIF($stage->percent != '', 'percent', 'float')->where('id')->eq((int)$stageID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldStage, $stage);
        return false;
    }

    /**
     * Get stages.
     *
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getStages($orderBy = 'id_desc')
    {
        return $this->dao->select('*')->from(TABLE_STAGE)->where('deleted')->eq(0)->orderBy($orderBy)->fetchAll('id');
    }

    /**
     * Get pairs of stage.
     *
     * @access public
     * @return array
     */
    public function getPairs()
    {
        $stages = $this->getStages();

        $pairs = array();
        foreach($stages as $stageID => $stage)
        {
            $pairs[$stageID] = $stage->name;
        }

        return $pairs;
    }

    /**
     * Get a stage by id.
     *
     * @param  int    $stageID
     * @access public
     * @return object
     */
    public function getByID($stageID)
    {
        return $this->dao->select('*')->from(TABLE_STAGE)->where('deleted')->eq(0)->andWhere('id')->eq((int)$stageID)->fetch();
    }

    /**
     *  Get stage total percent
     *
     *  return string
     */
    public function getTotalPercent()
    {
        return $this->dao->select('sum(percent) as total')->from(TABLE_STAGE)->where('deleted')->eq('0')->fetch('total');
    }
}
