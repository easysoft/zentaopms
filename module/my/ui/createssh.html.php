<?php
declare(strict_types=1);
/**
 * The create ssh view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2026 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;
$width = common::checkNotCN() ? 'full' : '1/2';

$fields = defineFieldList('my');
$fields->field('name')->required(true)->placeholder($lang->my->nameFormat)->width($width);
$fields->field('publicKey')->labelHintIcon('help')->labelHint($lang->my->sshKeyTip)->required(true)->control(array('control' => 'textarea', 'rows' => 5))->width('full');

formGridPanel
(
    setID('createSSHForm'),
    set::size('md'),
    set::modeSwitcher(false),
    set::title($title),
    set::fields($fields)
);
