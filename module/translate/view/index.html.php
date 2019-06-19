<?php include '../../common/view/header.html.php';?>
<div class='row'>
  <div class='u-24-5'>
    <div class='cont-left'>
      <div class='box-title'><?php echo $lang->translate->langVersion?></div>
      <div class='box-content'>
        <ul><?php foreach($lang->translate->zentaoVersions as $key => $version) echo '<li>' . html::a(inlink('index', "zentaoVersion=$key"), $version) . '</li>';?></ul>
      </div>
    </div>
  </div>
  <div class='u-24-19'>
    <table class='table-1 f-14px' align='center'>
      <?php if(empty($zentaoVersion)):?>
      <tr><td class='red'><?php echo $lang->translate->notice->noVersion?></td></tr>
      <?php else:?>
      <?php
      $i = 0;
      foreach($lang->translate->langs as $key => $language)
      {
          if($i % $config->translate->columnNum == 0) echo '<tr>';
          echo '<td class="pd-5px">';
          echo html::a(inlink('showLang', "zentaoversion=$zentaoVersion&language=$key"), $language);
          echo "<span class='f-right'>";
          echo isset($percents[$key]) ? $percents[$key] : (in_array($key, $config->translate->defaultLang) ? '100%' : '0%');
          echo ' ' . html::a(inlink('translate', "zentaoversion=$zentaoVersion&language=$key"), $lang->translate->translate) . ' ';
          echo html::a(inlink('download', "zentaoversion=$zentaoVersion&language=$key"), $lang->translate->download);
          echo '</span></td>';
          $i++;
          if($i % $config->translate->columnNum == 0) echo '</tr>';
      }
      if($i % $config->translate->columnNum != 0) echo '</tr>';
      ?>
      <?php endif;?>
    </table>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
