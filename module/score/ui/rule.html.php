<?php
declare(strict_types=1);
/**
 * The score view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming <sunguangming@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

featureBar
(
    set::current('all')
);

toolbar
(
    btn
    (
        setClass('btn primary'),
        set::url(helper::createLink('my', 'score')),
        $lang->score->common
    )
);

dtable
(
    set::cols(array_values($config->score->dtable->fieldList)),
    set::data($rules),
    set::fixedLeftWidth('0.2')
);

render();
