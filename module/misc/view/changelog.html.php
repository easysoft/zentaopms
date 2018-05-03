<?php include '../../common/view/header.lite.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block mw-800px'>
    <div class='main-header clearfix'>
      <h2 class='pull-left'><?php echo $lang->changeLog ?></h2>
      <div class='btn-toolbar pull-left'>
        <div class='dropdown' id='versionMenu'>
          <button class='btn dropdown-toggle' type='button' data-toggle='dropdown'><?php echo $version === 'latest' ? $lang->misc->feature->lastest : ('v' . $version) ?> <span class='caret'></span></button>
          <ul class='dropdown-menu'>
            <?php foreach(array_keys($lang->misc->feature->all) as $versionName):?>
            <li<?php echo $versionName === $version ? " class='active'" : '' ?>>
              <?php echo html::a(helper::createLink('misc', 'changeLog', "version=$versionName"), $versionName === 'latest' ? $lang->misc->feature->lastest : ('v' . $versionName));?>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
    <div id='featureList' class='article-content'>
      <?php $idx = count($features) == 1 ? '' : 1; ?>
      <?php foreach ($features as $feature):?>
      <div class='item'>
        <h3><?php echo $idx . ($idx == '' ? '' : '. ') . $feature['title'] ?></h3>
        <div class='desc'><?php echo $feature['desc'] ?></div>
      </div>
      <?php $idx++; ?>
      <?php endforeach; ?>
      <?php if($detailed and $this->app->getClientLang() != 'en'):?>
      <div> <a href='###' onclick="$('.detailed').toggle()"><?php echo $lang->misc->feature->detailed;?></a></div>
      <div class='detailed hide'><?php echo $detailed;?></div>
      <?php endif;?>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
