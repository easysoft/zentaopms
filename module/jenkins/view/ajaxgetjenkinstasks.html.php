<?php
/**
 * The jenkins task view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Gang Zeng <zenggang@cnezsoft.com>
 * @package     repo
 * @version     $Id$
 * @link        https://www.zentao.net
 */

/**
 * Render task tree HTML recursively.
 *
 * Each call handles one level of the tree. Branch nodes (array values)
 * recurse into children, leaf nodes (string values) render as clickable links.
 *
 * @param  array  $tasks
 * @param  int    $level
 * @access private
 * @return string
 */
function renderTaskTreeHtml($tasks, $level = 0)
{
    $html = '';
    foreach($tasks as $name => $task)
    {
        if(empty($task))
        {
            continue;
        }

        if(is_array($task))
        {
            $oneLevel = $level === 0 ? ' one-level' : '';
            $html .= "<li data-idx='\$groupName' data-id='{$name}' class='has-list open in{$oneLevel}'>";
            $html .= '<i class="list-toggle icon"></i>';
            $html .= "<div class='label-type'><a class='text-muted not-list-item'>{$name}</a></div>";
            $html .= "<ul data-idx='{$name}'>";
            $html .= renderTaskTreeHtml($task, $level + 1);
            $html .= '</ul>';
            $html .= '</li>';
        }
        else
        {
            $displayId = ($level === 0) ? $task : $name;
            $html .= "<li data-idx='{$displayId}' data-id='{$displayId}'>";
            $html .= "<a href='###' id='{$name}' class='text-ellipsis' onclick=\"setJenkinsJob('{$task}','{$name}')\" title='{$task}' data-key='{$task}'>{$task}</a>";
            $html .= '</li>';
        }
    }
    return $html;
}
?>
<style>
#dropMenuTasks .tree li {padding: 3px 0 0 10px;}
#dropMenuTasks .tree li.has-list.open:before {border-left: 0px;}
#dropMenuTasks .tree li > a {max-width: 100%; line-height: 20px; border-radius: 2px; padding-top: 5px;}
#dropMenuTasks .col-left {padding: 0;}
#dropMenuTasks .label {margin-left: 3px;}
#dropMenuTasks .hide-in-search {padding-left: 8px;}
#dropMenuTasks .hide-in-search .hidden {display: block !important; visibility: inherit !important;}
#dropMenuTasksRepo > div.table-row > div > div > ul > li > div {padding-left: 10px;}
#dropMenuTasks ul.tree-angles {margin-bottom: 0;}
#dropMenuTasks {margin: 0;}
#dropMenuTasks ul > li > ul > li > a:hover {color: white; background-color: #0c64eb; text-decoration: none;}
#dropMenuTasks .tree .has-list > ul > li {padding-top: 0;}
.search-list .list-group {padding: 7px 10px;}
#dropMenuTasks .label-type {margin: 1px 10px; line-height: 20px;}
.tree li>.list-toggle {top: 0px;}
.tree .one-level>.list-toggle {top: 3px;}
</style>
<div class="table-row">
  <div class="table-col col-left">
    <div class="list-group" id="jenkinsTaskList">
      <ul class='tree tree-angles' data-ride='tree' data-idx='0'>
      <?php echo renderTaskTreeHtml($tasks);?>
      </ul>
    </div>
  </div>
</div>
<script>
$('#jenkinsTaskList .tree').tree();
</script>
