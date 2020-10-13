<?php
/**
 * The html template file of accessible method of personnel module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id
 */
?>
<?php include '../../common/view/header.html.php';
js::set('deptID', $deptID);
?>
<div id="mainMenu" class="clearfix">
  <div id="sidebarHeader">
    <div class="title">
      <?php echo empty($dept->name) ? $lang->dept->common : $dept->name;?>
      <?php if($deptID) echo html::a(inlink('accessible', "program=$programID&deptID=0"), "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted'");?>
    </div>
  </div>
  <div class="btn-toolbar pull-left">
    <a id="bysearchTab" class="btn btn-link querybox-toggle"><i class="icon icon-search muted"></i><?php echo $lang->personnel->search;?></a>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <div id="sidebar" class="side-col">
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class="cell">
      <?php echo $deptTree;?>
    </div>
  </div>
  <div class="main-col">
    <div id="queryBox" class="cell" data-module="accessible"></div>
    <form class="main-table table-personnel" action="" data-ride="table">
      <?php $vars = "programID=$programID&deptID=$deptID&param=$param&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
      <table id="accessibleList" class="table has-sort-head">
        <thead>
          <tr>
            <th class="c-id"><?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
            <th class="w-120px"><?php echo common::printOrderLink('department', $orderBy, $vars, $lang->personnel->department);?></th>
            <th class="w-100px"><?php echo $lang->personnel->realName;?></th>
            <th class="w-100px"><?php echo $lang->personnel->userName;?></th>
            <th class="w-80px"><?php echo $lang->personnel->job;?></th>
            <th class="w-60px"><?php echo $lang->personnel->genders;?></th>
          </tr>
        </thead>
      </table>
    </form>
  </div>
</div>
<script>
$('#dept' + deptID).addClass('active');
$(".tree .active").parent('li').addClass('active');
</script>
<?php include '../../common/view/footer.html.php';?>
