<?php
/**
 * The splitcolumn view file of kanban module of ZentaoPMS.
 *
 * @copyright   Copyright 2009-2020 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @autdor      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     kanban
 * @version     $Id: splitcolumn.html.php 935 2021-12-16 10:24:24Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
    <h2><span><?php echo $lang->kanban->splitColumn;?></span></h2>
    </div>
    <form metdod='post' enctype='multipart/form-data' target='hiddenwin' id='columnForm'>
      <table class='table table-form'>
        <?php $i = 1;?>
        <tr>
          <td class='c-id'><strong><?php echo $i;?></strong></td>
          <td class='c-nameTitle'><strong><?php echo $lang->kanbancolumn->childName;?></strong></td>
          <td class='c-name'><?php echo html::input("name[$i]", '', "class='form-control'");?></td>
          <td class='c-colorTitle'><strong><?php echo $lang->kanbancolumn->childColor;?></strong></td>
          <td class='c-color'><?php echo html::select("color[$i]", '', '', "class='form-control chosen'");?></td>
          <td class='c-colorTitle'><strong><?php echo $lang->kanban->WIPCount;?></strong></td>
          <td class='c-WIPCount required'>
            <div class="table-col">
              <?php echo html::input("WIPCount[$i]", '', "class='form-control' disabled id='WIPCount$i'");?>
            </div>
            <div class="table-col w-50px">
              <span class="input-group-addon" style="border: 1px solid #dcdcdc; border-left-widtd: 0px;">
                <div class='checkbox-primary'>
                  <input id="noLimit<?php echo $i;?>" name="noLimit<?php echo $i;?>" value='-1' type='checkbox' class='no-margin' checked/>
                  <label for='needNotReview'><?php echo $lang->kanban->noLimit;?></label>
                </div>
              </span>
            </div>
          </td>
        </tr>
        <?php $i++;?>
        <tr>
          <td class='c-id'><strong><?php echo $i;?></strong></td>
          <td class='c-nameTitle'><strong><?php echo $lang->kanbancolumn->childName;?></strong></td>
          <td class='c-name required'><?php echo html::input("name[$i]", '', "class='form-control'");?></td>
          <td class='c-colorTitle'><strong><?php echo $lang->kanbancolumn->childColor;?></strong></td>
          <td class='c-color required'><?php echo html::select("color[$i]", '', '', "class='form-control chosen'");?></td>
          <td class='c-colorTitle'><strong><?php echo $lang->kanban->WIPCount;?></strong></td>
          <td class='c-WIPCount required'>
            <div class="table-col">
              <?php echo html::input("WIPCount[$i]", '', "class='form-control' disabled id='WIPCount$i'");?>
            </div>
            <div class="table-col w-50px">
              <span class="input-group-addon" style="border: 1px solid #dcdcdc; border-left-widtd: 0px;">
                <div class='checkbox-primary'>
                  <input id="noLimit<?php echo $i;?>" name="noLimit<?php echo $i;?>" value='-1' type='checkbox' class='no-margin' checked/>
                  <label for='needNotReview'><?php echo $lang->kanban->noLimit;?></label>
                </div>
              </span>
            </div>
          </td>
          <td class='c-action table-row'>
            <a href='javascript:;' onclick='addItem(this)' class='btn btn-link'><i class='icon-plus'></i></a>
            <a href='javascript:;' onclick='deleteItem(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
          </td>
        </tr>
        <tr>
          <td colspan='12' class='text-center form-actions'>
            <?php echo html::submitButton();?>
            <?php echo html::commonButton($lang->cancel, "data-dismiss='modal'", 'btn btn-wide');?>
          </td>
        </tr>
      </table>
    <?php js::set('i', $i);?>
    </form>
  </div>
</div>
<div>
  <?php $i = '%i%';?>
  <table class='hidden'>
    <tr id='addItem' class='hidden'>
      <td class='c-id'><strong><?php echo $i;?></strong></td>
      <td class='c-nameTitle'><strong><?php echo $lang->kanbancolumn->childName;?></strong></td>
      <td class='c-name required'><?php echo html::input("name[$i]", '', "class='form-control'");?></td>
      <td class='c-colorTitle'><strong><?php echo $lang->kanbancolumn->childColor;?></strong></td>
      <td class='c-color required'><?php echo html::select("color[$i]", '', '', "class='form-control chosen'");?></td>
      <td class='c-colorTitle'><strong><?php echo $lang->kanban->WIPCount;?></strong></td>
      <td class='c-WIPCount required'>
        <div class="table-col">
          <?php echo html::input("WIPCount[$i]", '', "class='form-control' disabled id='WIPCount$i'");?>
        </div>
        <div class="table-col w-50px">
          <span class="input-group-addon" style="border: 1px solid #dcdcdc; border-left-widtd: 0px;">
            <div class='checkbox-primary'>
              <input id="noLimit<?php echo $i;?>" name="noLimit<?php echo $i;?>" value='-1' type='checkbox' class='no-margin' checked/>
              <label for='needNotReview'><?php echo $lang->kanban->noLimit;?></label>
            </div>
          </span>
        </div>
      </td>
      <td class='c-action table-row'>
        <a href='javascript:;' onclick='addItem(this)' class='btn btn-link'><i class='icon-plus'></i></a>
        <a href='javascript:;' onclick='deleteItem(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
      </td>
    </tr>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
