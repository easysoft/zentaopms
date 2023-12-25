<?php
declare(strict_types=1);
/**
 * The treemap view file of host module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     host
 * @link        https://www.zentao.net
 */
namespace zin;

h::script('var jQuery  = $;');
h::importCss($app->getWebRoot() . 'theme/zui/treemap/min.css');
h::importJs($app->getWebRoot() . 'js/zui/treemap/min.js');

$config->host->featureBar[$type]['active'] = true;
featureBar(set::items($config->host->featureBar));

treemap
(
    setClass('canvas'),
    set::data($treemap),
    set::onNodeClick(jsRaw('(node) => window.onTreemapNodeClick(node)'))
);
