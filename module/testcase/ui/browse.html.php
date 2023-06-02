<?php
declare(strict_types=1);
/**
 * The browse view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;

$this->testcase->buildOperateMenu(null, 'browse');

foreach($cases as $case)
{
    $actions = array();
    foreach($this->config->testcase->dtable->fieldList['actions']['actionsMap'] as $actionCode => $actionMap)
    {
        $isClickable = $this->testcase->isClickable($case, $actionCode);

        $actions[] = $isClickable ? $actionCode : array('name' => $actionCode, 'disabled' => true);
    }
    $case->actions = $actions;
}

$cols = array_values($config->testcase->dtable->fieldList);
$data = array_values($cases);

featureBar();
toolbar
(
    btngroup
    (
        btn
        (
            setClass('btn primary'),
            set::icon('plus'),
            set::url(helper::createLink('testcase', 'create', "productID=$productID&branch=$branch&moduleID=$initModule")),
            $lang->testcase->create
        ),
        dropdown
        (
            btn(setClass('btn primary dropdown-toggle'), setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
            set::items
            (
                array
                (
                    array('text' => $lang->testcase->create,      'url' => helper::createLink('testcase', 'create', "productID=$productID&branch=$branch&moduleID=$initModule")),
                    array('text' => $lang->testcase->batchCreate, 'url' => helper::createLink('testcase', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$initModule")),
                    array('text' => $lang->testcase->newScene,    'url' => helper::createLink('testcase', 'createScene', "productID=$productID&branch=$branch&moduleID=$initModule"))
                )
            ),
            set::placement('bottom-end'),
        )
    )
);

dtable
(
    set::cols($cols),
    set::data($data),
);

render();
