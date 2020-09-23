<?php
/**
 * The model file of budget module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     budget
 * @version     $Id: model.php 5079 2013-07-10 00:44:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class budgetModel extends model
{
    /**
     * Get subject option.
     *
     * @access public
     * @return object
     */
    public function getSubjectOption()
    {
        $subjects    = $this->loadModel('tree')->getOptionMenu(0, $viewType = 'subject', $startModuleID = 0);
        $subjectList = $this->dao->select('*')->from(TABLE_MODULE)->where('id')->in(array_keys($subjects))->fetchAll();

        foreach($subjectList as $subject)
        {
            if(isset($subjects[$subject->parent])) unset($subjects[$subject->parent]);
            if($subject->grade > 2) unset($subjects[$subject->id]);
        }

        return $subjects;
    }

    /**
     * Get budget by id.
     *
     * @param  int  $budgetID
     * @access public
     * @return array
     */
    public function getByID($budgetID)
    {
        $budget = $this->dao->select('*')->from(TABLE_BUDGET)->where('id')->eq($budgetID)->fetch();
        $budget = $this->loadModel('file')->replaceImgURL($budget, 'desc');

        return $budget;
    }

    /**
     * Get budget list.
     *
     * @param  int     $programID
     * @param  varchar $orderBy
     * @param  pager   $object
     * @access public
     * @return object
     */
    public function getList($programID, $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_BUDGET)
            ->where('PRJ')->eq($programID)
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Get stages.
     *
     * @param  int    $programID
     * @access public
     * @return array
     */
    public function getStages($programID)
    {
        return $this->dao->select('stage')->from(TABLE_BUDGET)
            ->where('PRJ')->eq($programID)
            ->andWhere('deleted')->eq(0)
            ->orderBy('stage_asc')
            ->fetchPairs();
    }

    /**
     * Get subjects.
     *
     * @param  int    $programID
     * @access public
     * @return array
     */
    public function getSubjects($programID)
    {
        return $this->dao->select('subject')->from(TABLE_BUDGET)
            ->where('PRJ')->eq($programID)
            ->andWhere('deleted')->eq(0)
            ->orderBy('subject_asc')
            ->fetchPairs();
    }

    /**
     * Get subject structure.
     *
     * @access public
     * @return array
     */
    public function getSubjectStructure()
    {
        $subjects  = $this->getSubjects($this->session->PRJ);

        $structure = array();
        foreach($subjects as $subjectID)
        {
            $subject = $this->loadModel('tree')->getById($subjectID); 
            if($subject->grade == 1) 
            {
                $structure[$subject->id][] = $subject->id;
                continue;
            }
            
            $structure[$subject->parent][]           = $subject->id;
            $structure[$subject->parent]['hasChild'] = true;
        }

        return $structure;
    }

    /**
     * Get summary.
     *
     * @param  int    $programID
     * @access public
     * @return array
     */
    public function getSummary($programID)
    {
        $budgets = $this->dao->select('*')->from(TABLE_BUDGET)
            ->where('PRJ')->eq($programID)
            ->andWhere('deleted')->eq(0)
            ->fetchAll();

        $summary = array();
        foreach($budgets as $budget)
        {
            $summary[$budget->subject] = isset($summary[$budget->subject]) ? $summary[$budget->subject] : array();
            $summary[$budget->subject][$budget->stage]  = isset($summary[$budget->subject][$budget->stage]) ? $summary[$budget->subject][$budget->stage] : 0;
            $summary[$budget->subject][$budget->stage] += $budget->amount;
        }

        $total = 0;
        foreach($summary as $subject => $stageSummary)
        {
            $summary[$subject]['summary'] = 0;
            foreach($stageSummary as $amount) 
            {
                $summary[$subject]['summary'] += $amount;
                $total += $amount;
            }
        }

        $summary['total'] = $total;
        return $summary;
    }

    /**
     * Create a budget.
     *
     * @access public
     * @return int
     */
    public function create()
    {
        $budget = fixer::input('post')
            ->setDefault('createdBy', $this->app->user->account)
            ->setDefault('createdDate', helper::today())
            ->setDefault('PRJ', $this->session->PRJ)
            ->cleanFloat('amount')
            ->remove('uid')
            ->get();

        $this->dao->insert(TABLE_BUDGET)->data($budget)
            ->autoCheck()
            ->batchCheck($this->config->budget->create->requiredFields, 'notempty')
            ->exec();

        $budgetID = $this->dao->lastInsertID();
        return $budgetID;
    }

    /**
     * Batch create budgets.
     *
     * @access public
     * @return bool
     */
    public function batchCreate()
    {
        $today   = helper::today();
        $budgets = fixer::input('post')->get();

        foreach($budgets->name as $i => $name)
        {
            if(!$name) continue;
            $data = new stdclass();
            $data->name        = $name;
            $data->PRJ         = $this->session->PRJ;
            $data->stage       = $budgets->stage[$i];
            $data->subject     = $budgets->subject[$i];
            $data->amount      = (float)$budgets->amount[$i];
            $data->desc        = nl2br($budgets->desc[$i]);
            $data->createdBy   = $this->app->user->account;
            $data->createdDate = $today;

            foreach(explode(',', $this->config->budget->create->requiredFields) as $field)
            {    
                $field = trim($field);
                if($field and empty($data->$field))
                {    
                    dao::$errors['message'][] = sprintf($this->lang->error->notempty, $this->lang->budget->$field);
                    return false;
                }    
            }


            $this->dao->insert(TABLE_BUDGET)->data($data)
                ->autoCheck()
                ->exec();

            $budgetID = $this->dao->lastInsertID();
            $this->loadModel('action')->create('budget', $budgetID, 'Opened');
        }

        return !dao::isError();
    }

    /**
     * Update a budget.
     *
     * @param  int  $budgetID 
     * @access public
     * @return array
     */
    public function update($budgetID)
    {
        $oldBudget = $this->getByID($budgetID);
        $budget = fixer::input('post')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', helper::today())
            ->cleanFloat('amount')
            ->remove('uid')
            ->get();

        $this->dao->update(TABLE_BUDGET)->data($budget)
            ->where('id')->eq($budgetID)
            ->autoCheck()
            ->batchCheck($this->config->budget->create->requiredFields, 'notempty')
            ->exec();

        if(!dao::isError()) return common::createChanges($oldBudget, $budget);
    }
}
