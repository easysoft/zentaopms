<?php
declare(strict_types=1);
/**
 * The required view file of custom module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     custom
 * @link        https://www.zentao.net
 */
namespace zin;

$requiredRows = null;
$i            = 0;
foreach($requiredFields as $method => $requiredField)
{
    $fields = $this->custom->getFormFields($module, $method);
    if(empty($fields)) continue;
    if($module == 'caselib' and $method == 'createcase') continue;

    $actionKey   = $method . 'Action';
    $actionTitle = isset($lang->$module->$actionKey) ? $lang->$module->$actionKey : $lang->$module->$method;

    $requiredRows[] = formGroup
        (
            set::width('1/2'),
            set::label($actionTitle . $lang->custom->page),
            set::labelClass('font-bold'),
            picker
            (
                set::name("requiredFields[{$method}][]"),
                set::items($fields),
                set::value($requiredField),
                set::multiple(true),
                set::required(true)
            )
        );
}

$formActions = array('submit');
if(common::hasPriv('custom', 'resetRequired'))
{
    $formActions[] = array(
        'url'          => inlink('resetRequired', "module=$module"),
        'text'         => $lang->custom->restore,
        'class'        => 'btn-wide ajax-submit',
        'data-confirm' => $lang->custom->confirmRestore
    );
}

if(!in_array($module, array('productplan', 'release', 'testsuite', 'testreport', 'caselib', 'doc')) && $config->vision == 'rnd') include 'sidebar.html.php';
div
(
    setID('mainContent'),
    setClass('row has-sidebar-left'),
    isset($sidebarMenu) ? $sidebarMenu : null,
    formPanel
    (
        setID('requiredForm'),
        setClass('flex-auto'),
        setClass(isset($sidebarMenu) ? 'ml-0.5' : null),
        set::actionsClass('w-1/2'),
        set::actions($formActions),
        span
        (
            setClass('text-md font-bold'),
            $lang->custom->required
        ),
        $requiredRows
    )
);

/* ====== Render page ====== */
render();
