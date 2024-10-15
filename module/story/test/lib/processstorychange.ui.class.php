<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
/**
 *@copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 *@license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 *@author      lijie
 *@package     story
 *@link        http://www.zentao.net
 */
class  processStoryChangeTester extends tester
{
    /**
     * Check the child stories after parent story changed.
     *
     * @param string $storyName
     * @access public
     * @return object
     */
    public function processStoryChange($storyName)
    {
        /*变更需求后在父需求详情页点击子需求确认父需求变更按钮*/
        $form = $this->initForm('story', 'change', array('id' => 1), 'appIframe-product');
        $form->dom->title->setValue($storyName);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        $viewpage = $this->loadPage('story', 'view');
        $viewpage->wait(1);
        $viewpage->dom->okBtn->click();
        if($viewpage->childStatus->getText() != '激活') return $this->fail('确认父需求变更失败');

        return $this->success('确认父需求变更成功');
    }
}
