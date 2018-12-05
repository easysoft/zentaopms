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
        $this->app->loadLang('chat');
        if($_POST)
        {
            if(strlen($this->post->key) != 32 or !validater::checkREG($this->post->key, '|^[A-Za-z0-9]+$|')) $this->send(array('result' => 'fail', 'message' => array('key' => $this->lang->chat->errorKey)));

            $data = new stdclass();
            $data->turnon = $this->post->turnon;
            $data->key    = $this->post->key;
            if($data) $this->loadModel('setting')->setItems('system.xuanxuan', $data);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $locate = inlink('xuanxuan');
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate));
        }

        $this->view->position[] = $this->lang->chat->settings;
        $this->view->title      = $this->lang->chat->settings;
        $this->view->turnon     = isset($this->config->xuanxuan->turnon) ? $this->config->xuanxuan->turnon : 1;
        $this->display();
    }
}
