<?php
/**
 * The control file of extension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     extension
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class extension extends control
{
    /**
     * Browse extensions.
     *
     * @param  string $type     browse type.
     * @access public
     * @return void
     */
    public function browse($type = 'installed')
    {
        $this->view->header->title = $this->lang->extension->browse;
        $this->view->position[]    = $this->lang->extension->browse;
        $this->display();
    }

    public function download()
    {
    }

    public function upload()
    {
    }

    public function install($extension)
    {
    }

    public function uninstall()
    {
    }

    public function activate()
    {
    }

    public function deactivate()
    {
    }
}
