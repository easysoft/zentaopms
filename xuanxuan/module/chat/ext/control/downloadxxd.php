<?php
class chat extends control
{
    public function downloadXXD()
    {   
        if($_POST)
        {   
            $setting = fixer::input('post')->get();
            $result  = $this->loadModel('setting')->setItems('system.common.xxd', $setting);
            $this->chat->downloadXXD($setting);
        }   

        if(!isset($this->config->xxd)) $this->config->xxd = new stdclass();
        $this->view->title = $this->lang->chat->downloadXXD;
        $this->display();
    }
}
