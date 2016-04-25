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
      <strong><?php echo $lang->feature ?></strong>
    </div>
    <div class='dropdown' id='versionMenu'>
      <button class='btn btn-sm dropdown-toggle' type='button' data-toggle='dropdown'><?php echo $version === 'latest' ? $lang->misc->feature->lastest : ('v' . $version) ?> <span class='caret'></span></button>
      <ul class='dropdown-menu pull-right'>
        <?php foreach ($lang->misc->feature->all as $versionName => $features):?>
        <li<?php echo $versionName === $version ? " class='active'" : '' ?>>
          <?php echo html::a(helper::createLink('misc', 'feature', "version=$versionName"), $versionName === 'latest' ? $lang->misc->feature->lastest : ('v' . $versionName));?>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
  <div id='featureCarousel' class='carousel slide' data-ride='carousel' data-interval='10000'>
    <ol class='carousel-indicators'>
      <?php $idx = 1; ?>
      <?php foreach ($features as $feature):?>
      <li data-target='#featureCarousel' data-slide-to='<?php echo $idx; ?>'<?php echo $idx === 1 ? " class='active'" : '' ?>></li>
      <?php $idx++; ?>
      <?php endforeach; ?>
    </ol>
    <div class='carousel-inner'>
      <?php $idx = 1; ?>
      <?php foreach ($features as $feature):?>
      <?php $imgUrl = $defaultTheme . 'images/feature/' . $version . '/' . $feature['img']; ?>
      <div class='item<?php echo $idx === 1 ? ' active' : '' ?>'>
        <h2><?php echo $feature['title'] ?></h2>
        <img alt='<?php echo $feature['title'] ?>' src='<?php echo $imgUrl ?>'>
        <div class='desc'><?php echo $feature['desc'] ?></div>
      </div>
      <?php $idx++; ?>
      <?php endforeach; ?>
    </div>
    <a class='left carousel-control' href='#featureCarousel' data-slide='prev'>
      <span class='icon icon-chevron-left'></span>
    </a>
    <a class='right carousel-control' href='#featureCarousel' data-slide='next'>
      <span class='icon icon-chevron-right'></span>
    </a>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
