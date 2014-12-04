<?php
/**
 * The action->dynamic view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: action->dynamic.html.php 1477 2011-03-01 15:25:50Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<script language='Javascript'>
var browseType = '<?php echo $browseType;?>';
</script>
<div id='featurebar'>
  <ul class='nav'>
    <?php 
    echo '<li id="today">'       . html::a(inlink('dynamic', "browseType=today"),      $lang->action->dynamic->today)      . '</li>';
    echo '<li id="yesterday">'   . html::a(inlink('dynamic', "browseType=yesterday"),  $lang->action->dynamic->yesterday)  . '</li>';
    echo '<li id="twodaysago">'  . html::a(inlink('dynamic', "browseType=twodaysago"), $lang->action->dynamic->twoDaysAgo) . '</li>';
    echo '<li id="thisweek">'    . html::a(inlink('dynamic', "browseType=thisweek"),   $lang->action->dynamic->thisWeek)   . '</li>';
    echo '<li id="lastweek">'    . html::a(inlink('dynamic', "browseType=lastweek"),   $lang->action->dynamic->lastWeek)   . '</li>';
    echo '<li id="thismonth">'   . html::a(inlink('dynamic', "browseType=thismonth"),  $lang->action->dynamic->thisMonth)  . '</li>';
    echo '<li id="lastmonth">'   . html::a(inlink('dynamic', "browseType=lastmonth"),  $lang->action->dynamic->lastMonth)  . '</li>';
    echo '<li id="all">'         . html::a(inlink('dynamic', "browseType=all"),        $lang->action->dynamic->all)        . '</li>';
    echo "<li id='account' class='w-120px'>"     . html::select('account', $users, $account, 'onchange=changeUser(this.value) class="form-control chosen"') . '</li>';
    echo "<li id='product' class='w-180px'>"     . html::select('product', $products, $product, 'onchange=changeProduct(this.value) class="form-control chosen"') . '</li>';
    echo "<li id='project' class='w-180px' style='margin-right: 10px;'>"     . html::select('project', $projects, $project, 'onchange=changeProject(this.value) class="form-control chosen"') . '</li>';
    echo "<li id='bysearchTab'>" . html::a('#', '<i class="icon-search icon"></i>&nbsp;' . $lang->action->dynamic->search) . "</li>";
    ?>
  </ul>
  <div id='querybox' class='<?php if($browseType =='bysearch') echo 'show';?>'></div>
</div>
  
<table class='table table-condensed table-hover table-striped tablesorter table-fixed'>
  <thead>
    <tr class='colhead'>
      <?php $vars = "browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
      <th class='w-150px'><?php common::printOrderLink('date',       $orderBy, $vars, $lang->action->date);?></th>
      <th class='w-user'> <?php common::printOrderLink('actor',      $orderBy, $vars, $lang->action->actor);?></th>
      <th class='w-100px'><?php common::printOrderLink('action',     $orderBy, $vars, $lang->action->action);?></th>
      <th class='w-80px'> <?php common::printOrderLink('objectType', $orderBy, $vars, $lang->action->objectType);?></th>
      <th class='w-id'>   <?php common::printOrderLink('objectID',   $orderBy, $vars, $lang->idAB);?></th>
      <th><?php echo $lang->action->objectName;?></th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($actions as $action):?>
  <?php $module = $action->objectType == 'case' ? 'testcase' : $action->objectType;?>
  <tr class='text-center'>
    <td><?php echo $action->date;?></td>
    <td>
      <?php 
      $actor = isset($users[$action->actor]) ? $users[$action->actor] : $action->actor;
      echo strpos($actor, ':') === false ? $actor : substr($actor, strpos($actor, ':') + 1);
      ?>
    </td>
    <td><?php echo $action->actionLabel;?></td>
    <td><?php echo $lang->action->objectTypes[$action->objectType];?></td>
    <td><?php echo $action->objectID;?></td>
    <td class='text-left'><?php echo html::a($action->objectLink, $action->objectName);?></td>
  </tr>
  <?php endforeach;?>
  </tbody>
  <tfoot><tr><td colspan='6'><?php $pager->show();?></td></tr></tfoot>
</table>
<script>$('#<?php echo $browseType;?>').addClass('active')</script>
<?php include '../../common/view/footer.html.php';?>
