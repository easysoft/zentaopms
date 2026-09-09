<?php
/**
 * GitFox 的“应用”入口复用 product 模块，删除应用后返回应用列表。
 * The delete method of product module for the GitFox vision, redirects back to the application list.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @package     product
 * @link        https://www.zentao.net
 */
helper::importControl('product');
class myProduct extends product
{
    /**
     * 删除应用。
     * Delete an application.
     *
     * @param  int    $productID
     * @param  string $confirm   no|yes
     * @access public
     * @return void
     */
    public function delete(int $productID, string $confirm = 'no')
    {
        if($this->config->vision != 'gitfox') return parent::delete($productID, $confirm);

        if($confirm == 'no')
        {
            $confirmURL = $this->createLink('product', 'delete', "productID={$productID}&confirm=yes");
            return $this->send(array('result' => 'success', 'load' => array('confirm' => $this->lang->product->confirmDelete, 'confirmed' => $confirmURL)));
        }

        // 删除 product 和对应 systems
        if(!$this->product->deleteProductWithSystems($productID))
        {
            return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }

        $this->session->set('product', '');

        $message = $this->executeHooks($productID);
        if($message) $this->lang->saveSuccess = $message;

        return $this->sendSuccess(array('load' => $this->createLink('product', 'browse')));
    }
}
