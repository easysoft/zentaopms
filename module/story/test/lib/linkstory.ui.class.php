<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
/**
 *@copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 *@license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 *@author      lijie
 *@package     story
 *@link        http://www.zentao.net
 */
class linkStoryTester extends tester
{
    /**
     * Check the linkstories after link story.
     *
     * @param string $storyID
     * @access public
     * @return object
     */
    public function linkStory($storyID)
    {
        /*进入需求详情页点击关联需求*/
        $form = $this->initForm('story', 'view', array('id' => $storyID), 'appIframe-product');  //进入研发需求详情页
        $form->dom->btn($this->lang->story->linkStory)->click();  //点击关联需求按钮
        $form->wait(1);

        /*关联需求页面选择关联的需求*/
        $form->dom->searchBox->click();
        $form->wait(1);
        $form->dom->selectAll->click();
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        /*查看关联的需求*/
        $viewpage = $this->loadPage('story', 'view');
        if($viewpage->dom->firLinkStories->getText()!= '激活业务需求') return $this->success('关联的需求不正确');
        if($viewpage->dom->secLinkStories->getText()!= '激活用户需求') return $this->success('关联的需求不正确');

        return $this->success('关联需求成功');
    }
}
