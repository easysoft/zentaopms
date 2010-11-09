<?php
/**
 * The control file of admin module of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class admin extends control
{
    /* 首页。*/
    public function index($tab = 'index')
    {
        $this->locate($this->createLink('action', 'trash'));
    }
}
