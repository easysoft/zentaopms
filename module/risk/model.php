<?php
/**
 * The model file of risk module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yuchun Li <liyuchun@cnezsoft.com>
 * @package     risk
 * @version     $Id: model.php 5079 2020-09-04 09:08:34Z lyc $
 * @link        http://www.zentao.net
 */
?>
<?php
class riskModel extends model
{
    /**
     * Create a risk.
     *
     * @access public
     * @return int|bool
     */
    public function create()
    {
        $risk = fixer::input('post')
            ->add('PRJ', $this->session->PRJ)
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::today())
            ->stripTags($this->config->risk->editor->create['id'], $this->config->allowedTags)
            ->remove('uid')
            ->get();

        $risk = $this->loadModel('file')->processImgURL($risk, $this->config->risk->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_RISK)->data($risk)->autoCheck()->batchCheck($this->config->risk->create->requiredFields, 'notempty')->exec();

        if(!dao::isError()) return $this->dao->lastInsertID();
        return false;
    }

    /**
     * Batch create risk.
     *
     * @access public
     * @return bool
     */
    public function batchCreate()
    {
        $data = fixer::input('post')->get(); 

        $this->loadModel('action');
        foreach($data->name as $i => $name)
        {
            if(!$name) continue; 

            $risk = new stdclass();
            $risk->name        = $name;
            $risk->source      = $data->source[$i];
            $risk->category    = $data->category[$i];
            $risk->strategy    = $data->strategy[$i];
            $risk->PRJ         = $this->session->PRJ;
            $risk->createdBy   = $this->app->user->account;
            $risk->createdDate = helper::today();

            $this->dao->insert(TABLE_RISK)->data($risk)->autoCheck()->exec();

            $riskID = $this->dao->lastInsertID();
            $this->action->create('risk', $riskID, 'Opened');
        }

        return true;
    }

    /**
     * Update a risk.
     *
     * @param  int    $riskID
     * @access public
     * @return array|bool
     */
    public function update($riskID)
    {
        $oldRisk = $this->getByID($riskID);

        $risk = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::today())
            ->stripTags($this->config->risk->editor->edit['id'], $this->config->allowedTags)
            ->remove('uid')
            ->get();

        $this->dao->update(TABLE_RISK)->data($risk)->autoCheck()->where('id')->eq((int)$riskID)->exec();

        if(!dao::isError()) return common::createChanges($oldRisk, $risk);
        return false;
    }

    /**
     * Track a risk.
     *
     * @param  int    $riskID
     * @access public
     * @return array|bool
     */
    public function track($riskID)
    {
        $oldRisk = $this->dao->select('*')->from(TABLE_RISK)->where('id')->eq((int)$riskID)->fetch();

        $risk = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::today())
            ->stripTags($this->config->risk->editor->track['id'], $this->config->allowedTags)
            ->remove('isChange,comment,uid,files,label')
            ->get();

        $this->dao->update(TABLE_RISK)->data($risk)->autoCheck()->where('id')->eq((int)$riskID)->exec();

        if(!dao::isError()) return common::createChanges($oldRisk, $risk);
        return false;
    }

    /**
     * Get risks List.
     *
     * @param  int    $projectID
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $pager
     * @access public
     * @return object
     */
    public function getList($projectID, $browseType = '', $param = '', $orderBy = 'id_desc', $pager = null)
    {
        if($browseType == 'bysearch') return $this->getBySearch($projectID, $param, $orderBy, $pager);

        return $this->dao->select('*')->from(TABLE_RISK)
            ->where('deleted')->eq(0)
            ->beginIF($browseType != 'all' and $browseType != 'assignTo')->andWhere('status')->eq($browseType)->fi()
            ->beginIF($browseType == 'assignTo')->andWhere('assignedTo')->eq($this->app->user->account)->fi()
            ->andWhere('PRJ')->eq($projectID)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get risks by search
     *
     * @param  int    $projectID
     * @param  string $queryID
     * @param  string $orderBy
     * @param  int    $pager
     * @access public
     * @return object
     */
    public function getBySearch($projectID, $queryID = '', $orderBy = 'id_desc', $pager = null)
    {
        if($queryID && $queryID != 'myQueryID')
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('riskQuery', $query->sql);
                $this->session->set('riskForm', $query->form);
            }
            else
            {
                $this->session->set('riskQuery', ' 1 = 1');
            }
        }
        else
        {
            if($this->session->riskQuery == false) $this->session->set('riskQuery', ' 1 = 1');
        }

        $riskQuery = $this->session->riskQuery;

        return $this->dao->select('*')->from(TABLE_RISK)
            ->where($riskQuery)
            ->andWhere('deleted')->eq('0')
            ->andWhere('PRJ')->eq($projectID)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get risks of pairs
     *
     * @param  int    $projectID
     * @access public
     * @return object
     */
    public function getPairs($projectID)
    {
        return $this->dao->select('id, name')->from(TABLE_RISK)
            ->where('deleted')->eq(0)
            ->andWhere('PRJ')->eq($projectID)
            ->fetchPairs();
    }

    /**
     * Get risk by ID
     *
     * @param  int    $riskID
     * @access public
     * @return object
     */
    public function getByID($riskID)
    {
        return $this->dao->select('*')->from(TABLE_RISK)->where('id')->eq((int)$riskID)->fetch();
    }

    /**
     * Get block risks
     *
     * @param  int    $projectID
     * @param  string $browseType
     * @param  int    $limit
     * @param  string $orderBy
     * @access public
     * @return object
     */
    public function getBlockRisks($projectID, $browseType = 'all', $limit = 15, $orderBy = 'id_desc')
    {
        return $this->dao->select('*')->from(TABLE_RISK)
            ->where('PRJ')->eq($projectID)
            ->beginIF($browseType != 'all' and $browseType != 'assignTo')->andWhere('status')->eq($browseType)->fi()
            ->beginIF($browseType == 'assignTo')->andWhere('assignedTo')->eq($this->app->user->account)->fi()
            ->andWhere('deleted')->eq('0')
            ->orderBy($orderBy)
            ->limit($limit)
            ->fetchAll();
    }

    /**
     * Get user risks.
     *
     * @param  string $type    open|assignto|closed|suspended|canceled
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return object
     */
    public function getUserRisks($type = 'assignedTo', $orderBy = 'id_desc', $pager)
    {
        $riskList = $this->dao->select('*')->from(TABLE_RISK)
            ->where('deleted')->eq('0')
            ->andWhere($type)->eq($this->app->user->account)->fi()
            ->beginIF($this->app->rawMethod == 'contribute')->andWhere("status")->in('closed,canceled')->fi()
            ->beginIF($this->app->rawMethod == 'work')->andWhere("status")->in('active,hangup')->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();

        return $riskList;
    }

    /**
     * Get risk pairs of a user.
     *
     * @param  string $account
     * @param  int    $limit
     * @param  string $status active|closed|hangup|canceled
     * @access public
     * @return array
     */
    public function getUserRiskPairs($account, $limit = 10, $status = 'all')
    {
        $stmt = $this->dao->select('t1.id, t1.name, t2.name as project')
            ->from(TABLE_RISK)->alias('t1')
            ->leftjoin(TABLE_PROJECT)->alias('t2')->on('t1.PRJ = t2.id')
            ->where('t1.assignedTo')->eq($account)
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF($status != 'all')->andWhere('t1.status')->in($status)->fi()
            ->query();

        $risks = array();
        while($risk = $stmt->fetch())
        {
            $risks[$risk->id] = $risk->project . ' / ' . $risk->name;
        }
        return $risks;
    }

    /**
     * Print assignedTo html
     *
     * @param  int    $risk
     * @param  int    $users
     * @access public
     * @return string
     */
    public function printAssignedHtml($risk, $users)
    {
        $btnTextClass   = '';
        $assignedToText = zget($users, $risk->assignedTo);

        if(empty($risk->assignedTo))
        {
            $btnTextClass   = 'text-primary';
            $assignedToText = $this->lang->risk->noAssigned;
        }
        if($risk->assignedTo == $this->app->user->account) $btnTextClass = 'text-red';

        $btnClass     = $risk->assignedTo == 'closed' ? ' disabled' : '';
        $btnClass     = "iframe btn btn-icon-left btn-sm {$btnClass}";
        $assignToLink = helper::createLink('risk', 'assignTo', "riskID=$risk->id", '', true);
        $assignToHtml = html::a($assignToLink, "<i class='icon icon-hand-right'></i> <span title='" . zget($users, $risk->assignedTo) . "' class='{$btnTextClass}'>{$assignedToText}</span>", '', "class='$btnClass'");

        echo !common::hasPriv('risk', 'assignTo', $risk) ? "<span style='padding-left: 21px' class='{$btnTextClass}'>{$assignedToText}</span>" : $assignToHtml;
    }

    /**
     * Assign a risk.
     *
     * @param  int    $riskID
     * @access public
     * @return array|bool
     */
    public function assign($riskID)
    {
        $oldRisk = $this->getByID($riskID);
        
        $risk = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::today())
            ->setDefault('assignedDate', helper::today())
            ->stripTags($this->config->risk->editor->assignto['id'], $this->config->allowedTags)
            ->remove('uid,comment,files,label')
            ->get();

        $this->dao->update(TABLE_RISK)->data($risk)->autoCheck()->where('id')->eq((int)$riskID)->exec();

        if(!dao::isError()) return common::createChanges($oldRisk, $risk);
        return false;
    }

    /**
     * Cancel a risk.
     *
     * @param  int    $riskID
     * @access public
     * @return array|bool
     */
    public function cancel($riskID)
    {
        $oldRisk = $this->getByID($riskID);
        
        $risk = fixer::input('post')
            ->setDefault('status','canceled')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::today())
            ->stripTags($this->config->risk->editor->cancel['id'], $this->config->allowedTags)
            ->remove('uid,comment')
            ->get();

        $this->dao->update(TABLE_RISK)->data($risk)->autoCheck()->where('id')->eq((int)$riskID)->exec();

        if(!dao::isError()) return common::createChanges($oldRisk, $risk);
        return false;
    }

    /**
     * Close a risk.
     *
     * @param  int    $riskID
     * @access public
     * @return array|bool
     */
    public function close($riskID)
    {
        $oldRisk = $this->getByID($riskID);
        
        $risk = fixer::input('post')
            ->setDefault('status','closed')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::today())
            ->add('closedBy', $this->app->user->account)
            ->add('closedDate', helper::today())
            ->stripTags($this->config->risk->editor->close['id'], $this->config->allowedTags)
            ->remove('uid,comment')
            ->get();

        $this->dao->update(TABLE_RISK)->data($risk)->autoCheck()->where('id')->eq((int)$riskID)->exec();

        if(!dao::isError()) return common::createChanges($oldRisk, $risk);
        return false;
    }

    /**
     * Hangup a risk.
     *
     * @param  int    $riskID
     * @access public
     * @return array|bool
     */
    public function hangup($riskID)
    {
        $oldRisk = $this->getByID($riskID);
        
        $risk = fixer::input('post')
            ->setDefault('status','hangup')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::today())
            ->get();

        $this->dao->update(TABLE_RISK)->data($risk)->autoCheck()->where('id')->eq((int)$riskID)->exec();

        if(!dao::isError()) return common::createChanges($oldRisk, $risk);
        return false;
    }

    /**
     * Activate a risk.
     *
     * @param  int    $riskID
     * @access public
     * @return array|bool
     */
    public function activate($riskID)
    {
        $oldRisk = $this->getByID($riskID);
        
        $risk = fixer::input('post')
            ->setDefault('status','active')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::today())
            ->get();

        $this->dao->update(TABLE_RISK)->data($risk)->autoCheck()->where('id')->eq((int)$riskID)->exec();

        if(!dao::isError()) return common::createChanges($oldRisk, $risk);
        return false;
    }

    /**
     * Adjust the action is clickable.
     *
     * @param  int    $risk
     * @param  int    $action
     * @static
     * @access public
     * @return bool
     */
    public static function isClickable($risk, $action)
    {
        $action = strtolower($action);

        if($action == 'cancel' or $action == 'close') return $risk->status != 'canceled' and $risk->status != 'closed';
        if($action == 'hangup')   return $risk->status == 'active';
        if($action == 'activate') return $risk->status != 'active';

        return true;
    }

    /**
     * Build search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildSearchForm($queryID, $actionURL)
    {
        $this->config->risk->search['actionURL'] = $actionURL;
        $this->config->risk->search['queryID']   = $queryID;
        
        $this->loadModel('search')->setSearchParams($this->config->risk->search);
    }
}
