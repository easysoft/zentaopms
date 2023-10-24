<?php //include '../../common/view/header.html.php';?>
<?php include '../../common/view/header.lite.html.php';?>
<header id='header'>
  <div id='mainHeader' style='height: 0px;'>
  </div>
</header>
<?php
if(!empty($config->sso->redirect))
{
    css::import($defaultTheme . 'bindranzhi.css');
    js::import($jsRoot . 'bindranzhi.js');
}
?>
<script>
adjustMenuWidth();
</script>
<main id='main' <?php if(!empty($config->sso->redirect)) echo "class='ranzhiFixedTfootAction'";?> >
  <div class='container'>

<div class='row'>
  <div class='col-md-12'>
    <form method='post' action='<?php echo inlink('index')?>' class='no-stash'>
      <div class='input-group col-md-8 col-md-offset-2'>
        <span class='input-group-btn w-auto'><?php echo html::input('words', $words, "class='form-control'")?></span>
        <span class='input-group-btn select'><?php echo html::select('type[]', $typeList, $type, "class='form-control chosen' multiple")?></span>
        <span class='input-group-btn'><?php echo html::submitButton($lang->search->common, '', 'btn btn-primary')?></span>
      </div>
    </form>
    <div class='list list-condensed'>
      <header>
        <h2><?php echo $lang->search->result;?></h2>
      </header>
      <section class='items items-hover'>
        <?php foreach($results as $object):?>
        <div class='item'>
          <div class='item-heading'>
            <div class="pull-right">
              <span><?php echo html::a($object->url . (strpos($object->url, '?') !== false ? '&onlybody=yes' : '?onlybody=yes'), $lang->search->preview, '', "class='iframe' data-width='90%'")?></span>
            </div>
            <h4>
              <?php
              $objectType = $object->objectType == 'case' ? 'testcase' : $object->objectType;
              $html = html::a($object->url, $object->title, '', 'title="' . strip_tags($object->title) . '"');

              if($objectType == 'task')
              {
                  $isMultiProject = $this->dao->select('multiple')->from(TABLE_PROJECT)->alias('t1')
                      ->leftJoin(TABLE_TASK)->alias('t2')->on('t2.execution=t1.id')
                      ->where('t2.id')->eq($object->objectID)
                      ->fetch('multiple');
                  if(!$isMultiProject) $html = html::a($object->url, $object->title, '', 'data-app="project" title="' . strip_tags($object->title) . '"');
              }

              echo $html;
              if(($objectType == 'story' || $objectType == 'requirement' || $objectType == 'execution' || $objectType == 'issue') and !empty($object->extraType))
              {
                  echo "<small class=''>[{$lang->search->objectTypeList[$object->extraType]} #{$object->objectID}]</small> ";
              }
              else
              {
                  echo "<small class=''>[{$lang->searchObjects[$objectType]} #{$object->objectID}]</small> ";
              }
              ?>
            </h4>
          </div>
          <div class='item-content'>
            <div class='text text-muted'><?php echo $object->summary;?></div>
          </div>
        </div>
        <?php endforeach;?>
      </section>
      <footer class='table-footer'>
        <?php echo str_replace($words, urlencode($words), $pager->get('right', 'short'));?>
        <span class='execute-info text-muted'><?php printf($lang->search->executeInfo, $pager->recTotal, $consumed);?></span>
      </footer>
    </div>
  </div>
</div>
<script>
$(function()
{
    $('#searchbox #searchInput').val(<?php echo json_encode($words)?>);
})
</script>
<?php include '../../common/view/footer.html.php';?>
