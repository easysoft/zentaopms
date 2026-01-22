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
            'task'           => 'execution-task,my-work,my-contribute,execution-tree,execution-grouptask,project-execution,product-track,repo-view,story-change,execution-kanban,execution-taskkanban,my-index,feedback-adminview,projectstory-track,execution-calendar,my-effort,company-effort,company-calendar,product-dynamic,project-dynamic,execution-dynamic,project-view,execution-view,task-view',
            'story'          => 'product-browse,projectstory-story,execution-story,my-work,my-contribute,productplan-view,build-view,projectbuild-view,product-track,repo-view,testcase-zerocase,my-index,feedback-adminview,projectstory-track,task-view,my-effort,company-effort,company-calendar,demandpool-track,product-dynamic,project-dynamic,execution-dynamic,project-view,execution-view,ticket-browse,ticket-view,roadmap-view,charter-view,demand-view,review-assess,review-audit,action-trash,execution-task,testcase-browse,story-view',
            'bug'            => 'bug-browse,project-bug,my-work,my-contribute,execution-bug,bug-view,qa-index,execution-task,product-track,execution-task,task-view,repo-view,story-change,repo-review,feedback-adminview,my-index,projectstory-track,my-effort,company-effort,company-calendar,product-dynamic,project-dynamic,execution-dynamic,project-view,execution-view,ticket-browse,ticket-view,productplan-view',
            'testcase'       => 'testcase-browse,testcase-browseScene,project-testcase,my-work,my-contribute,execution-testcase,testtask-cases,testsuite-view,product-browse,testcase-view,qa-index,caselib-browse,product-track,story-change,my-index,projectstory-track,my-effort,company-effort,company-calendar,product-dynamic,project-dynamic,execution-dynamic,project-view,execution-view,testreport-view,bug-browse,project-bug,bug-view',
            'testsuite'      => 'testsuite-browse,testsuite-view,testtask-cases,my-index,product-dynamic,project-dynamic,execution-dynamic,project-view,execution-view',
            'testtask'       => 'testtask-browse,testtask-cases,qa-index,testcase-browse,execution-build,my-index,my-effort,company-effort,company-calendar,product-dynamic,project-dynamic,execution-dynamic,project-view,execution-view,project-testcase,project-testtask,execution-testtask',
            'testreport'     => 'testreport-browse,project-testreport,execution-testreport,execution-testtask',
            'tree'           => 'product-browse,project-browse,execution-task,bug-browse,projectstory-story,host-browse,execution-story,feedback-admin,ticket-browse,testcase-browse,caselib-browse,dataview-browse,deliverable-browse,workflowdatasource-browse',
            'doc'            => 'doc-mySpace,doc-productSpace,doc-projectSpace,doc-teamSpace,doc-view,execution-doc',
            'design'         => 'design-browse,projectstory-track,my-effort,company-effort,company-calendar,my-index',
            'release'        => 'release-browse,release-view,product-roadmap,kanban-view,projectrelease-browse,story-view,my-index,my-effort,company-effort,company-calendar,product-dynamic,project-dynamic,project-view',
            'productplan'    => 'productplan-browse,kanban-view,projectplan-browse,my-index,my-effort,company-effort,company-calendar,product-dynamic,project-dynamic,project-view,charter-view',
            'programplan'    => 'project-execution',
            'projectrelease' => 'projectrelease-browse',
            'projectstory'   => 'projectstory-story',
            'build'          => 'execution-build,build-view,project-index,kanban-view,testtask-browse,projectbuild-browse,my-effort,company-effort,company-calendar,my-index,project-dynamic,execution-dynamic,project-view,execution-view,testtask-view',
            'projectbuild'   => 'projectbuild-browse,projectbuild-view,project-index',
            'repo'           => 'repo-maintain,repo-log,repo-browse,repo-view,repo-review,mr-view,repo-browsewebhooks,repo-browserule',
            'mr'             => 'pullreq-browse,mr-browse,my-audit',
            'pullreq'        => 'pullreq-browse',
            'compile'        => 'compile-browse',
            'store'          => 'store-browse',
            'space'          => 'space-browse',
            'serverroom'     => 'serverroom-browse',
            'project'        => 'program-browse,program-project,project-browse,project-view,project-team,charter-view,project-template,project-deliverable',
            'product'        => 'product-all,program-productview,program-product,product-view,product-dynamic,my-index,charter-view,testtask-view',
            'gitlab'         => 'space-browse,gitlab-browseproject',
            'gitfox'         => 'space-browse',
            'zanode'         => 'zanode-browse,zanode-view',
            'zahost'         => 'zahost-browse,zahost-view',
            'stakeholder'    => 'stakeholder-browse,program-stakeholder',
            'execution'      => 'execution-story,execution-team,execution-index,execution->view,project-execution,execution-all,project-index,user-execution,execution-relation,programplan-relation',
            'ai'             => 'ai-models',
            'api'            => 'api-index',
            'demand'         => 'demand-browse,demand-view,my-work,my-contribute,feedback-adminview,feedback-admin,my-index,demandpool-track,product-dynamic',
            'issue'          => 'issue-browse,issue-view,my-index,project-index,project-dynamic,execution-dynamic,project-view,execution-view',
            'risk'           => 'risk-browse,risk-view,my-index,project-index,project-dynamic,execution-dynamic,project-view,execution-view',
            'opportunity'    => 'opportunity-browse,opportunity-view,my-index,project-index,project-dynamic,execution-dynamic,project-view,execution-view',
            'meeting'        => 'meeting-browse,meeting-view,my-index,project-dynamic,execution-dynamic,project-view,execution-view,my-meeting',
            'todo'           => 'my-todo,my-effort,company-effort,company-calendar',
            'feedback'       => 'feedback-admin,feedback-browse,my-work,my-contribute,my-effort,company-effort,company-calendar',
            'ticket'         => 'my-effort,company-effort,company-calendar,ticket-browse,my-work,my-contribute',
            'designguide'    => 'designguide-browse,designguide-view,execution-task,task-view', // For designguide plugin.
            'deploy'         => 'deploy-browse,deploy-steps,deploy-scope,deploy-view,deploy-cases',
            'service'        => 'service-browse,service-manage,service-view',
            'domain'         => 'domain-browse,domain-view',
            'researchtask'   => 'marketresearch-task,my-index,product-dynamic,project-dynamic,project-view,my-contribute',
            'review'         => 'review-browse,project-deliverable,my-index,product-dynamic,project-dynamic,project-view,programplan-browse,project-execution,my-contribute,my-audit',
            'charter'        => 'my-index,charter-browse,charter-view,my-audit,program-browse,project-view',
            'roadmap'        => 'charter-view',
            'approvalflow'   => 'approvalflow-browse',
            'host'           => 'host-browse,my-index',
            'deploy'         => 'deploy-browse',
            'program'        => 'program-browse,program-productview',
            'workflowgroup'  => 'workflowgroup-project,workflowgroup-deliverable',
            'cm'             => 'cm-browse,cm-view,my-contribute',
            'deliverable'    => 'deliverable-browse,project-deliverable,action-trash',
            'weekly'         => 'weekly-browse',
            'milestone'      => 'weekly-browse',
            'nc'             => 'nc-browse'
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
