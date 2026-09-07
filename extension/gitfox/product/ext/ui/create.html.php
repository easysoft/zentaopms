<?php
declare(strict_types=1);
/**
 * GitFox 的创建应用页。
 * Create product module for the GitFox vision.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @package     product
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
    set::title($lang->product->create),
    set::fields($fields),
    set::modeSwitcher(false)
);
