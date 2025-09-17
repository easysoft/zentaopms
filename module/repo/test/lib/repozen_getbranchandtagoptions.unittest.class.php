<?php
declare(strict_types = 1);
class repoZenGetBranchAndTagOptionsTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('repo');
        $this->objectTao   = $tester->loadTao('repo');
    }

    /**
     * Test getBranchAndTagOptions method.
     *
     * @param  object $scm
     * @access public
     * @return array
     */
    public function getBranchAndTagOptionsTest($scm)
    {
        if(dao::isError()) return dao::getError();

        if(empty($scm) || !is_object($scm)) return false;

        // 模拟语言配置
        $lang = new stdClass();
        $lang->repo = new stdClass();
        $lang->repo->branch = '分支';
        $lang->repo->tag = '标签';

        // 初始化返回的选项结构
        $options = array(
            array('text' => $lang->repo->branch, 'items' => array(), 'disabled' => true),
            array('text' => $lang->repo->tag,    'items' => array(), 'disabled' => true)
        );

        // 获取分支数据
        $branches = array();
        if(isset($scm->branches) && is_array($scm->branches))
        {
            $branches = $scm->branches;
        }

        // 构建分支选项
        foreach($branches as $branch)
        {
            $options[0]['items'][] = array('text' => $branch, 'value' => $branch, 'key' => $branch);
        }

        // 获取标签数据
        $tags = array();
        if(isset($scm->tags) && is_array($scm->tags))
        {
            $tags = $scm->tags;
        }

        // 构建标签选项
        foreach($tags as $tag)
        {
            $options[1]['items'][] = array('text' => $tag, 'value' => $tag, 'key' => $tag);
        }

        // 如果没有标签，移除标签选项
        if(empty($tags)) unset($options[1]);

        // 如果没有分支，移除分支选项
        if(empty($branches)) unset($options[0]);

        // 如果都没有，返回空数组
        if(empty($branches) && empty($tags)) return array();

        // 重新索引数组以保证连续的数组索引
        return array_values($options);
    }
}