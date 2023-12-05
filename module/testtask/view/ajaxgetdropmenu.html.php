<?php
/**
 * The ajaxgetdropmenu file of testtask module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     testtask
 * @version     $Id: ajaxgetdropmenu.html.php 935 2022-06-27 16:44:24Z $
 * @link        https://www.zentao.net
 */
?>
<style>
#currentTesttask + #dropMenu {max-width: 250px;}
#currentTesttask + #dropMenu > .list-group {overflow-x: hidden;}
#currentTesttask + #dropMenu .table-row > .table-col > .list-group {max-width: 248px;}
</style>
<?php
$selectHtml = '';
foreach($testtasks as $testtaskID => $testtask)
{
    if($objectType == 'project' and $testtask->project != $objectID) continue;
    if($objectType == 'execution' and $testtask->execution != $objectID) continue;

    $selected    = (string)$testtaskID == $currentTaskID ? 'selected' : '';
    $param       = $method == 'report' ? "productID=$productID&taskID=$testtaskID&browseType=all&branch=$branch" : "taskID=$testtaskID";
    $selectHtml .= html::a($this->createLink($module, $method, $param), $testtask->name, '', "class='$selected' data-key='{$testtasksPinyin[$testtask->name]}' data-app='{$this->app->tab}' title='{$testtask->name}'");
}
?>
<div class="table-row">
  <div class="table-col">
    <div class='list-group'><?php echo $selectHtml;?></div>
  </div>
</div>
<script>
$(function()
{
    $('#currentTesttask + #dropMenu .list-group .table-row .table-col a').mouseout(function()
    {
        $(this).removeClass('active');
    })
})
</script>
