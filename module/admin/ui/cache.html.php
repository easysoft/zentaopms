<?php
declare(strict_types=1);
/**
 * The cache view file of admin module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     admin
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    formGroup
    (
        set::label($lang->admin->daoCache),
        radioList
        (
            set::name('enable'),
            set::items($lang->admin->cacheStatusList),
            set::value($config->cache->enableDAO),
            set::inline(true)
        )
    )
);

render();
