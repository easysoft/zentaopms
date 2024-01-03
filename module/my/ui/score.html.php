<?php
declare(strict_types=1);
/**
 * The score view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('methods', $lang->score->methods);

featureBar
(
    set::current('all'),
);

$cols = array_values($config->my->score->dtable->fieldList);
$data = array_values($scores);
toolbar
(
    span
    (
        setClass('btn text'),
        h::strong
        (
            $lang->score->current,
            ':'
        ),
        $user->score
    ),
    hasPriv('score', 'rule') ? btn
    (
        setClass('btn primary'),
        set::url(helper::createLink('score', 'rule')),
        $lang->my->scoreRule
    ) : null
);

dtable
(
    set::cols($cols),
    set::data($data),
    set::fixedLeftWidth('0.2'),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::footPager(usePager())
);

render();
