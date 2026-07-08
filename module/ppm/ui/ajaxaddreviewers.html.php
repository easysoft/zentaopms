<?php
declare(strict_types=1);
/**
 * The add reviewers view file of ppm module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     ppm
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    setID('reviewersForm'),
    set::title($lang->ppm->addReviewer),
    formGroup
    (
        set::width('1/2'),
        set::required(true),
        set::name('reviewer'),
        set::label($lang->ppm->reviewer),
        set::items($users),
        set::multiple(true)
    )
);
