<?php
class xuanxuanAdmin extends adminModel
{
    public function blockStatus($block = null)
    {   
        if(empty($block)) return false;
        $userList = count($this->loadModel('chat')->getUserList('online'));
        $xxdStartDate = $this->getXxdStart(); 
        $setServerBtn = html::a(helper::createLink('setting', 'xuanxuan', 'type=edit'), $this->lang->admin->xuanxuanSetting, '', 'class="btn"');
        if(empty($xxdStartDate)) $xxdStartDate = $this->lang->noData;

        $html =  '<div class="table-row statusBlock">';
        $html .= "<div class='col date'><p>{$this->lang->admin->xxdStartDate}</p><h2>{$xxdStartDate}</h2></div>";
        $html .= "<div class='col'><p>{$this->lang->admin->countUsers}  </p><h2>{$userList}    </h2></div>";
        $html .= "<div class='col server'><p>{$this->lang->admin->setServer}   </p><h2>{$setServerBtn}</h2></div>";
        $html .= '</div>';

        return $html;
    }   

    public function blockStatistics($block = null)
    {
        $this->loadModel('chat');
        $now = helper::now();
        $users    = count($this->chat->getUserList());
        $groups   = count($this->chat->getChatGroupPairs());
        $messages = $this->chat->getMessageNumByTimeFrame();

        $fileSize = $this->getXxcAllFileSize();
        if($fileSize == 0)
        {
            $fileSize .= '<small> KB</small>';
        }
        else if($fileSize > $this->lang->admin->sizeType['G'])
        {
            $fileSize = round($fileSize / $this->lang->admin->sizeType['G'], 2) . '<small> GB</small>';
        }
        else if($fileSize > $this->lang->admin->sizeType['M'])
        {
            $fileSize = round($fileSize / $this->lang->admin->sizeType['M'], 2) . '<small> MB</small>';
        }
        else if($fileSize > $this->lang->admin->sizeType['K'])
        {
            $fileSize = round($fileSize / $this->lang->admin->sizeType['K'], 2) . '<small> KB</small>';
        }

        if(empty($block)) return false;
        $html =  '<div class="table-row statisticsBlock">';
        $html .= "<div class='col'><p>{$this->lang->admin->totalUsers}</p><h2>{$users}</h2></div>";
        $html .= "<div class='col'><p>{$this->lang->admin->totalGroups}  </p><h2>{$groups}    </h2></div>";
        $html .= "<div class='col'><p>{$this->lang->admin->fileSize}   </p><h2>{$fileSize}</h2></div>";
        $html .=  '</div><div class="table-row statisticsBlock">';
        $html .= "<div class='col'><p>{$this->lang->admin->message['total']}</p><h2>{$messages->total}</h2></div>";
        $html .= "<div class='col'><p>{$this->lang->admin->message['day']}  </p><h2>{$messages->day}    </h2></div>";
        $html .= "<div class='col'><p>{$this->lang->admin->message['hour']}   </p><h2>{$messages->hour}</h2></div>";
        $html .= '</div>';
        return $html;
    }

    public function getXxdStart()   
    {
        return $this->dao->select('date')->from(TABLE_ACTION)
            ->where('objectType')->eq('xxd')
            ->andWhere('action')->eq('start')
            ->orderBy('id desc')            
            ->fetch('date');   
    }

    public function getXxcAllFileSize()
    {
        $xxcFiles = $this->dao->select('size')->from(TABLE_FILE)
            ->where('objectType')->eq('chat')
            ->fetchPairs();

        if(empty($xxcFiles)) return 0;
        return array_sum($xxcFiles);
    }
}
