function agreeLicense()
{
    $.cookie('ztCloudLicense', 'yes', {expires:config.cookieLife, path:config.webRoot});
    self.location.href=self.location.href;
}
