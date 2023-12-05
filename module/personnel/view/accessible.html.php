<?php
/**
 * The html template file of accessible method of personnel module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
    <div id="queryBox" class="cell <?php if($browseType == 'bysearch') echo ' show';?>" data-module="accessible"></div>
    <form class="main-table table-personnel" action="" data-ride="table">
      <table id="accessibleList" class="table has-sort-head">
        <thead>
          <tr>
            <th class="c-id"><?php echo $lang->idAB;?></th>
            <th class="c-user"><?php echo $lang->personnel->realName;?></th>
            <th class="c-department"><?php echo $lang->personnel->department;?></th>
            <th class="c-job"><?php echo $lang->personnel->job;?></th>
            <th class="c-user"><?php echo $lang->personnel->userName;?></th>
            <th class="c-genders"><?php echo $lang->personnel->genders;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($personnelList as $personnel):?>
          <tr>
            <td class="c-id"><?php echo $personnel->id;?></td>
            <td class="c-name"><?php echo $personnel->realname;?></td>
            <td class="c-name"><?php echo zget($deptList, $personnel->dept);?></td>
            <td><?php echo zget($lang->user->roleList, $personnel->role, '');?></td>
            <td class="c-name"><?php echo $personnel->account;?></td>
            <td><?php echo zget($lang->user->genderList, $personnel->gender, '');?></td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <div class="table-footer">
        <?php $pager->show('right', 'pagerjs');?>
      </div>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
