<?php
declare(strict_types=1);
/**
 * The control file of provider module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     provider
 * @link        https://www.zentao.net
 */
class provider extends control
{
    /**
     * 浏览服务列表。
     * Browse provider list.
     *
     * @param  string $orderBy
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse(string $orderBy = 'id_desc', int $recPerPage = 20, int $pageID = 1)
    {
        $this->loadModel('space')->setMenu();

        $this->view->title = $this->lang->provider->browse;
        $this->display();
    }
}
