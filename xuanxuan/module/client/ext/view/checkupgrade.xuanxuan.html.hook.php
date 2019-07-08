<?php
$this->app->loadLang('chat');
$html  = '';
$html .= '<li>' . html::a($this->createLink('admin', 'xuanxuan'), $this->lang->chat->common) . '</li>';
$html .= '<li>' . html::a($this->createLink('client', 'browse'), $this->lang->client->browse) . '</li>';
$html .= '<li>' . $this->lang->client->checkUpgrade . '</li>';
?>
<script>
$(function()
{
    $('#footer .breadcrumb').append(<?php echo json_encode($html);?>);
})
</script>
