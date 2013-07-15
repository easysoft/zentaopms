<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/form.html.php';?>
<table class='table-1' id='api'>
  <caption>
    <?php echo $method->post ? 'GET/POST ' . $filePath : 'GET ' . $filePath?>
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
      <?php echo "<p class='strong'>{$lang->api->debug}</p>"?>
      <form method='post' id='apiForm'>
      <?php if($method->parameters):?>
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
            </td>
          </tr>
        </table>
      <?php else:?>
      <?php echo html::hidden('noparam', '0') . $lang->api->noParam . html::submitButton($lang->api->submit);?>
      <?php endif;?>
      </form>
      <?php if($method->post) echo "<p>{$lang->api->post}</p>"?>
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
