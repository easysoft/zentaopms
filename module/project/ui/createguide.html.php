<?php
declare(strict_types=1);
/**
 * The create guide view file of project module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao<caoyanyi@easycorp.ltd>
 * @package     project
 * @link        https://www.zentao.net
 */
namespace zin;

div
(
    setClass('modal-header justify-center font-bold'),
    h3($lang->project->chooseProgramType)
);

$hasWaterfall     = helper::hasFeature('waterfall');
$hasWaterfallPlus = helper::hasFeature('waterfallplus');

$createLink = createLink("project", "create", "model=%s&programID=$programID&copyProjectID=0&extra=productID=$productID,branchID=$branchID");
$itemList   = array();
foreach($lang->project->modelList as $model => $modelName)
{
    if(empty($model)) continue;
    if(!$hasWaterfall && $model == 'waterfall') continue;
    if(!$hasWaterfallPlus && $model == 'waterfallplus') continue;

    $titleKey   = "{$model}Title";
    $itemList[] = center
    (
        setClass('model-block'),
        div
        (
            setClass('model-item col items-center cursor-pointer'),
            set('data-url', sprintf($createLink, $model)),
            img
            (
                setClass('border'),
                set::src("theme/default/images/main/{$model}.png")
            ),
            h3
            (
                setClass('mt-2 font-bold'),
                $lang->project->{$model}
            ),
            p($lang->project->$titleKey)
        )
    );
}

div
(
    setID('modelList'),
    setClass('flex items-center flex-wrap'),
    $itemList,
    div
    (
        setClass('model-block more-model'),
        div
        (
            setClass('border text-gray text-center'),
            $lang->project->moreModelTitle
        )
    )
);

render();
