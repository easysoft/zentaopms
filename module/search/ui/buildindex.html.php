<?php
declare(strict_types=1);
/**
 * The build index view file of search module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     search
 * @link        https://www.zentao.net
 */
namespace zin;

panel
(
    set::title($lang->search->index),
    set::titleIcon('refresh'),
    div
    (
        setID('buildResult'),
        button
        (
            on::click('buildIndex'),
            setClass('btn primary'),
            $lang->search->buildIndex
        )
    )
);

render();
