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
include dirname(__FILE__, 6).'/test/lib/ui.php';
class changeStoryTester extends tester
{
    /**
     *  change a story.
     *
     * @param  string $storyName $reviewer
     * @access public
     * @return object
     */
    public function changeStory($storyName, $reviewer)
    {
        $form = $this->initForm('story', 'change', array('id' => 1), 'appIframe-product');
        $form->dom->title->setValue($storyName);
        $form->wait(1);
        $form->dom->{'reviewer[]'}->multiPicker($reviewer);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        if($this->response('method') != 'view' and $reviewer == array())
        {
            if($form->dom->alertmodal('text') == '『评审人员』不能为空。') return $this->success('变更需求表单页面评审人不为空提示信息正确');
            return $this->failed('变更需求表单页面提示信息不正确');
        }

        if($this->response('method') != 'view' and $storyName == null)
        {
            if($form->dom->alertmodal('text') == '『研发需求名称』不能为空。') return $this->success('变更需求表单页面需求名称不为空提示信息正确');
            return $this->failed('变更需求表单页面提示信息不正确');
        }

        /* 跳转到需求列表页面搜索创建需求并进入该需求详情页。 */

        $viewPage = $this->initForm('story', 'view', array('id' => 1), 'appIframe-product');

        if($viewPage->dom->storyName->getText() != $storyName) return $this->failed('需求名称不正确');
        if($viewPage->dom->status->getText()    != '评审中') return $this->failed('需求状态不正确');

        return $this->success('变更研发需求成功');
    }

    /**
     *  change an epic.
     *
     * @param  string $storyName
     * @param  array $reviewer
     * @access public
     * @return object
     */
    public function changeEpic($storyName, $reviewer)
    {
        $form = $this->initForm('epic', 'change', array('id' => 3), 'appIframe-product');
        $form->dom->title->setValue($storyName);
        $form->wait(1);
        $form->dom->{'reviewer[]'}->multiPicker($reviewer);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        if($this->response('method') != 'view' and $reviewer == array())
        {
            if($form->dom->alertmodal('text') == '『评审人员』不能为空。') return $this->success('变更需求表单页面评审人不为空提示信息正确');
            return $this->failed('变更需求表单页面提示信息不正确');
        }
        if($this->response('method') != 'view' and $storyName == null)
        {
            if($form->dom->alertmodal('text') == '『业务需求名称』不能为空。') return $this->success('变更需求表单页面需求名称不为空提示信息正确');
            return $this->failed('变更需求表单页面提示信息不正确');
        }

        /* 跳转到需求列表页面搜索创建需求并进入该需求详情页。 */

        $viewPage = $this->initForm('epic', 'view', array('id' => 3), 'appIframe-product');
        if($viewPage->dom->storyName->getText() != $storyName) return $this->failed('需求名称不正确');
        if($viewPage->dom->status->getText()    != '评审中') return $this->failed('需求状态不正确');

        return $this->success('变更业务需求成功');
    }

/**
     *  change a requirement.
     *
     * @param  string $storyName
     * @param  array $reviewer
     * @access public
     * @return object
     */
    public function changeRequirement($storyName, $reviewer)
    {
        $form = $this->initForm('requirement', 'change', array('id' => 2), 'appIframe-product');
        $form->wait(1);
        $form->dom->{'reviewer[]'}->multiPicker($reviewer);
        $form->dom->title->setValue($storyName);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        if($this->response('method') != 'view' and $reviewer == array())
        {
            if($form->dom->alertmodal('text') == '『评审人员』不能为空。') return $this->success('变更需求表单页面评审人不为空提示信息正确');
            return $this->failed('变更需求表单页面提示信息不正确');
        }
        if($this->response('method') != 'view' and $storyName == null)
        {
            if($form->dom->alertmodal('text') == '『用户需求名称』不能为空。') return $this->success('变更需求表单页面需求名称不为空提示信息正确');
            return $this->failed('变更需求表单页面提示信息不正确');
        }

        /* 跳转到需求列表页面搜索创建需求并进入该需求详情页。 */

        $viewPage = $this->initForm('requirement', 'view', array('id' => 2), 'appIframe-product');
        if($viewPage->dom->storyName->getText() != $storyName) return $this->failed('需求名称不正确');
        if($viewPage->dom->status->getText()    != '评审中') return $this->failed('需求状态不正确');

        return $this->success('变更用户需求成功');
    }
}
