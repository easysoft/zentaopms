<?php
declare(strict_types=1);
/**
 * The api view file of dev module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong<yidong@easycorp.ltd>
 * @package     dev
 * @link        https://www.zentao.net
 */
namespace zin;

$featureBarItems = array();
foreach($lang->dev->featureBar['api'] as $key => $label)
{
    $featureBarItems[] = array
    (
        'text'   => $label,
        'active' => ($selectedModule == $key || ($key == 'index' and $selectedModule != 'restapi')),
        'url'    => inlink('api', "module=$key")
    );
}

$fnBuildAPIContent = function() use($selectedModule, $apis)
{
    global $lang, $config, $app;
    $app->loadLang($selectedModule);
    $app->loadConfig($selectedModule);

    $contents = array();
    foreach($apis as $api)
    {
        $methodName = zget($api, 'name', '');

        $params  = array();
        $paramTR = array();
        if(isset($api['param']))
        {
            foreach($api['param'] as $param)
            {
                $params[]  = "{$param['var']}=[{$param['var']}]";
                $paramTR[] = h::tr
                (
                    h::td($param['var']),
                    h::td($param['type']),
                    h::td($param['desc'])
                );
            }
        }
        $params = implode('&', $params);

        $postTR = array();
        if(isset($config->dev->postParams[$selectedModule][$methodName]))
        {
            foreach($config->dev->postParams[$selectedModule][$methodName] as $paramName => $paramType)
            {
                $paramDesc = '';
                $listKey   = $paramName . 'List';
                if(isset($lang->$selectedModule->$paramName)) $paramDesc .= $lang->$selectedModule->$paramName . ' ';
                if(isset($lang->$selectedModule->$listKey)) $paramDesc .= sprintf($lang->dev->paramRange, join(' | ', array_keys($lang->$selectedModule->$listKey)));
                if($paramType == 'date')  $paramDesc .= $lang->dev->paramDate;
                if($paramName == 'color') $paramDesc .= $lang->dev->paramColor;
                if(isset($config->$selectedModule->$methodName->requiredFields) and strpos($config->$selectedModule->$methodName->requiredFields, $paramName) !== false) $paramDesc .= "<span class='red'>*{$lang->required}</span>";
                if($paramName == 'product') $paramDesc .= "<span class='red'>*{$lang->required}</span>";
                if($paramName == 'mailto') $paramDesc .= $lang->dev->paramMailto;

                $postTR[] = h::tr
                (
                    h::td($paramName),
                    h::td($paramType),
                    h::td(html($paramDesc))
                );
            }
        }


        $contents[] = div
        (
            setClass('detail'),
            div
            (
                setClass('detail-title font-bold'),
                !empty($api['post']) ? 'GET/POST' : 'GET',
                '  ' . helper::createLink($selectedModule, $methodName, $params, 'json')
            ),
            div
            (
                setClass('detail-content mt-3'),
                html(zget($api, 'doc', '')),
                h::table
                (
                    setClass('table bordered'),
                    h::tr
                    (
                        h::th($lang->dev->params),
                        h::th($lang->dev->type),
                        h::th($lang->dev->desc),
                    ),
                    isset($api['param']) ? $paramTR : h::tr(h::td(set::colspan(3), $lang->dev->noParam))
                ),
                isset($config->dev->postParams[$selectedModule][$methodName]) ? h::table
                (
                    setClass('table bordered'),
                    h::caption(setClass('text-left'), $lang->dev->post),
                    h::tr
                    (
                        h::th($lang->dev->params),
                        h::th($lang->dev->type),
                        h::th($lang->dev->desc)
                    ),
                    $postTR
                ) : null
            )
        );
    }

    return $contents;
};

$activeGroup = '';
foreach($moduleTree as $module)
{
    if($module->active) $activeGroup = $module->id;
}

h::css("
.sidebar .tree [data-level=\"0\"][id=\"{$activeGroup}\"] {color: var(--color-primary-600); font-weight:bolder}
.sidebar .tree [data-level=\"1\"][id=\"{$selectedModule}\"] {color: var(--color-primary-600); font-weight:bolder}
");

featureBar
(
    set::items($featureBarItems),
    icon(set('title', $lang->dev->apiTips), 'help')
);

sidebar
(
    setClass('bg-white'),
    h::header
    (
        setClass('h-10 flex items-center pl-4 flex-none gap-3'),
        span(setClass('text-lg font-semibold'), icon(setClass('pr-2'), 'list'), $lang->dev->moduleList),
    ),
    treeEditor(set(array('items' => $moduleTree, 'canEdit' => false, 'canDelete' => false, 'canSplit' => false)))
);

div
(
    setClass('bg-white p-3'),
    $selectedModule ? div(setClass("module-content"), $fnBuildAPIContent()) : null
);
