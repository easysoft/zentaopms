<?php
/**
 * The browse view file of dataview module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenxuan Song <1097180981@qq.com>
 * @package     dataview
 * @version     $Id: browse.html.php 4129 2022-11-14 14:42:12 $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('table',      $selectedTable);?>
<?php js::set('dataview',   $dataview);?>
<?php js::set('warningDesign', $lang->dataview->error->warningDesign);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'><?php // common::printAdminSubMenu('dev');?></div>
  <div class="btn-toolbar pull-right">
    <?php if($selectedTable):?>
      <?php if(common::hasPriv('dataview', 'export')) echo html::a($this->createLink('dataview', 'export', "type=$type&table=$selectedTable", '', true), "<i class='icon icon-export'></i> {$lang->dataview->export}", '', "class='iframe btn btn-link'");?>
    <?php endif;?>
    <?php if(common::hasPriv('dataview', 'create')) echo html::a($this->createLink('dataview', 'create'), "<i class='icon icon-plus'></i><span class='text'>" . $lang->dataview->create . "</span>", '', "class='btn btn-secondary'");?>
  </div>
</div>
<script>$('#mainMenu #<?php echo $tab;?>').addClass('btn-active-text')</script>

<div id='mainContent' class='main-row'>
  <div class='side-col' id='sidebar'>
    <div class='cell'>
      <div class="panel panel-sm with-list">
        <div class='panel-heading'>
          <?php foreach($lang->dataview->typeList as $key => $label):?>
          <?php echo html::a(inlink('browse', "type=$key"), "<span class='text'>$label</span>", '', "class='btn btn-link " . ($type == $key ? 'btn-active-text' : '') . "'");?>
          <?php endforeach;?>
        </div>
        <?php if($type == 'table') echo $originTable;?>
        <?php if($type == 'view'):?>
          <?php if(!$groupTree):?>
          <hr class="space">
          <div class="text-center text-muted">
            <?php echo $lang->dataview->noModule;?>
          </div>
          <hr class="space">
          <?php endif;?>
          <?php echo $groupTree;?>
          <div class="text-center">
            <?php common::printLink('tree', 'browsegroup', "dimensionID=0&groupID=0&type=dataview", $lang->dataview->manageGroup, '', "class='btn btn-info btn-wide'");?>
            <hr class="space-sm" />
          </div>
        <?php endif;?>
      </div>
    </div>
  </div>
  <div id='dataContent' class='main-col main-content'>
    <div class='detail'>
      <div class='detail-title'>
        <div class='dataview-title' title='<?php echo $dataTitle;?>'><?php echo $dataTitle;?></div>
        <div class='actions pull-right'>
          <?php if($selectedTable and isset($dataview)):?>
          <?php
            $designTitle  = $lang->dataview->design;
            $editTitle    = $lang->dataview->edit;
            $deleteTitle  = $lang->dataview->delete;

            if(common::hasPriv('dataview', 'query') and $type == 'view') echo html::a('#', "<i class='icon icon-design'></i> {$lang->dataview->design}", '', "class='query-view' title='{$designTitle}'");
            if(common::hasPriv('dataview', 'edit') and $type == 'view') echo html::a($this->createLink('dataview', 'edit', "id=$selectedTable", '', true), "<i class='icon icon-edit'></i> {$lang->dataview->edit}", '', "class='iframe' title='{$editTitle}' data-width='480'");
            if(common::hasPriv('dataview', 'delete') and $type == 'view') echo html::a($this->createLink('dataview', 'delete', "id=$selectedTable"), "<i class='icon icon-trash'></i> {$lang->dataview->delete}", 'hiddenwin', "class='query-delete' title='{$deleteTitle}'");
          ?>
          <?php endif;?>
        </div>
      </div>
      <?php if($selectedTable):?>
      <div class='detail-content'>
        <div class='tabs'>
          <ul class='nav nav-tabs'>
            <li class='active'><a href='#data' data-toggle='tab'><?php echo $lang->dataview->data;?></a></li>
            <li><a href='#schema' data-toggle='tab'><?php echo $lang->dataview->schema;?></a></li>
            <?php if(!empty($dataview)):?>
            <li><a href='#details' data-toggle='tab'><?php echo $lang->dataview->details;?></a></li>
            <?php endif;?>
          </ul>
          <div class='tab-content '>
            <div class='tab-pane active' id='data'>
              <?php if(!empty($fields)):?>
              <table class="table table-bordered" style="min-width: <?php echo count($fields)*100;?>px">
                <thead>
                  <tr>
                    <?php foreach($fields as $key => $field):?>
                    <?php
                    $fieldName = isset($dataview->fieldSettings->$key->name) ? $dataview->fieldSettings->$key->name : $key;
                    if(!empty($dataview->langs))
                    {
                        $langs = json_decode($dataview->langs, true);
                        if(!empty($langs)) $fieldName = $langs[$key][$clientLang] ? $langs[$key][$clientLang] : $fieldName;
                    }
                    ?>
                    <th><?php echo $fieldName;?></th>
                    <?php endforeach;?>
                  </tr>
                </thead>
                <tbody>
                    <?php foreach($data as $value):?>
                    <?php echo '<tr>';?>
                      <?php foreach($fields as $key => $field):?>
                      <td title='<?php echo isset($value->$key) ? $value->$key : 'null';?>'><?php echo isset($value->$key) ? $value->$key : 'null';?></td>
                      <?php endforeach;?>
                    <?php echo '</tr>';?>
                    <?php endforeach;?>
                </tbody>
              </table>
              <?php endif;?>
            </div>
            <div class='tab-pane' id='schema'>
              <?php if(!empty($fields)):?>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th class='w-id'><?php echo $lang->dev->fields['id']?></th>
                    <th><?php echo $lang->dev->fields['name']?></th>
                    <?php if(strpos($selectedTable, $this->config->db->prefix) !== false) echo "<th>{$lang->dev->fields['desc']}</th>"?>
                    <th><?php echo $lang->dev->fields['type']?></th>
                    <th><?php echo $lang->dev->fields['length']?></th>
                    <th><?php echo $lang->dev->fields['null']?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i = 1;?>
                  <?php foreach($fields as $key => $field):?>
                  <tr>
                    <td title='<?php echo $i;?>'><?php echo $i;?></td>
                    <td title='<?php echo $key;?>'><?php echo $key;?></td>
                    <?php if(strpos($selectedTable, $this->config->db->prefix) !== false) echo "<td title='{$field['name']}'>{$field['name']}</td>"?>
                    <td title="<?php echo $field['type'];?>"><?php echo $field['type'];?></td>
                    <td title='<?php echo isset($field['options']['max']) ? $field['options']['max'] : '';?>'><?php echo isset($field['options']['max']) ? $field['options']['max'] : '';?></td>
                    <td title='<?php echo $field['null'];?>'><?php echo $field['null'];?></td>
                  </tr>
                  <?php $i++; endforeach?>
                </tbody>
              </table>
              <?php endif;?>
            </div>
            <?php if(!empty($dataview)):?>
            <div class='tab-pane' id='details'>
              <table class="table table-data table-fixed">
                <tbody>
                  <tr>
                    <th><?php echo $lang->dataview->name;?></th>
                    <td title='<?php echo $dataview->name;?>'><?php echo $dataview->name;?></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->dataview->code;?></th>
                    <td><?php echo $dataview->code;?></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->dataview->view;?></th>
                    <td><?php echo empty($dataview->sql) ? '' : $dataview->view;?></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->dataview->group;?></th>
                    <td><?php echo zget($groups, $dataview->group);?></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->dataview->sql;?></th>
                    <td class='dataview-sql'><?php echo $dataview->sql;?></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <?php endif;?>
          </div>
        </div>
      </div>
      <?php else:?>
      <div class='detail-content'>
        <div class="table-empty-tip">
          <p><span class="text-muted"><?php echo $lang->dataview->notSelect;?></span></p>
        </div>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>
<?php include $this->app->getModuleRoot() . 'common/view/footer.html.php';?>
