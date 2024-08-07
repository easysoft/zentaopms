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
            'task'           => 'execution-task,my-work,my-contribute,execution-tree,execution-grouptask,project-execution,product-track,repo-view,story-change,execution-kanban,execution-taskkanban,my-index,feedback-adminview,projectstory-track,execution-calendar,my-effort',
            'story'          => 'product-browse,projectstory-story,execution-story,my-work,my-contribute,productplan-view,build-view,projectbuild-view,product-track,repo-view,testcase-zerocase,my-index,feedback-adminview,projectstory-track,task-view,my-effort,demandpool-track',
            'bug'            => 'bug-browse,project-bug,my-work,my-contribute,execution-bug,bug-view,qa-index,execution-task,product-track,execution-task,task-view,repo-view,story-change,repo-review,feedback-adminview,my-index,projectstory-track,my-effort',
            'testcase'       => 'testcase-browse,project-testcase,my-work,my-contribute,execution-testcase,testtask-cases,testsuite-view,product-browse,testcase-view,qa-index,caselib-browse,product-track,story-change,my-index,projectstory-track,my-effort',
            'testsuite'      => 'testsuite-browse,testsuite-view,testtask-cases,my-index',
            'testtask'       => 'testtask-browse,testtask-cases,qa-index,testcase-browse,execution-build,my-index,my-effort',
            'testreport'     => 'testreport-browse,project-testreport,execution-testreport,execution-testtask',
            'tree'           => 'product-browse,project-browse,execution-task,bug-browse,projectstory-story,host-browse,execution-story,feedback-admin,ticket-browse,testcase-browse,caselib-browse',
            'doc'            => 'doc-mySpace,doc-productSpace,doc-projectSpace,doc-teamSpace,doc-view,execution-doc',
            'design'         => 'design-browse,projectstory-track,my-effort',
            'release'        => 'release-browse,release-view,product-roadmap,kanban-view,projectrelease-browse,story-view,my-index,my-effort',
            'productplan'    => 'productplan-browse,kanban-view,projectplan-browse,my-index,my-effort',
            'programplan'    => 'project-execution',
            'projectrelease' => 'projectrelease-browse',
            'projectstory'   => 'projectstory-story',
            'build'          => 'execution-build,build-view,project-index,kanban-view,testtask-browse,projectbuild-browse,my-effort',
            'projectbuild'   => 'projectbuild-browse,projectbuild-view,project-index',
            'mr'             => 'mr-browse',
            'repo'           => 'repo-maintain,repo-log,repo-browse,repo-view,repo-review,mr-view',
            'compile'        => 'compile-browse',
            'store'          => 'store-browse',
            'space'          => 'space-browse',
            'serverroom'     => 'serverroom-browse',
            'project'        => 'program-browse,program-project,project-browse,project-view,project-team',
            'product'        => 'product-all,program-productview,program-product,product-view',
            'gitlab'         => 'space-browse,gitlab-browseproject',
            'gitfox'         => 'space-browse',
            'zanode'         => 'zanode-browse,zanode-view',
            'zahost'         => 'zahost-browse,zahost-view',
            'stakeholder'    => 'stakeholder-browse',
            'execution'      => 'execution-story,execution-team,execution-index,execution->view,project-execution,execution-all,project-index,user-execution,execution-relation',
            'ai'             => 'ai-models',
            'api'            => 'api-index',
            'demand'         => 'demand-browse,demand-view,my-work,feedback-adminview,feedback-admin,my-index,demandpool-track',
            'issue'          => 'issue-browse,issue-view',
            'risk'           => 'risk-browse,risk-view',
            'todo'           => 'my-todo,my-effort',
            'ticket'         => 'my-effort',
            'designguide'    => 'designguide-browse,designguide-view,execution-task,task-view', // For designguide plugin.
            'deploy'         => 'deploy-browse,deploy-steps,deploy-scope,deploy-view,deploy-cases',
            'service'        => 'service-browse,service-manage,service-view',
            'domain'         => 'domain-browse,domain-view',
            'researchtask'   => 'marketresearch-task,my-index',
            'review'         => 'review-browse,my-index',
            'charter'        => 'charter-browse'
        );

        $props  = parent::getProps();
        $back   = $this->prop('back');
        $module = $app->getModuleName();
        $method = $app->getMethodName();
        if($back != 'APP')
        {
            $props['data-back'] = $back;
        }
        elseif(isset($backs[$module]))
        {
            $backList = explode(',', $backs[$module]);
            $backList = array_diff($backList, array("$module-$method"));

            $props['data-back'] = implode(',', $backList);
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
