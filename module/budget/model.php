<?php
class budgetModel extends model
{
    /**
     * Get subject option.
     *
     * @access public
     * @return void
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
     * @return void
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
     * @param  varchar $orderBy
     * @param  pager   $object
     * @access public
     * @return void
     */
    public function getList($orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_BUDGET)
            ->where('program')->eq($this->session->program)
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Get stages.
     *
     * @access public
     * @return void
     */
    public function getStages()
    {
        return $this->dao->select('stage')->from(TABLE_BUDGET)
            ->where('program')->eq($this->session->program)
            ->andWhere('deleted')->eq(0)
            ->orderBy('stage_asc')
            ->fetchPairs();
    }

    /**
     * Get subjects.
     *
     * @access public
     * @return void
     */
    public function getSubjects()
    {
        return $this->dao->select('subject')->from(TABLE_BUDGET)
            ->where('program')->eq($this->session->program)
            ->andWhere('deleted')->eq(0)
            ->orderBy('subject_asc')
            ->fetchPairs();
    }

    /**
     * Get subject structure.
     *
     * @access public
     * @return void
     */
    public function getSubjectStructure()
    {
        $subjects  = $this->getSubjects();

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
     * @access public
     * @return void
     */
    public function getSummary()
    {
        $budgets = $this->dao->select('*')->from(TABLE_BUDGET)
            ->where('program')->eq($this->session->program)
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
     * @return void
     */
    public function create()
    {
        $budget = fixer::input('post')
            ->setDefault('createdBy', $this->app->user->account)
            ->setDefault('createdDate', helper::today())
            ->setDefault('program', $this->session->program)
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
     * @return void
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
            $data->program     = $this->session->program;
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
     * @return void
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
