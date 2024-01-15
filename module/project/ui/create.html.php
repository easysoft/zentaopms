<?php
declare(strict_types=1);
/**
 * The create view file of project module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     project
 * @link        https://www.zentao.net
 */
namespace zin;

$fields = useFields('project.create');

$autoLoad = array();

jsVar('model', $model);

formGridPanel
(
    to::titleSuffix
    (
        picker
        (
            set::onChange(jsRaw("(value) => loadPage($.createLink('project', 'create', 'model=' + value))")),
            set::className('text-base text-light w-24'),
            set::required(true),
            set::items($lang->project->modelList),
            set::value($model)
        )
    ),
    to::headingActions
    (
        a
        (
            icon('copy', setClass('mr-1')),
            setClass('primary-ghost'),
            setData(array('destoryOnHide' => true, 'toggle' => 'modal', 'target' => '#copyProjectModal')),
            $lang->project->copy
        ),
        divider(setClass('py-2 my-0.5 mx-4 self-center'))
    ),
    on::click('[name=name], [name=code], [name=end], [name=days], [data-name="parent"] .pick *', 'removeTips'),
    on::click('[type=submit]', 'removeAllTips'),
    on::change('[name=hasProduct]', 'changeType'),
    on::change('[name=future]', 'toggleBudget'),
    on::change('[name=begin], [name=end]', 'computeWorkDays'),
    set::title($lang->project->create),
    set::fields($fields),
);

$copyProjectsBox = array();
if(!empty($copyProjects))
{
    foreach($copyProjects as $id => $name)
    {
        $copyProjectsBox[] = btn
        (
            setClass('project-block justify-start'),
            setClass($copyProjectID == $id ? 'primary-outline' : ''),
            set('data-id', $id),
            set('data-pinyin', zget($copyPinyinList, $name, '')),
            icon(setClass('text-gray'), $lang->icons['project']),
            span($name, set::title($name))
        );
    }
}
else
{
    $copyProjectsBox[] = div
    (
        setClass('inline-flex items-center w-full bg-lighter h-12 mt-2 mb-8'),
        icon('exclamation-sign icon-2x pl-2 text-warning'),
        span
        (
            set::className('font-bold ml-2'),
            $lang->project->copyNoProject
        )
    );
}

modalTrigger
(
    modal
    (
        set::id('copyProjectModal'),
        to::header
        (
            span
            (
                h4
                (
                    set::className('copy-title'),
                    $lang->project->copyTitle
                )
            ),
            input
            (
                set::name('projectName'),
                set::placeholder($lang->project->searchByName)
            )
        ),
        div
        (
            set::id('copyProjects'),
            setClass('flex items-center flex-wrap'),
            $copyProjectsBox
        )
    )
);

render();
