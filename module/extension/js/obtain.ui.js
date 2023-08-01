function searchExtension()
{
    const url  = $.createLink('extension', 'obtain', "type=bySearch");
    const form = new FormData();
    form.append('key', $('#key').val());

   postAndLoadPage(url, form);
}
