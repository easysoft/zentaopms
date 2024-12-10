<?php
declare(strict_types=1);
/**
 * The control file of example module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      lijie
 * @package     epic
 * @link        http://www.zentao.net
 */
include dirname(__FILE__, 5).'/test/lib/ui.php';
class createChildStoryTester extends tester
{
    public function checkDisplay($storyName)
    {
        $browseStoryParam = array(
            'productID'  => '1',
            'branch'     => '',
            'browseType' => 'allstory',
            'param'      => '0',
            'storyType'  => 'epic'
        );
        $form = $this->initForm('product', 'browse', $browseStoryParam, 'appIframe-product');
        $form->dom->search($searchList = array("需求名称,包含,$storyName"));
        if($form->dom->{$this->lang->story->subdivide}->getText != '评审中和已关闭的需求，无法进行拆分操作') return $this->failed('细分按钮高亮不正确');
        return $this->success('细分按钮高亮正确');
    }
    /**
     * Batchcreate requirement.
     *
     * @param  string $type
     * @param  string $storyName
     * @access public
     * @return object
     */
    public function batchCreateDefault($storyName, $childName)
    {
        $browseStoryParam = array(
            'productID'  => '1',
