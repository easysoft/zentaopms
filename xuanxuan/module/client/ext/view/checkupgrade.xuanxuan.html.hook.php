<?php
$this->app->loadLang('im');
$html  = '';
$html .= '<li>' . html::a($this->createLink('admin', 'xuanxuan'), $this->lang->im->common) . '</li>';
$html .= '<li>' . html::a($this->createLink('client', 'browse'), $this->lang->client->browse) . '</li>';
$html .= '<li>' . $this->lang->client->checkUpgrade . '</li>';
?>
<script>
$(function()
{
    $('#footer .breadcrumb').append(<?php echo json_encode($html);?>);
    for(i in serverVersions[0].xxcDownload)
    {
        var url = serverVersions[0].xxcDownload[i].url;
        url = url.replace('xuanxuan.' + serverVersions[0].xxcVersion, 'zentaoclient');
        url = url.replace('xuanxuan', 'zentaoclient');
        serverVersions[0].xxcDownload[i].url = url;
    }
});
</script>
