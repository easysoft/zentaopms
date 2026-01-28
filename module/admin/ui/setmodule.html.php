<?php
declare(strict_types=1);
/**
 * The set module view file of admin module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     admin
 * @link        https://www.zentao.net
 */
namespace zin;

global $app;
$app->loadLang('project');
jsVar('confirmDisableStoryType', $lang->admin->notice->confirmDisableStoryType);
jsVar('edition', $config->edition);
jsVar('enableER', $config->enableER);
jsVar('URAndSR', $config->URAndSR);
jsVar('ERCommon', $lang->ERCommon);
jsVar('URCommon', $lang->URCommon);
jsVar('changeLang', $lang->admin->setModule->change);
jsVar('deliverableLang', $lang->admin->setModule->deliverable);
jsVar('cmLang', $lang->admin->setModule->cm);
jsVar('openDependFeature', $lang->admin->notice->openDependFeature);
jsVar('closeDependFeature', $lang->admin->notice->closeDependFeature);

if(strpos(",$disabledFeatures,", ",productUR,") !== false) $disabledFeatures .= ',productER';

$rows = array();
foreach($config->featureGroup as $group => $features)
{
    if(strpos(",$disabledFeatures,", ",$group,") !== false) continue;

    $hasData = false;
    foreach($features as $feature)
    {
        $code = $group . ucfirst($feature);
        if(strpos(",$disabledFeatures,", ",$code,") !== false) continue;
        $hasData = true;
    }

    if($hasData)
    {
        $items = array();
        foreach($features as $feature)
        {
            $code = $group. ucfirst($feature);
            if(strpos(",$disabledFeatures,", ",$code,") !== false) continue;

            $value = strpos(",$closedFeatures,", ",$code,") === false ? '1' : '0';
            if($code == 'myScore') $value = $useScore ? 1 : 0;

            $items[] = checkbox
            (
                setID("module{$code}"),
                set::rootClass('w-40'),
                set::name("module[{$code}]"),
                set::value(1),
                set::checked($value == 1),
                on::change('checkModule'),
                $lang->admin->setModule->{$feature}
            );

            $items[] = input
            (
                setID("module{$code}"),
                set::type('hidden'),
                set::name("module[{$code}]"),
                set::value($value)
            );
        }
        $rows[] = h::tr
        (
            setClass('border-t'),
            h::td
            (
                setClass('p-2.5'),
                checkbox
                (
                    setID("allChecker{$group}"),
                    set::name("allChecker[$group]"),
                    on::change('checkGroup'),
                    $lang->admin->setModule->{$group}
                )
            ),
            h::td
            (
                setClass('flex flex-wrap p-2.5 border-l'),
                $items
            )
        );
    }
}

formPanel
(
    set::id('setModuleForm'),
    set::title($lang->admin->setModuleIndex),
    set::actions(false),
    set::ajax(array('beforeSubmit' => jsRaw("submitForm"))),
    h::table
    (
        setClass('border w-full'),
        h::thead
        (
            h::tr
            (
                h::th
                (
                    setClass('text-md p-2.5'),
                    setStyle(array('width' => '100px')),
                    $lang->admin->setModule->module
                ),
                h::th
                (
                    setClass('text-md p-2.5 border-l'),
                    $lang->admin->setModule->optional
                )
            )
        ),
        h::tbody
        (
            $rows,
            h::tr
            (
                setClass('border-t'),
                h::td
                (
                    setClass('p-2.5'),
                    checkbox
                    (
                        setID('allCheckeer'),
                        on::change('checkAll'),
                        $lang->selectAll
                    )
                ),
                h::td
                (
                    setClass('form-actions inline-flex gap-4 p-2.5 border-l'),
                    button
                    (
                        setClass('btn primary'),
                        set::type('submit'),
                        $lang->save
                    ),
                    button
                    (
                        setClass('btn open-url'),
                        setData(array('back' => 'APP')),
                        $lang->goback
                    )
                )
            )
        )
    )
);

render();
