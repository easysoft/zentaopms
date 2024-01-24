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
$fields->orders('name,code', 'type,status', 'reviewer,QD,RD');
$fields->fullModeOrders('name,code', 'type,status', 'reviewer,QD,RD');

formGridPanel
(
    set::title($lang->product->edit),
    set::defaultMode('full'),
    set::modeSwitcher(false),
    set::fields($fields),
    on::change('[name=program]', 'toggleLineByProgram')
);
