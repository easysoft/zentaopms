<?php
declare(strict_types=1);
/**
 * The add reviewers view file of mr module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     mr
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    setID('reviewersForm'),
    set::title($lang->mr->addReviewer),
    formGroup
    (
        set::width('1/2'),
        set::required(true),
        set::name('reviewer'),
        set::label($lang->mr->reviewer),
        set::items($users),
        set::multiple(true)
    )
);
