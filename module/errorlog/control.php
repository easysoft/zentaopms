<?php
declare(strict_types=1);
/**
 * The control file of errorlog module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2026 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      ZenTao Team
 * @package     errorlog
 * @link        https://www.zentao.net
 * @property    errorlogModel $errorlog
 * @property    errorlogZen   $errorlogZen
 */
class errorlog extends control
{
    /**
     * 错误日志列表。
     * Browse error logs.
     *
     * @param  string $type
     * @param  string $queryID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse(string $type = 'all', string $queryID = '', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $queryID   = $type == 'bysearch' ? $queryID : 0;
        $actionURL = $this->createLink('errorlog', 'browse', "type=bysearch&queryID=myQueryID&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}");
        $this->errorlogZen->buildSearchForm($this->config->errorlog->search, $queryID, $actionURL);
        $query   = $type == 'bysearch' ? $this->errorlogZen->getErrorLogQuery((int)$queryID) : '';
        $logList = $this->errorlog->getList($query, $orderBy, $pager);

        foreach($logList as $log)
        {
            $log->levelName = isset($this->lang->errorlog->levelList[$log->level]) ? $this->lang->errorlog->levelList[$log->level] : $log->level;
            $log->levelType = $this->errorlog->getLevelType((int)$log->level);
        }

        $this->view->title    = $this->lang->errorlog->common . $this->lang->hyphen . $this->lang->errorlog->browse;
        $this->view->logList  = $logList;
        $this->view->pager    = $pager;
        $this->view->orderBy  = $orderBy;
        $this->view->type     = $type;
        $this->view->queryID  = $queryID;
        $this->display();
    }

    /**
     * 查看错误日志详情。
     * View an error log.
     *
     * @param  int $id
     * @access public
     * @return void
     */
    public function view(int $id)
    {
        $log = $this->errorlog->getByID($id);
        if(!$log) return $this->sendError($this->lang->errorlog->notFound);

        $log->levelName = isset($this->lang->errorlog->levelList[$log->level]) ? $this->lang->errorlog->levelList[$log->level] : $log->level;

        $this->view->title = $this->lang->errorlog->view;
        $this->view->log   = $log;
        $this->display();
    }

    /**
     * 根据请求ID获取错误日志。
     * Get error log by request id.
     *
     * @param  string $requestID
     * @access public
     * @return void
     */
    public function ajaxGetLog(string $requestID = '')
    {
        if(empty($requestID)) return $this->send(array('result' => 'fail', 'message' => $this->lang->errorlog->notFound));

        $logs = $this->errorlog->getByRequestID($requestID);
        if(empty($logs)) return $this->send(array('result' => 'fail', 'message' => $this->lang->errorlog->notFound));

        /* 只允许查看自己请求产生的错误日志，管理员可在后台查看任意日志。Only allow viewing error logs of own requests, admins can view all logs in the backend. */
        $account = isset($this->app->user->account) ? $this->app->user->account : '';
        if($logs[0]->account != $account) return $this->send(array('result' => 'fail', 'message' => $this->lang->errorlog->notFound));

        foreach($logs as $log)
        {
            $log->levelName = isset($this->lang->errorlog->levelList[$log->level]) ? $this->lang->errorlog->levelList[$log->level] : $log->level;
            $log->levelType = $this->errorlog->getLevelType((int)$log->level);
        }

        return $this->send(array('result' => 'success', 'data' => $logs));
    }

    /**
     * 删除一条错误日志。
     * Delete an error log.
     *
     * @param  int $id
     * @access public
     * @return void
     */
    public function delete(int $id)
    {
        $this->errorlog->deleteByID($id);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        return $this->send(array('result' => 'success', 'message' => $this->lang->deleteSuccess, 'load' => true));
    }

    /**
     * 批量删除错误日志。
     * Batch delete error logs.
     *
     * @access public
     * @return void
     */
    public function batchDelete()
    {
        $idList = zget($_POST, 'idList', array());
        if(empty($idList)) return $this->send(array('result' => 'fail', 'load' => true));

        $this->errorlog->deleteByIDs($idList);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        return $this->send(array('result' => 'success', 'message' => $this->lang->deleteSuccess, 'load' => true));
    }

    /**
     * 设置保存天数。
     * Set save days.
     *
     * @access public
     * @return void
     */
    public function setting()
    {
        if($_POST)
        {
            $days = form::data()->get()->days;
            if(!validater::checkInt($days, 1, $this->config->errorlog->maxSaveDays)) return $this->send(array('result' => 'fail', 'message' => array('days' => sprintf($this->lang->errorlog->notice->int, $this->lang->errorlog->days))));

            $this->loadModel('setting')->setItem('system.errorlog.saveDays', (string)$days);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $this->view->title = $this->lang->errorlog->setting;
        $this->display();
    }

    /**
     * 删除过期错误日志。
     * Delete overdue error logs.
     *
     * @access public
     * @return bool
     */
    public function deleteLog()
    {
        $date = date(DT_DATE1, strtotime("-{$this->config->errorlog->saveDays} days"));
        $this->errorlog->deleteByDate($date);
        return !dao::isError();
    }
}
