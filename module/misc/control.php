<?php
/**
 * The control file of misc of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     misc
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class misc extends control
{
    /* 通过隐藏的iframe定时刷新此页面，保证session不过期。*/
    public function ping()
    {
        if(mt_rand(0, 10) == 5) $this->loadModel('setting')->setSN();
        die("<html><head><meta http-equiv='refresh' content='300' /></head><body></body></html>");
    }

    /* 显示phpinfo信息。*/
    public function phpinfo()
    {
        die(phpinfo());
    }

    /* 关于禅道。*/
    public function about()
    {
        $this->display();
        exit;
    }

    public function updateNL()
    {
        $this->loadModel('upgrade')->updateNL();
    }
}
