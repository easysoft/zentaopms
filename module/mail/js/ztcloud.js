function agreeLicense()
{
    $.cookie('ztCloudLicense', 'yes', {path:config.webRoot});
    self.location.href=self.location.href;
}
