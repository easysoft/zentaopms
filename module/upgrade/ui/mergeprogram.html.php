<?php
declare(strict_types=1);
/**
 * The mergeprogram view file of upgrade module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     upgrade
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);
if($type == 'productline')                   include_once('mergebyline.html.php');
if($type == 'product')                       include_once('mergebyproduct.html.php');
if($type == 'sprint' || $type == 'moreLink') include_once('mergebysprint.html.php');

div
(
    set::id('main'),
    div
    (
        set::id('mainContent'),
        set::class('bg-white p-4'),
        set::style(array('margin' => '50px auto 0', 'width' => '1200px')),
        div
        (
            set::class('article-h1 mb-4'),
            $lang->upgrade->mergeModes['manually']
        ),
        form
        (
            set::actions(''),
            $getMergeData($this->view)
        )
    )
);

render('pagebase');

