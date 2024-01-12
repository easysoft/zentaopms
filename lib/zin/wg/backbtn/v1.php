<?php
declare(strict_types=1);
/**
 * The backBtn widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */

namespace zin;

require_once dirname(__DIR__) . DS . 'btn' . DS . 'v1.php';

/**
 * 后退按钮（backBtn）部件类。
 * The back button widget class.
 *
 * @author Hao Sun
 */
class backBtn extends btn
{
    /**
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defineProps = array(
        'back?: string="APP"'  // 定义返回行为，可以为 `'APP'`（默认值，返回打开当前页面时的上一个历史记录）、 `'GLOBAL'`（返回上一个全局历史记录）、`'moduleName-methodName'`（从历史记录中向后查找符合指定路径的历史记录）。
    );

    /**
     * Override the getProps method.
     *
     * @access protected
     * @return array
     */
    protected function getProps(): array
    {
        global $app;

        $backs = array(
            'task'           => 'execution-task,my-work,my-contribute,execution-tree,execution-grouptask',
            'story'          => 'product-browse,projectstory-story,execution-story,my-work,my-contribute,productplan-view,build-view,projectbuild-view,',
            'bug'            => 'bug-browse,project-bug,my-work,my-contribute,execution-bug,bug-view,qa-index,',
            'testcase'       => 'testcase-browse,project-testcase,my-work,my-contribute,execution-testcase,testtask-cases,testsuite-view,product-browse,testcase-view,qa-index,caselib-browse,',
            'testsuite'      => 'testsuite-browse,testsuite-view,',
            'testtask'       => 'testtask-browse,testtask-cases,qa-index,testcase-browse,',
            'testreport'     => 'testreport-browse,project-testreport,execution-testreport,',
            'tree'           => 'product-browse,project-browse,execution-task,bug-browse,projectstory-story,host-browse',
            'doc'            => 'doc-mySpace,doc-productSpace,doc-projectSpace,doc-teamSpace,doc-view,execution-doc',
            'design'         => 'design-browse',
            'release'        => 'release-browse,release-view,product-roadmap,kanban-view',
            'productplan'    => 'productplan-browse,kanban-view',
            'programplan'    => 'project-execution',
            'projectrelease' => 'projectrelease-browse',
            'build'          => 'execution-build,build-view,project-index,kanban-view',
            'projectbuild'   => 'projectbuild-browse,projectbuild-view,project-index',
            'mr'             => 'mr-browse',
            'repo'           => 'repo-log,repo-browse',
            'compile'        => 'compile-browse',
            'store'          => 'store-browse',
            'space'          => 'space-browse',
            'serverroom'     => 'serverroom-browse',
            'project'        => 'program-browse,program-project,project-browse,project-view,project-team',
            'product'        => 'product-all,program-productview,program-product',
            'gitlab'         => 'space-browse,gitlab-browseproject',
            'zanode'         => 'zanode-browse,zanode-view',
            'zahost'         => 'zahost-browse,zahost-view',
        );

        $props = parent::getProps();
        $back = $this->prop('back');
        if($back != 'APP')
        {
            $props['data-back'] = $back;
        }
        elseif(isset($backs[$app->rawModule]))
        {
            $props['data-back'] = $backs[$app->rawModule];
        }
        else
        {
            $props['data-back'] = empty($back) ? 'APP' : $back;
        }

        return $props;
    }

    /**
     * Override the getClassList method.
     *
     * @access protected
     * @return array
     */
    protected function getClassList(): array
    {
        $classList = parent::getClassList();
        $classList['open-url'] = true;
        return $classList;
    }
}
