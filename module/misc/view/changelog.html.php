<?php include '../../common/view/header.lite.html.php';?>
<?php
$features = $lang->misc->feature->all[$version];
if(empty($features))
{
    $version = 'latest';
    $features = $lang->misc->feature->all[$version];
}
?>
<div class='container mw-800px'>
  <div id='titlebar'>
    <div class='heading'>
      <strong><?php echo $lang->changeLog ?></strong>
    </div>
  </div>
  <div class='dropdown' id='versionMenu'>
    <button class='btn btn-sm dropdown-toggle' type='button' data-toggle='dropdown'><?php echo $version === 'latest' ? $lang->misc->feature->lastest : ('v' . $version) ?> <span class='caret'></span></button>
    <ul class='dropdown-menu'>
      <?php foreach ($lang->misc->feature->all as $versionName => $features):?>
      <li<?php echo $versionName === $version ? " class='active'" : '' ?>>
        <?php echo html::a(helper::createLink('misc', 'changeLog', "version=$versionName"), $versionName === 'latest' ? $lang->misc->feature->lastest : ('v' . $versionName));?>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
  <div id='featureList' class='article-content'>
    <?php $idx = 1; ?>
    <?php foreach ($features as $feature):?>
    <div class='item'>
      <h3><?php echo $idx . '. ' . $feature['title'] ?></h3>
      <div class='desc'><?php echo $feature['desc'] ?></div>
    </div>
    <?php $idx++; ?>
    <?php endforeach; ?>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
