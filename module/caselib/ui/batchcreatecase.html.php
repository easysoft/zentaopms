<?php
declare(strict_types=1);
/**
 * The batchcreatecase view file of caselib module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     caselib
 * @link        https://www.zentao.net
 */
namespace zin;

/* Field of id. */
$items[] = array
(
    'name'    => 'id',
    'label'   => $lang->idAB,
    'control' => 'index',
    'width'   => '32px'
);

/* Field of module. */
$items[] = array
(
    'name'     => 'module',
    'label'    => $lang->testcase->module,
    'control'  => 'picker',
    'items'    => $moduleOptionMenu,
    'value'    => $currentModuleID,
    'width'    => '200px',
    'required' => strpos($config->testcase->create->requiredFields, 'module') !== false,
    'ditto'    => true
);

/* Field of type. */
$items[] = array
(
    'name'     => 'type',
    'label'    => $lang->testcase->type,
    'control'  => 'picker',
    'items'    => $lang->testcase->typeList,
    'value'    => 'feature',
    'width'    => '160px',
    'required' => true,
    'ditto'    => true
);

/* Field of stage. */
$items[] = array
(
    'name'     => 'stage',
    'label'    => $lang->testcase->stage,
    'control'  => array(
        'type'     => 'picker',
        'items'    => $lang->testcase->stageList,
        'value'    => '',
        'multiple' => true,
        'required' => strpos($config->testcase->create->requiredFields, 'stage') !== false
    ),
    'required' => strpos($config->testcase->create->requiredFields, 'stage') !== false,
    'width'    => '160px'
);

/* Field of title. */
$items[] = array
(
    'name'     => 'title',
    'label'    => $lang->testcase->title,
    'width'    => '240px',
    'required' => true
);

/* Field of pri. */
$priList = array_filter($lang->testcase->priList);
$items[] = array
(
    'name'     => 'pri',
    'label'    => $lang->testcase->pri,
    'control'  => 'priPicker',
    'items'    => $priList,
    'value'    => 3,
    'width'    => '100px',
    'required' => strpos($config->testcase->create->requiredFields, 'pri') !== false,
    'ditto'    => true
);

/* Field of precondition. */
$items[] = array
(
    'name'     => 'precondition',
    'label'    => $lang->testcase->precondition,
    'width'    => '200px',
    'required' => strpos($config->testcase->create->requiredFields, 'precondition') !== false
);

/* Field of keywords. */
$items[] = array
(
    'name'     => 'keywords',
    'label'    => $lang->testcase->keywords,
    'width'    => '200px',
    'required' => strpos($config->testcase->create->requiredFields, 'keywords') !== false
);

formBatchPanel
(
    set::title($lang->testcase->batchCreate),
    set::pasteField('title'),
    set::items($items)
);

render();

