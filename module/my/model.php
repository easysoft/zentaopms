<?php
/**
 * The model file of dashboard module of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class myModel extends model
{
    /* 处理菜单。*/
    public function setMenu()
    {
        $this->lang->my->menu->account = sprintf($this->lang->my->menu->account, $this->app->user->realname);
    }
}
