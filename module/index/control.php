<?php
/**
 * The control file of index module of ZenTaoPMS.
 *
 * When requests the root of a website, this index module will be called.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: control.php 5036 2013-07-06 05:26:44Z wyd621@gmail.com $
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
    public function index()
    {
        $this->locate($this->createLink('my', 'index'));
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
