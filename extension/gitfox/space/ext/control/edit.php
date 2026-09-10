<?php
/**
 * GitFox 空间下编辑应用：由 space 模块承载，复用应用模块的表单与保存逻辑。
 * Edit an application of a space in the GitFox vision.
 *
 * @copyright   Copyright 2009-2026 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @package     space
 * @link        https://www.zentao.net
 */
helper::importControl('space');
class mySpace extends space
{
    /**
     * 编辑空间下的应用。
     * Edit an application of a space.
     *
     * @param  int $spaceID
     * @param  int $productID
     * @access public
     * @return void
     */
    public function edit(int $spaceID = 0, int $productID = 0)
    {
        $this->loadModel('space')->setMenu($spaceID);
        $this->app->loadLang('product');
        $this->app->loadConfig('product');
        $this->loadModel('product');

        if(!empty($_POST))
        {
            $productData = form::data($this->config->product->form->edit, $productID)
                ->add('id', $productID)
                ->setIF($this->post->acl == 'open', 'whitelist', '')
                ->get();
            $productData = $this->loadModel('file')->processImgURL($productData, $this->config->product->editor->edit['id'], $this->post->uid);

            /* 应用模块的编辑页只提交部分字段，未提交字段保持数据库原值。*/
            $product = $this->product->fetchByID($productID);
            if($product)
            {
                foreach($this->config->product->form->edit as $field => $fieldConfig)
                {
                    if($this->post->$field !== false) continue;
                    if(!property_exists($product, $field)) continue;

                    $productData->$field = $product->$field;
                }
            }

            $this->product->update($productID, $productData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* 与应用模块一致：同步 zt_system 中的应用名称。*/
            $product = $this->product->fetchByID($productID);
            if($product) $this->product->updateSystemsByProduct($productID, $product->name);

            return $this->send(array
            (
                'result'  => 'success',
                'message' => $this->lang->saveSuccess,
                'load'    => $this->createLink('space', 'apps', "spaceID={$spaceID}"),
                'changes' => array('type' => 'update', 'objectType' => 'product', 'objectList' => array($productID))
            ));
        }

        $product = $this->product->getByID($productID);
        if(!$product) return $this->locate($this->createLink('space', 'apps', "spaceID={$spaceID}"));

        $this->view->title   = $this->lang->product->edit . $this->lang->hyphen . $product->name;
        $this->view->product = $product;
        $this->view->spaceID = $spaceID;

        /* 复用应用模块的编辑表单视图。*/
        $this->display();
    }
}
