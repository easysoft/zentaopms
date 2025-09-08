<?php
declare(strict_types=1);
/**
 * The models view file of aiapp module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zemei Wang <wangzemei@easycorp.ltd>
 * @package     aiapp
 * @link        https://www.zentao.net
 */
namespace zin;

div
(
    setClass('models-view'),
    setData(array('modelLang' => $lang->aiapp->model, 'actionLang' => $lang->actions, 'converseLang' => $lang->aiapp->converse, 'pageSummary' => $lang->aiapp->pageSummary)),
    on::init()->call('initModelList'),
    dtable
    (
        setID('modelsList'),
        set::cols(array()),
        set::data(array()),
        set::footer(jsRaw('function(){return window.setModelsStatistics.call(this);}')),
        set::emptyTip($lang->aiapp->tips->noData)
    )
);
