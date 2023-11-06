<?php
/**
 * The importbuild view file of pi module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     pi
 * @link        https://www.zentao.net
 */
namespace zin;

formBase
(
    set::actions(''),
    dtable
    (
        set::id('linkStoryList'),
        set::userMap($users),
        set::cols($cols),
        set::data(array_values($builds2Imported)),
        set::onRenderCell(jsRaw('window.renderStoryCell')),
        set::footToolbar(array('items' => array(array('text' => $lang->pi->linkStory, 'btnType' => 'primary', 'className' => 'size-sm linkObjectBtn batch-btn', 'data-type' => 'story', 'data-url'  => inlink('linkStory', "piID={$PI->id}&columnID={$columnID}&from=$from&fromLane=$fromLane&toLane=$toLane"))))),
        set::footPager(usePager())
    )
);
