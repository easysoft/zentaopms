<?php
/**
 * The splitcolumn view file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
    <form class='form-ajax' metdod='post' enctype='multipart/form-data' id='columnForm'>
      <table class='table table-form'>
        <?php $i = 1;?>
        <tr>
          <td class='c-nameTitle'><strong><?php echo $lang->kanbancolumn->childName;?></strong></td>
          <td class='c-color required'>
            <div class="input-control has-icon-right">
              <?php echo html::input("name[$i]", '', "class='form-control' id='name$i'");?>
              <div class="colorpicker">
                <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                <ul class="dropdown-menu clearfix">
                </ul>
                <input type='hidden' id='color<?php echo $i;?>' name="color[<?php echo $i;?>]" class='form-control'data-icon="color" data-provide='colorpicker' data-wrapper='input-control-icon-right' data-pull-menu-right="true" data-optional='false' value='#333' data-colors="<?php echo implode(',', $config->kanban->columnColorList);?>">
              </div>
            </div>
          </td>
          <td class='c-WIPTitle'><strong><?php echo $lang->kanban->WIPCount;?></strong></td>
          <td class='c-WIPCount required'>
            <div class="table-col">
              <?php echo html::input("WIPCount[$i]", '', "class='form-control' disabled id='WIPCount$i'");?>
            </div>
            <div class="table-col w-50px">
              <span class="input-group-addon" style="border: 1px solid #dcdcdc; border-left-widtd: 0px;">
                <div class='checkbox-primary'>
                  <input id="noLimit<?php echo $i;?>" name="noLimit[<?php echo $i;?>]" value='-1' type='checkbox' class='no-margin' checked/>
                  <label for='needNotReview'><?php echo $lang->kanban->noLimit;?></label>
                </div>
              </span>
            </div>
          </td>
        </tr>
        <?php $i++;?>
        <tr>
          <td class='c-nameTitle'><strong><?php echo $lang->kanbancolumn->childName;?></strong></td>
          <td class='c-color required'>
            <div class="input-control has-icon-right">
              <?php echo html::input("name[$i]", '', "class='form-control' id='name$i'");?>
              <div class="colorpicker">
                <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                <ul class="dropdown-menu clearfix">
                </ul>
                <input type='hidden' id='color<?php echo $i;?>' name="color[<?php echo $i;?>]" class='form-control' data-icon="color" data-provide='colorpicker' data-wrapper='input-control-icon-right' data-pull-menu-right="true" data-optional='false' value='#333' data-colors="<?php echo implode(',', $config->kanban->columnColorList);?>">
              </div>
            </div>
          </td>
          <td class='c-WIPTitle'><strong><?php echo $lang->kanban->WIPCount;?></strong></td>
          <td class='c-WIPCount required'>
            <div class="table-col">
              <?php echo html::input("WIPCount[$i]", '', "class='form-control' disabled id='WIPCount$i'");?>
            </div>
            <div class="table-col w-50px">
              <span class="input-group-addon" style="border: 1px solid #dcdcdc; border-left-widtd: 0px;">
                <div class='checkbox-primary'>
                  <input id="noLimit<?php echo $i;?>" name="noLimit[<?php echo $i;?>]" value='-1' type='checkbox' class='no-margin' checked/>
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
        <?php $i++;?>
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
      <td class='c-nameTitle'><strong><?php echo $lang->kanbancolumn->childName;?></strong></td>
      <td class='c-color required'>
        <div class="input-control has-icon-right">
          <?php echo html::input("name[$i]", '', "class='form-control' id='name$i'");?>
          <div class="colorpicker">
            <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
            <ul class="dropdown-menu clearfix">
            </ul>
            <input type='hidden' id='color<?php echo $i;?>' name="color[<?php echo $i;?>]" class='form-control' data-icon="color" data-provide='colorpicker' data-wrapper='input-control-icon-right' data-pull-menu-right="true" data-optional='false' value='#333' data-colors="<?php echo implode(',', $config->kanban->columnColorList);?>">
          </div>
        </div>
      </td>
      <td class='c-WIPTitle'><strong><?php echo $lang->kanban->WIPCount;?></strong></td>
      <td class='c-WIPCount required'>
        <div class="table-col">
          <?php echo html::input("WIPCount[$i]", '', "class='form-control' disabled id='WIPCount$i'");?>
        </div>
        <div class="table-col w-50px">
          <span class="input-group-addon" style="border: 1px solid #dcdcdc; border-left-width: 0px;">
            <div class='checkbox-primary'>
              <input id="noLimit<?php echo $i;?>" name="noLimit[<?php echo $i;?>]" value='-1' type='checkbox' class='no-margin' checked/>
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
