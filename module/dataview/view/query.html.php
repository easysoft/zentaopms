<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('rawMethod',  $app->rawMethod);?>
<?php js::set('dataview',   $data);?>
<?php js::set('warningDesign', $lang->dataview->error->warningDesign);?>
<?php js::set('langSettings', isset($data->langs) ? $data->langs : '')?>
<?php js::set('objectFields', isset($objectFields) ? $objectFields : array());?>
<?php js::set('fieldSettings', zget($data, 'fieldSettings', ''));?>

<script>
<?php include '../js/datastorage.js';?>
window.DataStorage = initStorage(
{
    fields: {},
    columns: {},
    rows: {},
    langs: langSettings ? JSON.parse(langSettings) : {},
    relatedObject: {},
    fieldSettings: Array.isArray(fieldSettings) || typeof fieldSettings != 'object' ? {} : fieldSettings,
    objectFields: objectFields
}, true);
</script>

<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <div class='page-title'>
      <?php echo html::a($backLink, '<i class="icon icon-angle-left"></i> <span class="text">' . $lang->goback . '</span>', '', "id='back' class='btn btn-link' title={$lang->goback}");?>
    </div>
    <div class='divider'></div>
    <div class='page-title'>
      <span title='<?php echo $title;?>'><?php echo $title;?></span>
    </div>
  </div>
  <div class="create-action pull-right">
    <?php if($app->rawMethod == 'create') echo html::a($saveLink, '<i class="icon icon-save"></i> ' . $lang->save, '', "class='btn btn-primary' id='saveButton'");?>
    <?php if($app->rawMethod != 'create') echo '<button type="button" class="btn btn-primary" id="save"><i class="icon icon-save"></i> ' . $lang->save. '</button>';?>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <style><?php include '../css/querybase.css';?></style>
    <?php include 'querybase.html.php';?>
    <script><?php include '../js/querybase.js';?></script>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
