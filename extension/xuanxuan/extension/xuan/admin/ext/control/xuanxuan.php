<?php
class admin extends control
{
    /**
     * Configuration of xuanxuan.
     *
     * @access public
     * @return void
     */
    public function xuanxuan()
    {
        $this->app->loadLang('client');

        $xxdStatus = $this->loadModel('im')->getXxdStatus();
        $lastPoll  = $this->loadModel('setting')->getItem("owner=system&module=common&section=xxd&key=lastPoll");
        $polling   = !empty($this->config->xuanxuan->pollingInterval) ? $this->config->xuanxuan->pollingInterval : 0;
        $xxdStart  = !empty($this->config->xxd->start) ? $this->config->xxd->start : '';

        $runtimeLabel = $this->lang->client->xxdStartDate;
        $runtimeValue = $xxdStart ?: $this->lang->client->noData;
        if(!empty($lastPoll) && $xxdStatus == 'online' && $polling < 600 && !empty($this->config->xxd))
        {
            $runtimeLabel = $this->lang->client->xxdRunTime;
            $runtimeValue = $xxdStart ? $this->im->getXxdRunTime(strtotime(helper::now()) - strtotime($xxdStart)) : $this->lang->client->noData;
        }

        $this->view->title        = $this->lang->im->common;
        $this->view->onlineUsers  = $xxdStatus == 'offline' ? 0 : count($this->loadModel('im')->userGetList('online'));
        $this->view->totalUsers   = count($this->loadModel('im')->userGetList());
        $this->view->totalGroups  = count($this->im->chatGetGroupPairs());
        $this->view->messages     = $this->im->messageGetCountForBlock();
        $this->view->fileSize     = $this->admin->getXxcAllFileSize();
        $this->view->polling      = $polling ? $polling . 's' : $this->lang->client->noData;
        $this->view->lastPoll     = $lastPoll;
        $this->view->xxdStatus    = $xxdStatus;
        $this->view->runtimeLabel = $runtimeLabel;
        $this->view->runtimeValue = $runtimeValue;
        $this->display();
    }
}
