<?php
class epic extends control
{
    /**
     * Create a epic.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $moduleID
     * @param  int    $storyID
     * @param  int    $objectID  projectID|executionID
     * @param  int    $bugID
     * @param  int    $planID
     * @param  int    $todoID
     * @param  string $extra for example feedbackID=0
     * @access public
     * @return void
     */
    public function create(int $productID = 0, string $branch = '', int $moduleID = 0, int $storyID = 0, int $objectID = 0, int $bugID = 0, int $planID = 0, int $todoID = 0, string $extra = '')
    {
        echo $this->fetch('story', 'create', "productID=$productID&branch=$branch&moduleID=$moduleID&storyID=$storyID&objectID=$objectID&bugID=$bugID&planID=$planID&todoID=$todoID&extra=$extra&storyType=epic");
    }

    /**
     * Create a batch stories.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  int    $moduleID
     * @param  int    $storyID
     * @param  int    $executionID projectID|executionID
     * @param  int    $plan
     * @param  string $storyType
     * @param  string $extra for example feedbackID=0
     * @access public
     * @return void
     */
    public function batchCreate(int $productID = 0, string $branch = '', int $moduleID = 0, int $storyID = 0, int $executionID = 0, int $plan = 0, string $storyType = 'epic', string $extra = '')
    {
        echo $this->fetch('story', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$moduleID&storyID=$storyID&executionID=$executionID&plan=$plan&storyType=epic&extra=$extra");
    }

    /**
     * View a epic.
     *
     * @param  int    $storyID
     * @param  int    $version
     * @param  int    $param     executionID|projectID
     * @param  string $storyType
     * @access public
     * @return void
     */
    public function view(int $storyID, int $version = 0, int $param = 0)
    {
        echo $this->fetch('story', 'view', "storyID=$storyID&version=$version&param=$param&storyType=epic");
    }

    /**
     * Edit a epic.
     *
     * @param  int    $storyID
     * @param  string $kanbanGroup
     * @access public
     * @return void
     */
    public function edit(int $storyID, string $kanbanGroup = 'default')
    {
        echo $this->fetch('story', 'edit', "storyID=$storyID&kanbanGroup=$kanbanGroup&storyType=epic");
    }

    /**
     * Batch edit epic.
     *
     * @param  int    $productID
     * @param  int    $executionID
     * @param  int    $branch
     * @param  string $storyType
     * @param  string $from
     * @access public
     * @return void
     */
    public function batchEdit(int $productID = 0, int $executionID = 0, string $branch = '', string $storyType = 'epic', string $from = '')
    {
        echo $this->fetch('story', 'batchEdit', "productID=$productID&executionID=$executionID&branch=$branch&storyType=epic&from=$from");
    }

    /**
     * 关联Epic。
     * Link related epics.
     *
     * @param  int    $storyID
     * @param  string $browseType
     * @param  string $excludeStories
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkEpic(int $storyID, string $browseType = '', string $excludeStories = '', int $param = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        echo $this->fetch('story', 'linkStories', "storyID=$storyID&browseType=$browseType&excludeStories=$excludeStories&param=$param&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * 关联用户需求。
     * Link related requirements.
     *
     * @param  int    $storyID
     * @param  string $browseType
     * @param  string $excludeStories
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkRequirements(int $storyID, string $browseType = '', string $excludeStories = '', int $param = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        echo $this->fetch('story', 'linkRequirements', "storyID=$storyID&browseType=$browseType&excludeStories=$excludeStories&param=$param&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * 导出需求数据。
     * Get the data of the requiremens to export.
     *
     * @param  int    $productID
     * @param  string $orderBy
     * @param  int    $executionID
     * @param  string $browseType
     * @access public
     * @return void
     */
    public function export(int $productID, string $orderBy, int $executionID = 0, string $browseType = '')
    {
        echo $this->fetch('story', 'export', "productID=$productID&orderBy=$orderBy&executionID=$executionID&browseType=$browseType&storyType=epic");
    }

    /**
     * Delete a epic.
     *
     * @param  int    $storyID
     * @param  string $confirm   yes|no
     * @param  string $from      taskkanban
     * @access public
     * @return void
     */
    public function delete(int $storyID, string $confirm = 'no', string $from = '')
    {
        echo $this->fetch('story', 'delete', "storyID=$storyID&confirm=$confirm&from=$from&storyType=epic");
    }

    /**
     * Change a epic.
     *
     * @param  int    $storyID
     * @param  string $from
     * @access public
     * @return void
     */
    public function change(int $storyID, string $from = '')
    {
        echo $this->fetch('story', 'change', "storyID=$storyID&from=$from&storyType=epic");
    }

    /**
     * Review a epic.
     *
     * @param  int    $storyID
     * @param  string $from      product|project
     * @access public
     * @return void
     */
    public function review(int $storyID, string $from = 'product')
    {
        echo $this->fetch('story', 'review', "storyID=$storyID&from=$from&storyType=epic");
    }

    /**
     * Submit review.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function submitReview(int $storyID)
    {
        echo $this->fetch('story', 'submitReview', "storyID=$storyID&storyType=epic");
    }

    /**
     * Batch review epics.
     *
     * @param  string $result
     * @param  string $reason
     * @access public
     * @return void
     */
    public function batchReview(string $result, string $reason = '')
    {
        echo $this->fetch('story', 'batchReview', "result=$result&reason=$reason&storyType=epic");
    }

    /**
     * Recall the epic review or epic change.
     *
     * @param  int    $storyID
     * @param  string $from      list
     * @param  string $confirm   no|yes
     * @access public
     * @return void
     */
    public function recall(int $storyID, string $from = 'list', string $confirm = 'no')
    {
        echo $this->fetch('story', 'recall', "storyID=$storyID&from=$from&confirm=$confirm&storyType=epic");
    }

    /**
     * 需求的指派给页面。
     * Assign the epic to a user.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function assignTo(int $storyID)
    {
        echo $this->fetch('story', 'assignTo', "storyID=$storyID");
    }

    /**
     * 关闭需求。
     * Close the epic.
     *
     * @param  int    $storyID
     * @param  string $from      taskkanban
     * @access public
     * @return void
     */
    public function close(int $storyID, string $from = '')
    {
        echo $this->fetch('story', 'close', "storyID=$storyID&from=$from&storyType=epic");
    }

    /**
     * 批量关闭需求。
     * Batch close the requiremens.
     *
     * @param  int    $productID
     * @param  int    $executionID
     * @param  string $storyType
     * @param  string $from        contribute|work
     * @access public
     * @return void
     */
    public function batchClose(int $productID = 0, int $executionID = 0, string $storyType = 'epic', string $from = '')
    {
        echo $this->fetch('story', 'batchClose', "productID=$productID&executionID=$executionID&storyType=epic&from=$from");
    }

    /**
     * 激活需求。
     * Activate a epic.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function activate(int $storyID)
    {
        echo $this->fetch('story', 'activate', "storyID=$storyID&storyType=epic");
    }

    /**
     * 查看需求的报告。
     * The report page.
     *
     * @param  int    $productID
     * @param  int    $branchID
     * @param  string $storyType
     * @param  string $browseType
     * @param  int    $moduleID
     * @param  string $chartType
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function report(int $productID, int $branchID, string $storyType = 'epic', string $browseType = 'unclosed', int $moduleID = 0, string $chartType = 'pie', int $projectID = 0)
    {
        echo $this->fetch('story', 'report', "productID=$productID&branchID=$branchID&storyType=epic&browseType=$browseType&moduleID=$moduleID&chartType=$chartType&projectID=$projectID");
    }

    /**
     * Batch change branch.
     *
     * @param  int    $branchID
     * @param  string $confirm  yes|no
     * @param  string $storyIdList
     * @access public
     * @return void
     */
    public function batchChangeBranch(int $branchID, string $confirm = '', string $storyIdList = '')
    {
        echo $this->fetch('story', 'batchChangeBranch', "branchID=$branchID&confirm=$confirm&storyIdList=$storyIdList&storyType=epic");
    }

    /**
     * Batch assign to.
     *
     * @param  string $storyType story|epic
     * @access public
     * @return void
     */
    public function batchAssignTo(string $storyType = 'epic', string $assignedTo = '')
    {
        echo $this->fetch('story', 'batchAssignTo', "storyType=epic&assignedTo=$assignedTo");
    }

    /**
     * Batch change the module of story.
     *
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function batchChangeModule(int $moduleID)
    {
        echo $this->fetch('story', 'batchChangeModule', "moduleID=$moduleID&storyType=epic");
    }
}
