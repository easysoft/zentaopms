<?php
declare(strict_types=1);
/**
 * The control file of devopsspace module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li
 * @package     devopsspace
 * @link        https://www.zentao.net
 */
class devopsspace extends control
{
    /**
     * 空间列表。
     * Browse space.
     *
     * @param  int $recTotal
     * @param  int $recPerPage
     * @param  int $pageID
     * @access public
     * @return void
     */
    public function browse(int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $spaces = $this->devopsspace->getList($pager);
        foreach($spaces as &$space) $space->desc = str_replace('&nbsp;', ' ', strip_tags(htmlspecialchars_decode($space->desc)));

        $this->view->title  = $this->lang->devopsspace->browse;
        $this->view->spaces = $spaces;
        $this->view->pager  = $pager;
        $this->display();
    }

    /**
     * 创建空间。
     * Create space.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $formData = form::data($this->config->devopsspace->form->create)
                ->setDefault('createdBy', $this->app->user->account)
                ->get();

            $spaceID = $this->devopsspace->create($formData);
            if(dao::isError()) return $this->sendError(dao::getError());
            if($spaceID)
            {
                $this->loadModel('action')->create('devopsspace', $spaceID, 'created');
                if(dao::isError()) return $this->sendError(dao::getError());
            }

            $this->sendSuccess(array('load' => helper::createLink('devopsspace', 'browse')));
        }
        $this->view->title = $this->lang->devopsspace->create;
        $this->view->users = $this->loadModel('user')->getPairs('noletter|noempty|nodeleted|noclosed');
        $this->display();
    }
}
