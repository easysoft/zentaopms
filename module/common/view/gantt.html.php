<?php
/**
 * The view file of fullcalendar module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     fullcalendar
 * @version     $Id$
 * @link        http://www.zentao.net
 */
css::import($jsRoot . 'dhtmlxgantt/min.css');
js::import($jsRoot . 'dhtmlxgantt/min.js');
$currentLang = $app->getClientLang();
if($currentLang != 'en') js::import($jsRoot . 'dhtmlxgantt/lang/' . $currentLang . '.js');
?>
<style>
.gantt_message_area{display:none;}
#ganttPris:hover{background:unset;color:unset;cursor: unset;}
</style>
<script>
gantt.plugins({
    marker: true,
    critical_path: true,
    fullscreen: true,
    tooltip: true,
    click_drag: true
});
</script>
