<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/form.html.php';?>
<table class='table-1'>
  <caption>
    <?php echo 'GET ' . $filePath?>
  </caption>
  <tr>
    <td>
      <?php echo "<p class='strong'>{$lang->api->position}</p>"?>
      <?php echo '<div>' . sprintf($lang->api->startLine, $method->fileName, $method->startLine) . '</div>'?>
    </td>
  </tr>
  <tr>
    <td>
      <?php echo "<p class='strong'>{$lang->api->desc}</p>"?>
      <?php echo '<div>' . str_replace("\n", "<Br/>", $method->comment) . '</div>'?>
    </td>
  </tr>
  <tr>
    <td>
      <?php echo "<p class='strong'>{$lang->api->test}</p>"?>
      <?php if($method->parameters):?>
      <form method='post' id='apiForm'>
        <table>
          <?php foreach($method->parameters as $param):?>
          <tr>
            <th><?php echo $param->name?></th>
            <td><?php echo html::input("$param->name", $param->isOptional() ? $param->getDefaultValue() : '')?></td>
          </tr>
          <?php endforeach;?>
          <tr>
            <td align='center' colspan="2">
              <?php echo html::submitButton($lang->api->submit)?>
            <td>
          </tr>
        </table>
      </form>
      <?php else:?>
      <?php echo $lang->api->noParam;?>
      <?php endif;?>
    </td>
  </tr>
  <tr>
    <td id="result" class="hidden">
      <p class="strong"><?php echo $lang->api->url?>:</p>
      <div class="url"></div>
      <p class="strong"><?php echo $lang->api->result?>:</p>
      <p><?php echo $lang->api->status?>: <span class="status"></span></p>
      <p><?php echo $lang->api->data?>: <span class="data"></span></p>
    </td>
  <tr>
</table>
<?php include '../../common/view/footer.lite.html.php';?>
