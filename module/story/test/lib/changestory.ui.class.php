<?php
declare(strict_types=1);
/**
 * The control file of example module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      lijie
 * @package     story
 * @link        http://www.zentao.net
 */
include dirname(__FILE__, 5).'/test/lib/ui.php';
class changeStoryTester extends tester
{
    /**
     *  change a story.
     *
     * @param  string $storyName $reviewer
     * @access public
     * @return object
     */
    public function changeStory($storyName)
    {
        $form = $this->initForm('story', 'change', array('id' => 1), 'appIframe-product');
        $form->dom->title->setValue($storyName);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        if($this->response('method') != 'view')
        {
            if($form->dom->alertmodal('text') == '『研发需求名称』不能为空。') return $this->success('变更需求表单页面提示信息正确');
            return $this->failed('变更需求表单页面提示信息不正确');
        }

        /* 跳转到需求列表页面搜索创建需求并进入该需求详情页。 */

        $viewPage = $this->loadPage('story', 'view');
        if($viewPage->dom->storyName->getText() != $storyName) return $this->failed('需求名称不正确');
        if($viewPage->dom->status->getText()    != '评审中') return $this->failed('需求状态不正确');

        return $this->success('变更需求成功');
}
}
