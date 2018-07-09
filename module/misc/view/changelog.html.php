<?php include '../../common/view/header.lite.html.php';?>
<main id="main">
  <div class="container" id='<?php echo $version;?>'>
    <div id='mainContent' class='main-content'>
      <div class='center-block mw-800px'>
        <div class='main-header clearfix'>
          <h2 class='pull-left'><?php echo $lang->changeLog ?></h2>
          <div class='btn-toolbar pull-left'>
            <div class='dropdown' id='versionMenu'>
              <button class='btn dropdown-toggle' type='button' data-toggle='dropdown'><?php echo $version === 'latest' ? $lang->misc->feature->lastest : ($lang->misc->releaseDate[$version] . ' ' . $version) ?> <span class='caret'></span></button>
              <ul class='dropdown-menu'>
                <?php foreach(array_keys($lang->misc->feature->all) as $versionName):?>
                <li<?php echo $versionName === $version ? " class='active'" : '' ?>>
                  <?php echo html::a(helper::createLink('misc', 'changeLog', "version=$versionName"), $versionName === 'latest' ? $lang->misc->feature->lastest : ($lang->misc->releaseDate[$versionName] . ' ' . $versionName));?>
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
        </div>
        <?php if($detailed and $this->app->getClientLang() != 'en'):?>
        <div id="details" class='article-content'>
          <a href='###' class="btn btn-link text-primary btn-block text-left" onclick="$('#details').toggleClass('show-details')"><i class="icon icon-angle-right"></i> <?php echo $lang->misc->feature->detailed;?></a>
          <div class='details-list'><?php echo $detailed;?></div>
        </div>
        <?php endif;?>
      </div>
    </div>
  </div>
</main>
<?php include '../../common/view/footer.lite.html.php';?>
