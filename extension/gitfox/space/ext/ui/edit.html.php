<?php
declare(strict_types=1);
/**
 * GitFox 空间下编辑应用的视图：字段与应用模块一致，显式传入应用数据以带出原值。
 * The edit view of an application of a space in the GitFox vision.
 *
 * @copyright   Copyright 2009-2026 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @package     space
 * @link        https://www.zentao.net
 */
namespace zin;

global $lang;

$fields = new fieldList();
$fields->field('name')
    ->label($lang->product->name)
    ->required(true)
    ->control('text')
    ->width('1/2');
$fields->field('desc')
    ->label($lang->product->desc)
    ->width('full')
    ->control(array('control' => 'editor'));

formGridPanel
(
    set::title($lang->product->edit),
    set::fields($fields),
    set::data($product),
    set::modeSwitcher(false)
);
