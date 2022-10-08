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
        $this->loadModel('im');

        $block = new stdclass();
        $block->title = $this->lang->admin->blockStatus;
        $block->block = 'status';
        $block->grid  = '6';
        $blocks[] = $block;

        $block = new stdclass();
        $block->title = $this->lang->admin->blockStatistics;
        $block->block = 'statistics';
        $block->grid  = '6';
        $blocks[] = $block;

        foreach($blocks as $key => $block)
        {
            $block->params = new stdclass();
            $block->params->account = $this->app->user->account;
            $block->params->uid     = $this->app->user->id;

            $query            = array();
            $query['mode']    = 'getblockdata';
            $query['blockid'] = $block->block;
            $query['hash']    = ''; 
            $query['lang']    = $this->app->getClientLang();
            $query['sso']     = ''; 
            if(isset($block->params)) $query['param'] = base64_encode(json_encode($block->params));
        }

        $this->view->title      = $this->lang->im->common;
        $this->view->position[] = html::a($this->createLink('admin', 'xuanxuan'), $this->lang->im->common);

        $this->view->blocks = $blocks;
        $this->display();
    }
}
