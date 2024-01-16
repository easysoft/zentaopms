<?php
declare(strict_types=1);
/**
 * The edit view file of product module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     product
 * @link        https://www.zentao.net
 */
namespace zin;

$fields = useFields('product.edit');

formGridPanel
(
    set::title($lang->product->edit),
    set::fields($fields),
    set::defaultMode('full'),
    set::modeSwitcher(false),
    on::change('[name=program]', 'toggleLineByProgram')
);
