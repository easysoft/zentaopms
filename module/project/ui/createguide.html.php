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

jsVar('isInModal', isInModal());
jsVar('appTab', $config->vision == 'or' ? 'charter' : 'project');
div
(
    setClass('modal-header justify-center font-bold p-0 pb-2'),
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
        setClass('model-block p-2 ' . $model),
        div
        (
            setClass('model-item col items-center cursor-pointer'),
            set('data-url', sprintf($createLink, $model)),
            set('data-model', $model),
            img
            (
                setClass('border w-52'),
                set::src("theme/default/images/main/{$model}.png")
            ),
            h4
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
        setClass('model-block more-model p-2'),
        $config->edition == 'ipd' ? setClass('hidden') : null,
        div
        (
            setClass('border text-gray text-center'),
            $lang->project->moreModelTitle
        )
    )
);

render();
