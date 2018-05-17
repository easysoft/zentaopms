<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/form.html.php';?>
<div class='main-header'>
  <div class='heading'><i class='icon-terminal'></i> &nbsp;<strong><?php echo $method->post ? 'GET/POST ' . $filePath : 'GET ' . $filePath?></strong></div>
</div>
<div class='detail'>
  <div class='detail-title'><?php echo $lang->api->position;?></div>
  <div class='detail-content'><code><?php printf($lang->api->startLine, $method->fileName, $method->startLine);?></code></div>
</div>
<div class='detail'>
  <div class='detail-title'><?php echo $lang->api->desc;?></div>
  <div class='detail-content'><pre><?php echo str_replace("\n", "<br />", $method->comment);?></pre></div>
</div>
<div class='detail'>
  <div class='detail-title'><?php echo $lang->api->debug;?></div>
  <div class='detail-content'>
    <form method='post' id='apiForm'>
      <?php if($method->parameters):?>
      <table class='table table-form'>
        <?php foreach($method->parameters as $param):?>
        <tr>
          <th class='w-80px'><?php echo $param->name?></th>
          <td><?php echo html::input("$param->name", $param->isOptional() ? $param->getDefaultValue() : '', "class='form-control' autocomplete='off'")?></td>
        </tr>
        <?php endforeach;?>
        <tr>
          <td align='center' colspan="2">
            <?php echo html::submitButton($lang->api->submit, '', 'btn btn-primary btn-wide')?>
          </td>
        </tr>
      </table>
      <?php else:?>
      <?php echo html::hidden('noparam', '0') . $lang->api->noParam . html::submitButton($lang->api->submit, '', 'btn btn-primary btn-wide');?>
      <?php endif;?>
      <?php if($method->post) echo "<p>{$lang->api->post}</p>"?>
    </form>
  </div>
</div>
<div id="result" class="detail hidden">
  <div class='detail-title'><?php echo $lang->api->url?>:</div>
  <div class='detail-content'>
    <div class="url"></div>
    <h5><?php echo $lang->api->result?>:</h5>
    <p><?php echo $lang->api->status?>: <code class="status"></code></p>
    <p><?php echo $lang->api->data?>: <pre class="data"></pre></p>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
