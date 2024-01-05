<?php
declare(strict_types=1);
/**
 * The showxminimport view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('productID', $product->id);
jsVar('branch', $branch);
jsVar('userConfig_module', $settings['module']);
jsVar('userConfig_scene', $settings['scene']);
jsVar('userConfig_case', $settings['case']);
jsVar('userConfig_pri', $settings['pri']);
jsVar('userConfig_group', $settings['group']);
jsVar('jsLng', $this->lang->testcase->jsLng);

$nodeTemplate =
"<div  data-toggle='tooltip' data-placement='bottom' id='node-{id}' class='mindmap-node' data-type='{type}' data-id='{id}' data-parent='{parent}'>" .
"   <div class='scene-indicator' style='display:none;'><i class='icon icon-flag'></i></div>" .
"   <a class='pri-level' style='display:none;'></a>" .
"   <div class='wrapper'>" .
"       <div class='text'>{text}</div>" .
"       <div class='caption'>{caption}</div>" .
"       <div class='btn-toggle'></div>" .
"   </div>" .
"   <div class='suffix'><span>[</span><span class='content'>M:10000</span><span>]</span></div>" .
"</div>";

panel
(
    set::title("{$lang->testcase->xmindImportEdit}({$product->name})"),
    set::headingClass('p-6'),
    set::bodyClass('px-6 py-0'),
    mindmap
    (
        set::data($scenes),
        set::height('600px'),
        set::nodeTeamplate($nodeTemplate),
        set::enableDrag(false),
        set::manual(true)
    ),
    div
    (
        setClass('py-6 text-center'),
        btn(setClass('primary btn-wide'), $lang->save),
        backBtn(setClass('btn-wide ml-4'), $lang->goback)
    )
);
render();
