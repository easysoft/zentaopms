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

        $this->view->title  = $this->lang->devopsspace->browse;
        $this->view->spaces = $spaces;
        $this->view->pager  = $pager;
        $this->display();
    }
}
