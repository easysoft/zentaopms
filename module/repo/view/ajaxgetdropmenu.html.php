<?php
/**
 * The head switcher view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     repo
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<style>
#dropMenu .tree li {padding: 3px 0 0 10px;}
#dropMenu .tree li.has-list.open:before {border-left: 0px;}
#dropMenu .tree li > a {max-width: 100%; line-height: 20px; border-radius: 2px; padding-top: 5px;}
#dropMenu .col-left {padding: 0;}
#dropMenu .label {margin-left: 3px;}
#dropMenu .hide-in-search {padding-left: 8px;}
#dropMenu .hide-in-search .hidden {display: block !important; visibility: inherit !important;}
#dropMenuRepo > div.table-row > div > div > ul > li > div {padding-left: 10px;}
#dropMenu ul.tree-angles {margin-bottom: 0;}
#dropMenu {margin: 0;}
#dropMenu ul > li > ul > li > a:hover {color: white; background-color: #0c64eb; text-decoration: none;}
#dropMenu .tree .has-list > ul > li {padding-top: 0;}
.search-list .list-group {padding: 7px 10px;}
#swapper li>div.hide-in-search>a:focus, #swapper li>div.hide-in-search>a:hover {color: #838a9d; cursor: default;}
#dropMenu .label-type {margin: 1px 10px;}
</style>
<div class="table-row">
  <div class="table-col col-left">
    <div class="list-group" id="repoList">
      <ul class='tree tree-angles' data-ride='tree' data-idx='0'>
      <?php foreach($repoGroup as $groupName => $group):?>
        <?php if(empty($group)) continue;?>
        <li data-idx='$groupName' data-id='<?php echo $groupName?>' class='has-list open in'>
          <i class='list-toggle icon'></i>
          <div class='label-type'>
            <a class='text-muted not-list-item'><?php echo $groupName;?></a>
          </div>
          <ul data-idx='<?php echo $groupName;?>'>
          <?php foreach($group as $id => $repoName):?>
            <?php $isSelected = $id == $repoID ? 'selected' : '';?>
            <?php $repoName   = trim($repoName);?>
            <li data-idx='<?php echo $repoName;?>' data-id='<?php echo $groupName . '-' . $repoName;?>'>
              <a href='<?php echo sprintf($link, $id);?>' id='<?php echo $groupName. '-' . $repoName?>' class='<?php echo $isSelected;?> text-ellipsis' title='<?php echo $repoName;?>' data-key='<?php echo $repoName;?>' data-app='<?php echo $this->app->tab;?>'><?php echo $repoName;?></a>
            </li>
          <?php endforeach;?>
          </ul>
        </li>
      <?php endforeach;?>
      </ul>
    </div>
  </div>
</div>
<script>
$('.tree').tree();
</script>
