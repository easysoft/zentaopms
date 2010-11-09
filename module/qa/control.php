<?php
/**
 * The control file of qa module of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     qa
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class qa extends control
{
    public function index()
    {
        $this->locate($this->createLink('bug', 'index'));
    }
}
