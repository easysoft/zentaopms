<?php
class xuanxuanAdmin extends adminModel
{
    public function blockStatus($block = null)
    {   
        if(empty($block)) return false;

        $this->app->loadLang('client');

        $now             = helper::now();
        $polling         = empty($this->config->xuanxuan->pollingInterval) ? $this->lang->client->noData : $this->config->xuanxuan->pollingInterval . 's';
        $lastPoll        = $this->loadModel('setting')->getItem("owner=system&module=common&section=xxd&key=lastPoll");
        $xxdStatus       = $this->loadModel('im')->getXxdStatus();
        $onlineUserCount = $xxdStatus == 'offline' ? 0 : count($this->loadModel('im')->userGetList('online'));
        $setServerBtn    = html::a(helper::createLink('setting', 'xuanxuan', 'type=edit'), $this->lang->client->set, '', 'class="btn"');
        $xxdStartDate    = zget($this->config->xxd, 'start', $this->lang->client->noData);

        if(!empty($lastPoll) && $xxdStatus == 'online' && !empty($this->config->xxd) && $polling < 600)
        {
            $xxdRunTime = $xxdStartDate === $this->lang->client->noData ? $this->lang->client->noData : $this->im->getXxdRunTime(strtotime($now) - strtotime($xxdStartDate));
            $xxdRunHtml = "<div class='col date' style='height:100px;padding-top:20px;width:50%;'><p>{$this->lang->client->xxdRunTime}</p><h2>{$xxdRunTime}</h2></div>";
        }
        else
        {
            $xxdRunHtml = "<div class='col date' style='height:100px;padding-top:20px;width:50%;'><p>{$this->lang->client->xxdStartDate}</p><h2>{$xxdStartDate}</h2></div>";
        }

        $html  = '<div class="table-row statusBlock">';
        $html .= '<div class="pull-left" style="width:100%;text-align:center;">';
        $html .= "<div class='col date' style='height:100px;padding-top:20px;width:50%;'><p>{$this->lang->client->xxdStatus}</p><h2>{$this->lang->client->xxdStatusList[$xxdStatus]}</h2></div>";
        $html .= $xxdRunHtml;
        $html .= "<div class='col' style='height:100px;padding-top:20px;width:50%;'><p>{$this->lang->client->polling}</p><h2>{$polling}</h2></div>";
        $html .= "<div class='col' style='height:100px;padding-top:20px;width:50%;'><p>{$this->lang->client->countUsers}</p><h2>{$onlineUserCount}</h2></div>";
        $html .= "</div>";
        $html .= "<div class='col server' style='height:200px;width:30%;text-align:center;padding-top:56px;'><p>{$this->lang->client->setServer}</p><h2>{$setServerBtn}</h2></div>";
        $html .= '</div>';

        echo $html;
    }

    public function blockStatistics($block = null)
    {
        $this->loadModel('im');
        $this->app->loadLang('client');

        $now      = helper::now();
        $users    = count($this->im->userGetList());
        $groups   = count($this->im->chatGetGroupPairs());
        $messages = $this->im->messageGetCountForBlock();
        $fileSize = $this->getXxcAllFileSize();

        if($fileSize == 0)
        {
            $fileSize .= '<small> KB</small>';
        }
        else if($fileSize > $this->lang->client->sizeType['G'])
        {
            $fileSize = round($fileSize / $this->lang->client->sizeType['G'], 2) . '<small> GB</small>';
        }
        else if($fileSize > $this->lang->client->sizeType['M'])
        {
            $fileSize = round($fileSize / $this->lang->client->sizeType['M'], 2) . '<small> MB</small>';
        }
        else if($fileSize > $this->lang->client->sizeType['K'])
        {
            $fileSize = round($fileSize / $this->lang->client->sizeType['K'], 2) . '<small> KB</small>';
        }

        $html  = '<div class="table-row statisticsBlock">';
        $html .= "<div class='col'><p>{$this->lang->client->totalUsers}</p><h2>{$users}</h2></div>";
        $html .= "<div class='col'><p>{$this->lang->client->totalGroups}</p><h2>{$groups}</h2></div>";
        $html .= "<div class='col'><p>{$this->lang->client->fileSize}</p><h2>{$fileSize}</h2></div>";
        $html .= '</div><div class="table-row statisticsBlock">';
        $html .= "<div class='col'><p>{$this->lang->client->message['total']}</p><h2>{$messages->total}</h2></div>";
        $html .= "<div class='col'><p>{$this->lang->client->message['day']}</p><h2>{$messages->day}</h2></div>";
        $html .= "<div class='col'><p>{$this->lang->client->message['hour']}</p><h2>{$messages->hour}</h2></div>";
        $html .= '</div></tbody></table>';

        echo $html;
    }

    /**
     * Get XXC All file size.
     * 
     * @access public
     * @return int
     */
    public function getXxcAllFileSize()
    {
        $xxcFiles = $this->dao->select('size')->from(TABLE_FILE)
            ->where('objectType')->eq('chat')
            ->fetchPairs();

        if(empty($xxcFiles)) return 0;
        return array_sum($xxcFiles);
    }
}
