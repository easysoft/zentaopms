<?php
/**
 * The control file of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class release extends control
{
    /**
     * Common actions.
     * 
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function commonAction($productID)
    {
        $this->loadModel('product');
        $this->view->product = $this->product->getById($productID);
        $this->view->position[] = html::a($this->createLink('product', 'browse', "productID={$this->view->product->id}"), $this->view->product->name);
        $this->product->setMenu($this->product->getPairs(), $productID);
    }

    /**
     * Browse releases.
     * 
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function browse($productID)
    {
        $this->commonAction($productID);
        $this->session->set('releaseList', $this->app->getURI(true));
        $this->view->header->title = $this->lang->release->browse;
        $this->view->position[]    = $this->lang->release->browse;
        $this->view->releases      = $this->release->getList($productID);
        $this->display();
    }

    /**
     * Create a release.
     * 
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function create($productID)
    {
        if(!empty($_POST))
        {
            $releaseID = $this->release->create($productID);
            if(dao::isError()) die(js::error(dao::getError()));
            $this->loadModel('action')->create('release', $releaseID, 'opened');
            die(js::locate(inlink('view', "releaseID=$releaseID"), 'parent'));
        }

        $this->commonAction($productID);
        $this->view->header->title = $this->lang->release->create;
        $this->view->position[]    = $this->lang->release->create;
        $this->view->builds        = $this->loadModel('build')->getProductBuildPairs($productID);
        unset($this->view->builds['trunk']);
        $this->display();
    }

    /**
     * Edit a release.
     * 
     * @param  int    $releaseID 
     * @access public
     * @return void
     */
    public function edit($releaseID)
    {
        if(!empty($_POST))
        {
            $changes = $this->release->update($releaseID);
            if(dao::isError()) die(js::error(dao::getError()));
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('release', $releaseID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
            die(js::locate(inlink('view', "releaseID=$releaseID"), 'parent'));
        }

        $release = $this->release->getById((int)$releaseID);
        $this->commonAction($release->product);

        $this->view->header->title = $this->lang->release->edit;
        $this->view->position[]    = $this->lang->release->edit;
        $this->view->release       = $release;
        $this->view->builds        = $this->loadModel('build')->getProductBuildPairs($release->product);
        unset($this->view->builds['trunk']);
        $this->display();
    }
                                                          
    /**
     * View a release.
     * 
     * @param  int    $releaseID 
     * @access public
     * @return void
     */
    public function view($releaseID)
    {
        $release = $this->release->getById((int)$releaseID);
        if(!$release) die(js::error($this->lang->notFound) . js::locate('back'));

        $this->commonAction($release->product);

        $this->view->header->title = $this->lang->release->view;
        $this->view->position[]    = $this->lang->release->view;
        $this->view->release       = $release;
        $this->view->actions       = $this->loadModel('action')->getList('release', $releaseID);
        $this->view->users         = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }
 
    /**
     * Delete a release.
     * 
     * @param  int    $releaseID 
     * @param  string $confirm      yes|no
     * @access public
     * @return void
     */
    public function delete($releaseID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->release->confirmDelete, $this->createLink('release', 'delete', "releaseID=$releaseID&confirm=yes")));
        }
        else
        {
            $this->release->delete(TABLE_RELEASE, $releaseID);
            die(js::locate($this->session->releaseList, 'parent'));
        }
    }
}
