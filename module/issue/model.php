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

    public function getBlockIssues($browseType = 'all', $limit = 15, $orderBy = 'id_desc')
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
            ->limit($limit)
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

    /**
     * Resolve issue.
     *
     * @param  int    $issueID
     * @access public
     * @return void
     */
    public function resolve($issueID)
    {
        $issue = $this->post->issue;
        $issue['status'] = 'resolved';
        $this->dao->update(TABLE_ISSUE)->data($issue)->where('id')->eq($issueID)->exec();
    }

    /**
     * Create task.
     *
     * @access public
     * @return void
     */
    public function createTask()
    {
        $task = fixer::input('post')->remove('issue,spec')->get();
        $this->dao->insert(TABLE_TASK)->data($task, 'teamMember,storyEstimate,storyDesc,storyPri,labels,files')->exec();
        return $this->dao->lastInsertID();
    }

    /**
     * Create story.
     *
     * @access public
     * @return int
     */
    public function createStory()
    {
        $story = fixer::input('post')->remove('issue,color')
            ->setIF($this->post->needNotReview or $this->post->projectID > 0, 'status', 'active')
            ->get();
        $this->dao->insert(TABLE_STORY)->data($story, 'teamMember,storyEstimate,storyDesc,storyPri,labels,files,spec,story,needNotReview')->exec();
        $id = $this->dao->lastInsertID();
        $this->dao->insert(TABLE_STORYSPEC)
            ->set('story')->eq($id)
            ->set('title')->eq($story->title)
            ->set('spec')->eq($story->spec)
            ->set('version')->eq(1)
            ->exec();
        return $id;
    }

    /**
     * Create bug.
     *
     * @access public
     * @return int
     */
    public function createBug()
    {
        $bug = fixer::input('post')->remove('issue,spec,color')->join('openedBuild', ',')->get();
        $this->dao->insert(TABLE_BUG)->data($bug, 'teamMember,storyEstimate,storyDesc,storyPri,labels,files')->exec();
        return $this->dao->lastInsertID();
    }

    /**
     * Create risk.
     *
     * @access public
     * @return int
     */
    public function createRisk()
    {
        $risc = fixer::input('post')->remove('issue,color,estimate')->get();
        $this->dao->insert(TABLE_RISK)->data($risc, 'spec,title,teamMember,storyEstimate,storyDesc,storyPri,labels,files')->exec();
        return $this->dao->lastInsertID();
    }

}
