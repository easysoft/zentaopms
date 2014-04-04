<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/form.html.php';?>
<div class='panel panel-sm' id='api'>
  <div class='panel-heading'><i class='icon-terminal'></i> &nbsp;<strong><?php echo $method->post ? 'GET/POST ' . $filePath : 'GET ' . $filePath?></strong></div>
  <div class='panel-body'>
    <?php echo "<h5>{$lang->api->position}</h5>"?>
    <?php echo '<code>' . sprintf($lang->api->startLine, $method->fileName, $method->startLine) . '</code>'?>
    <hr>
    <?php echo "<h5>{$lang->api->desc}</h5>"?>
    <?php echo '<pre>' . str_replace("\n", "<Br/>", $method->comment) . '</pre>'?>
    <hr>
    <?php echo "<h5>{$lang->api->debug}</h5>"?>
    <form method='post' id='apiForm' class='form-condensed'>
    <?php if($method->parameters):?>
      <table class='table table-form'>
        <?php foreach($method->parameters as $param):?>
        <tr>
          <th class='w-80px'><?php echo $param->name?></th>
          <td><?php echo html::input("$param->name", $param->isOptional() ? $param->getDefaultValue() : '', "class='form-control'")?></td>
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
    <div id="result" class="hidden">
      <hr>
      <h5><?php echo $lang->api->url?>:</h5>
      <div class="url"></div>
      <h5><?php echo $lang->api->result?>:</h5>
      <p><?php echo $lang->api->status?>: <code class="status"></code></p>
      <p><?php echo $lang->api->data?>: <pre class="data"></pre></p>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
