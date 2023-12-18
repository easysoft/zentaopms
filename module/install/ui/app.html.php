<?php
declare(strict_types=1);
/**
 * The app view file of install module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     install
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);

h::importCss($app->getWebRoot() . 'js/xterm/xterm.css');
h::importJs($app->getWebRoot() . 'js/xterm/xterm.js');

$formRows = array();
foreach($components->category as $item)
{
    if(in_array($item->name, array('analysis', 'artifact'))) array_unshift($item->choices, (object)array('name' => $lang->install->solution->skipInstall, 'version' => ''));
    if($item->name === 'pms') continue;

    $formRows[] = formGroup
    (
        set::label($item->alias),
        set::name($item->name),
        set::required(true),
        set::items($this->solution->createSelectOptions($item->choices, $cloudSolution)),
        on::change('checkMemory(this)')
    );
}

jsVar('category', $category);
div
(
    set::id('main'),
    div
    (
        set::id('mainContent'),
        formPanel
        (
            setID('solutionForm'),
            setClass('bg-canvas m-auto mw-auto'),
            set::title($lang->install->solution->title),
            set::submitBtnText($lang->solution->install),
            set::formClass('w-1/2 m-auto'),
            set('data-loading', $lang->solution->notices->creatingSolution),
            div
            (
                setClass('text-center'),
                $lang->install->solution->desc
            ),
            $formRows,
            div
            (
                setClass('hidden text-center text-warning'),
                setID('overMemoryNotice'),
                $lang->install->solution->overMemory
            ),
            set::actions(array(
                array(
                    'text'  => $lang->install->solution->skip,
                    'href'  => createLink('install', 'step6'),
                    'id'    => 'skipBtn',
                    'class' => 'btn hidden'
                ),
                array(
                    'text'    => $lang->solution->install,
                    'id'      => 'submitBtn',
                    'class'   => 'btn primary',
                    'onclick' => 'checkSolution(this)'
                )
            ))
        )
    )
);

render('pagebase');
