<?php
declare(strict_types=1);
/**
 * The index view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      dingguodong <dingguodong@easycorp.ltd>
 * @package     product
 * @link        https://www.zentao.net
 */

namespace zin;

if(empty($products))
{
    div(
        setClass('table-empty-tip bg-canvas h-40 flex items-center justify-center'),
        span(
            setClass('text-gray'),
            $lang->product->noProduct
        ),
        a(
            setClass('btn primary-pale border-primary'),
            icon('plus'),
            set::href(createLink('product', 'create')),
            $lang->product->create
        )
    );
}
else
{
    echo $this->fetch('block', 'dashboard', 'dashboard=product');
    helper::end();
}

render();
