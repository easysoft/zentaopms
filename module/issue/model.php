<?php
/**
 * The model file of issue module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     issue
 * @version     $Id: model.php 5145 2013-07-15 06:47:26Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class issueModel extends model
{
    /**
     * Create a question.
     *
     * @access public
     * @return bool
     */
    public function create()
    {
        $now  = helper::now();
        $data = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', $now)
            ->add('program', $this->session->program)
            ->remove('labels,files')
            ->addIF($this->post->assignedTo, 'assignedBy', $this->app->user->account)
            ->addIF($this->post->assignedTo, 'assignedDate', $now)
            ->stripTags($this->config->issue->editor->create['id'], $this->config->allowedTags)
            ->get();

        if(strpos($this->config->issue->create->requiredFields, 'type') !== false and !$this->post->type)
        {
            dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->issue->type);
            return false;
        }

        if(strpos($this->config->issue->create->requiredFields, 'title') !== false and !$this->post->title)
        {
            dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->issue->title);
            return false;
        }

        if(strpos($this->config->issue->create->requiredFields, 'severity') !== false and !$this->post->severity)
        {
            dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->issue->severity);
            return false;
        }

        $this->dao->insert(TABLE_ISSUE)->data($data)->exec();
        $issueID = $this->dao->lastInsertID();
        $this->loadModel('file')->saveUpload('issue', $issueID);

        return $issueID;
    }

    /**
     * Get question list data.
     *
     * @param  string    $browseType
     * @param  string    $orderBy
     * @param  object    $pager
     * @access public
     * @return object
     */
    public function getIssueList($browseType = 'all', $orderBy = 'id_desc', $pager = null)
    {
        $issueList = $this->dao->select('*')->from(TABLE_ISSUE)
            ->where('program')->eq($this->session->program)
            ->andWhere('deleted')->eq('0')
            ->beginIF($browseType == 'open')->andWhere('status')->eq('active')->fi()
            ->beginIF($browseType == 'assignto')->andWhere('assignedTo')->eq($this->app->user->account)->fi()
            ->beginIF($browseType == 'closed')->andWhere('status')->eq('closed')->fi()
            ->beginIF($browseType == 'suspended')->andWhere('status')->eq('suspended')->fi()
            ->beginIF($browseType == 'cancelled')->andWhere('status')->eq('canceled')->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();

        return $issueList;
    }

    /**
     * Delete a question.
     *
     * @param  int    $issueID
     * @param  int    $null
     * @access public
     * @return object
     */
    public function delete($issueID = 0, $null = null)
    {
        $this->dao->update(TABLE_ISSUE)->set('deleted')->eq('1')->where('id')->eq($issueID)->exec();
    }

    /**
     * Get a issue details.
     *
     * @param  int    $issueID
     * @access public
     * @return object
     */
    public function getByID($issueID)
    {
        return $this->dao->select('*')->from(TABLE_ISSUE)->where('id')->eq($issueID)->andWhere('deleted')->eq('0')->fetch();
    }

    /**
     * Update a question.
     *
     * @param  int    $issueID
     * @access public
     * @return bool
     */
    public function update($issueID)
    {
        $now = helper::now();
        $data = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', $now)
            ->addIF($this->post->assignedTo, 'assignedBy', $this->app->user->account)
            ->addIF($this->post->assignedTo, 'assignedDate', $now)
            ->stripTags($this->config->issue->editor->edit['id'], $this->config->allowedTags)
            ->get();

        if(strpos($this->config->issue->edit->requiredFields, 'type') !== false and !$this->post->type)
        {
            dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->issue->type);
            return false;
        }

        if(strpos($this->config->issue->edit->requiredFields, 'title') !== false and !$this->post->title)
        {
            dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->issue->title);
            return false;
        }

        if(strpos($this->config->issue->edit->requiredFields, 'severity') !== false and !$this->post->severity)
        {
            dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->issue->severity);
            return false;
        }
        $oldIssue = $this->getByID($issueID);

        $this->dao->update(TABLE_ISSUE)->data($data)->where('id')->eq($issueID)->exec();
        return common::createChanges($oldIssue, $data);
    }

    /**
     * Update assignor.
     *
     * @param  int    $issueID
     * @access public
     * @return bool
     */
    public function assignTo($issueID)
    {
        $data = fixer::input('post')
            ->add('assignedBy', $this->app->user->account)
            ->add('assignedDate', helper::now())
            ->get();

        $oldIssue = $this->getByID($issueID);
        $this->dao->update(TABLE_ISSUE)->data($data)->where('id')->eq($issueID)->exec();

        return common::createChanges($oldIssue, $data);
    }

    /**
     * Close issue.
     *
     * @param  int    $issueID
     * @access public
     * @return bool
     */
    public function close($issueID)
    {
        $data = fixer::input('post')
            ->add('closeBy', $this->app->user->account)
            ->add('status', 'closed')
            ->get();

        $oldIssue = $this->getByID($issueID);
        $this->dao->update(TABLE_ISSUE)->data($data)->where('id')->eq($issueID)->exec();

        return common::createChanges($oldIssue, $data);
    }

    /**
     * Cancel issue.
     *
     * @param  int    $issueID
     * @access public
     * @return bool
     */
    public function cancel($issueID)
    {
        $data     = fixer::input('post')->get();
        $oldIssue = $this->getByID($issueID);
        $this->dao->update(TABLE_ISSUE)->data($data)->where('id')->eq($issueID)->exec();

        return common::createChanges($oldIssue, $data);
    }

    /**
     * Activate issue.
     *
     * @param  int    $issueID
     * @access public
     * @return bool
     */
    public function activate($issueID)
    {
        $data = fixer::input('post')
            ->add('status', 'active')
            ->get();
        $oldIssue = $this->getByID($issueID);
        $this->dao->update(TABLE_ISSUE)->data($data)->where('id')->eq($issueID)->exec();

        return common::createChanges($oldIssue, $data);
    }

    /**
     * Batch create issue.
     *
     * @access public
     * @return void
     */
    public function batchCreate()
    {
        $now  = helper::now();
        $data = fixer::input('post')->get();

        $issues = array();
        foreach($data->dataList as $issue)
        {
            if(!trim($issue['title'])) continue;

            $issue['createdBy']   = $this->app->user->account;
            $issue['createdDate'] = $now;
            $issue['program']     = $this->session->program;
            if($issue['assignedTo'])
            {
                $issue['assignedBy']   = $this->app->user->account;
                $issue['assignedDate'] = $now;
            }

            foreach(explode(',',$this->config->issue->create->requiredFields) as $field)
            {
                $field = trim($field);
                if($field and empty($issue["$field"])) die(js::alert(sprintf($this->lang->error->notempty, $this->lang->issue->$field)));	
            }

            $issues[] = $issue;
        }
        foreach($issues as $issue) $this->dao->insert(TABLE_ISSUE)->data($issue)->exec();

        return true;
    }
}
