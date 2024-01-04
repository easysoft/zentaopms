<?php
declare(strict_types=1);
/**
 * The activate view file of gogs module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     gogs
 * @link        https://www.zentao.net
 */
namespace zin;
global $lang;

detailHeader
(
    isAjaxRequest('modal') ? to::prefix() : '',
    to::title(
        entityLabel(
            set(array('entityID' => $job->id, 'level' => 1, 'text' => $job->name))
        ),
        $job->deleted ? span(setClass('label danger'), $lang->product->deleted) : null
    )
);

$hasResult = ($compile && !empty($compile->testtask));
$hasLog    = ($compile && !empty($compile->logs));
$repo      = $repo ? $repo : new stdclass();

if(strtolower($job->engine) == 'gitlab') $job->pipeline = $this->loadModel('gitlab')->getProjectName($job->server, $job->pipeline);
if(!$job->pipeline) $job->pipeline = '';

if($compile and $compile->status)
{
    $status = zget($lang->compile->statusList, $compile->status);
    $time   = zget($lang->compile->statusList, $compile->updateDate);
}
elseif($job->lastStatus)
{
    $status = zget($lang->compile->statusList, $job->lastStatus);
    $time   = zget($lang->compile->statusList, $job->lastExec);
}

$customParam = '';
if($job->customParam)
{
    foreach(json_decode($job->customParam) as $paramName => $paramValue)
    {
        $paramValue = str_replace('$zentao_version', zget($lang->job->paramValueList, $paramValue). '(' . $this->config->version . ')', $paramValue);
        $paramValue = str_replace('$zentao_account', zget($lang->job->paramValueList, $paramValue). '(' . $this->app->user->account . ')', $paramValue);
        $paramValue = str_replace('$zentao_product', zget($lang->job->paramValueList, $paramValue). '(' . $job->product . ')', $paramValue);
        $paramValue = str_replace('$zentao_repopath', zget($lang->job->paramValueList, $paramValue). '(' . zget($repo, 'path', '') . ')', $paramValue);

        $customParam .= "<p>$paramName : $paramValue</p>";
    }
}

detailBody
(
    sectionList
    (
        tabs
        (
            tabPane
            (
                set::key('job-basic'),
                set::title($lang->job->lblBasic),
                set::active(!$hasResult && !$hasLog),
                tableData
                (
                    item
                    (
                        set::name($lang->job->engine),
                        zget($lang->job->engineList, $job->engine)
                    ),
                    item
                    (
                        set::name($lang->job->repo),
                        zget($repo, 'name', '')
                    ),
                    item
                    (
                        set::name($lang->job->product),
                        $product->name
                    ),
                    item
                    (
                        set::name($lang->job->frame),
                        zget($lang->job->frameList, $job->frame)
                    ),
                    item
                    (
                        set::name($lang->job->server),
                        urldecode($job->pipeline) . '@' . $jenkins->name
                    ),
                    item
                    (
                        set::name($lang->job->triggerType),
                        $this->job->getTriggerConfig($job)
                    ),
                    item
                    (
                        set::name($lang->compile->status),
                        !empty($status) ? $status : ''
                    ),
                    item
                    (
                        set::name($lang->compile->time),
                        !empty($time) ? $time : ''
                    ),
                    item
                    (
                        set::name($lang->job->customParam),
                        html($customParam)
                    )
                )
            ),
            $hasResult ? tabPane
            (
                set::key('job-result'),
                set::title($lang->compile->result),
                set::active(true),
                div(setID('jobCases'), setData('task', $compile->testtask))
            ) : '',
            $hasLog ? tabPane
            (
                set::key('job-log'),
                set::title($lang->compile->logs),
                set::active(!$hasResult),
                tableData
                (
                    div
                    (
                        set::className('mt-4'),
                        html(nl2br($compile->logs))
                    )
                )
            ) : ''
        )
    )
);
