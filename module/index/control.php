<?php
/**
 * The control file of index module of ZenTaoPMS.
 *
 * When requests the root of a website, this index module will be called.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class index extends control
{
    /**
     * Construct function, load project, product.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * The index page of whole zentao system.
     * 
     * @access public
     * @return void
     */
    public function index($type='')
    {
        if(empty($type))$this->locate($this->createLink('my', 'index'));

        if($_POST)
        {
            $this->loadModel('setting')->setItem('system', 'common', '', 'flow', $this->post->flow);
            if($this->post->flow != 'full') die(js::locate($this->createLink('extension', 'install', "extension={$this->config->index->flow2Ext[$this->post->flow]}"), 'parent'));
            die(js::reload( 'parent.parent'));
        }
        $this->display();
    }

    /**
     * Just test the extension engine.
     * 
     * @access public
     * @return void
     */
    public function testext()
    {
        echo $this->fetch('misc', 'getsid');
    }
}
