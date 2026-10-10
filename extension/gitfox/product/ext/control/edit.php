<?php
/**
 * GitFox 空间下编辑应用：复用 product/edit，同时保持空间上下文。
 * Edit an application of a space in the GitFox vision.
 *
 * @copyright   Copyright 2009-2026 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @package     product
 * @link        https://www.zentao.net
 */
helper::importControl('product');
class myProduct extends product
{
    /**
     * 编辑应用。
     * Edit an application.
     *
     * @param  int    $productID
     * @param  string $action
     * @param  string $extra
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function edit(int $productID, string $action = 'edit', string $extra = '', int $programID = 0)
    {
        $spaceID = (int)$this->get->spaceID;
        if($spaceID)
        {
            $this->app->tab = 'space';
            $this->loadModel('space')->setMenu($spaceID);
            $this->view->spaceID = $spaceID;
        }

        return parent::edit($productID, $action, $extra, $programID);
    }
}
