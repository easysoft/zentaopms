<?php
declare(strict_types=1);
/**
 * The browse view file of testtask module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     testtask
 * @link        https://www.zentao.net
 */
namespace zin;

//$this->testcase->buildOperateMenu(null, 'browse');

$cols = array_values($config->testtask->dtable->fieldList);
$data = array_values($tasks);

featureBar();
toolbar
(
    btngroup
    (
        btn
        (
            setClass('btn primary'),
            set::icon('plus'),
            set::url(helper::createLink('testtask', 'create', "product=$productID")),
            $lang->testtask->create
        )
    )
);

dtable
(
    set::cols($cols),
    set::data($data),
    set::footPager(usePager()),
);

render();
