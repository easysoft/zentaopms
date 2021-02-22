<?php
/**
 * The html productlist file of productlist method of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php foreach($lang->product->featureBar['all'] as $key => $label):?>
    <?php echo html::a(inlink("all", "browseType=$key"), "<span class='text'>{$label}</span>", '', "class='btn btn-link'");?>
    <?php endforeach;?>
    <?php common::printLink('product', 'manageLine', '', "<span class='text'>{$lang->product->line}</span>", '', 'class="btn btn-link btn-active-text"');?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('product', 'create', '', '<i class="icon icon-plus"></i>' . $lang->product->create, '', 'class="btn btn-primary"');?>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <div class='side-col col-4'>
    <div class='panel'>
      <div class='panel-heading'>
        <div class="panel-title"></div>
        <div class='panel-body'>
          <ul id='modulesTree' data-name='tree-line'></ul> 
        </div>
      </div>
    </div>
  </div>
  <div class='main-col col-8'>
    <div class='panel'>
      <div class='panel-heading'>
        <div class="panel-title"><?php echo $lang->product->manageLine;?></div>
        <div class='panel-body'>
          <form id='lineForm' method='post' target='hiddenwin'>
            <table class='table table-form table-auto'>
              <tr>
                <td>
                  <div id='son'>
                    <div class="table-row row-module row-module-new">
                      <div class="table-col text-center"><strong><?php echo $lang->product->lineName;?></strong></div>
                      <div class="table-col text-center"><strong><?php echo $lang->product->program;?></strong></div>
                      <div class="table-col col-actions"> </div>
                    </div>
                    <?php $maxOrder = 0;?>
                    <?php foreach($sons as $son):?>
                    <div class="table-row row-module">
                      <div class="table-col col-module"><?php echo html::input("modules[id$son->id]", $son->name, 'class="form-control"');?></div>
                      <div class="table-col col-programs"><?php echo html::select("programs[id$son->id]", $programs, $son->root, "class='form-control chosen'");?></div>
                      <div class="table-col col-actions"> </div>
                    </div>
                    <?php endforeach;?>
                    <?php for($i = 0; $i <= 5 ; $i ++):?>
                    <div class="table-row row-module row-module-new">
                      <div class="table-col col-module"><?php echo html::input("modules[]", '', "class='form-control'");?></div>
                      <div class="table-col col-programs"><?php echo html::select("programs[]", $programs, '', "class='form-control chosen'");?></div>
                      <div class="table-col col-actions">
                        <button type="button" class="btn btn-link btn-icon btn-add" onclick="addItem(this)"><i class="icon icon-plus"></i></button>
                        <button type="button" class="btn btn-link btn-icon btn-delete" onclick="deleteItem(this)"><i class="icon icon-close"></i></button>
                      </div>
                    </div>
                  <?php endfor;?>
                  </div> 
                  <div id="insertItemBox" class="template">
                    <div class="table-row row-module row-module-new">
                      <div class="table-col col-module"><?php echo html::input("modules[]", '', "class='form-control'");?></div>
                      <div class="table-col col-programs"><?php echo html::select("programs[]", $programs, '', "class='form-control chosen'");?></div>
                      <div class="table-col col-actions">
                        <button type="button" class="btn btn-link btn-icon btn-add" onclick="addItem(this)"><i class="icon icon-plus"></i></button>
                        <button type="button" class="btn btn-link btn-icon btn-delete" onclick="deleteItem(this)"><i class="icon icon-close"></i></button>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
              <tr>
                <td colspan="2" class="form-actions">
                  <?php echo html::submitButton();?>
                  <?php echo html::backButton();?>
                </td>
              </tr>
            </table>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
