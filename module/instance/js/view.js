$(function()
{
    $('#memBtn').on('click', function(event)
    {
        bootbox.confirm(instanceNotices.adjustMemory, function(result)
        {
            if(!result) return;

            var loadingDialog = bootbox.dialog(
            {
                message: '<div class="text-center"><i class="icon icon-spinner-indicator icon-spin"></i>&nbsp;&nbsp;' + instanceNotices.adjusting + '</div>',
            });

            var id         = $(event.target).closest('button').attr('instance-id');
            var url        = createLink('instance', 'ajaxAdjustMemory', 'id=' + id, 'json');
            var adjustData = {memory_kb: $('select[name=memory_kb]').val()};
            $.post(url, adjustData).done(function(response)
            {
                loadingDialog.modal('hide');

                var res = JSON.parse(response);
                if(res.result == 'success')
                {
                    window.parent.$.apps.open(createLink('instance', 'view', 'id=' + id), 'space');
                }
                else
                {
                    bootbox.alert(
                    {
                        title:   instanceNotices.fail,
                        message: res.message,
                    });
                }
            });

        });
    });

    $('#ldapBtn').on('click', function(event)
    {
        bootbox.confirm(instanceNotices.switchLDAP, function(result)
        {
            if(!result) return;

            var loadingDialog = bootbox.dialog(
            {
                message: '<div class="text-center"><i class="icon icon-spinner-indicator icon-spin"></i>&nbsp;&nbsp;' + instanceNotices.switching + '</div>',
            });

            var id       = $(event.target).closest('button').attr('instance-id');
            var url      = createLink('instance', 'ajaxSwitchLDAP', 'id=' + id, 'json');
            var postData = {enableLDAP: $("[name='enableLDAP[]']:checked").length > 0};
            $.post(url, postData).done(function(response)
            {
                loadingDialog.modal('hide');

                var res = JSON.parse(response);
                if(res.result == 'success')
                {
                    window.parent.location.reload();
                }
                else
                {
                    bootbox.alert(
                    {
                        title:   instanceNotices.fail,
                        message: res.message,
                    });
                }
            });
        });
    });

    $('#smtpBtn').on('click', function(event)
    {
        bootbox.confirm(instanceNotices.switchSMTP, function(result)
        {
            if(!result) return;

            var loadingDialog = bootbox.dialog(
            {
                message: '<div class="text-center"><i class="icon icon-spinner-indicator icon-spin"></i>&nbsp;&nbsp;' + instanceNotices.switching + '</div>',
            });

            var id       = $(event.target).closest('button').attr('instance-id');
            var url      = createLink('instance', 'ajaxSwitchSMTP', 'id=' + id, 'json');
            var postData = {enableSMTP: $("[name='enableSMTP[]']:checked").length > 0};
            $.post(url, postData).done(function(response)
            {
                loadingDialog.modal('hide');

                var res = JSON.parse(response);
                if(res.result == 'success')
                {
                    window.parent.location.reload();
                }
                else
                {
                    bootbox.alert(
                    {
                        title:   instanceNotices.fail,
                        message: res.message,
                    });
                }
            });
        });
    });

    $('#customBtn').on('click', function(event)
    {
        var errors = '';
        $("#customForm").find("input[type=text]").each(function(index, item)
        {
            if($(item).val().trim().length == 0)
            {
                errors += $(item).attr('placeholder') + instanceNotices.required + '<br/>';
            }
        });
        if(errors.length > 0)
        {
            bootbox.alert(
            {
                title:   instanceNotices.error,
                message: errors,
            });
            return;
        }

        bootbox.confirm(instanceNotices.confirmCustom, function(result)
        {
            if(!result) return;

            var loadingDialog = bootbox.dialog(
            {
                message: '<div class="text-center"><i class="icon icon-spinner-indicator icon-spin"></i>&nbsp;&nbsp;' + instanceNotices.setting + '</div>',
            });

            var id         = $(event.target).closest('button').attr('instance-id');
            var url        = createLink('instance', 'ajaxUpdateCustom', 'id=' + id, 'json');
            var formData   = $('#customForm').serializeArray();
            var customData = {};
            formData.forEach(function(item, index){
              customData[item.name] = item.value;
            });

            $.post(url, customData).done(function(response)
            {
                loadingDialog.modal('hide');

                var res = JSON.parse(response);
                if(res.result == 'success')
                {
                    window.parent.location.reload();
                }
                else
                {
                    bootbox.alert(
                    {
                        title:   instanceNotices.fail,
                        message: res.message,
                    });
                }
            });

        });
    });

    $('.btn-uninstall').on('click', function(event)
    {
        bootbox.confirm(instanceNotices.confirmUninstall, function(result)
        {
            if(!result) return;

            var loadingDialog = bootbox.dialog(
            {
                message: '<div class="text-center"><i class="icon icon-spinner-indicator icon-spin"></i>&nbsp;&nbsp;' + instanceNotices.uninstalling + '</div>',
            });

            var id  = $(event.target).closest('button').attr('instance-id');
            var url = createLink('instance', 'ajaxUninstall', 'id=' + id, 'json');
            $.post(url).done(function(response)
            {
                loadingDialog.modal('hide');

                var res = JSON.parse(response);
                if(res.result == 'success')
                {
                    window.parent.$.apps.open(createLink('space', 'browse'), 'space');
                }
                else
                {
                    bootbox.alert(
                    {
                        title:   instanceNotices.fail,
                        message: res.message,
                    });
                }
            });
        });
    });

    $('.btn-start').on('click', function(event)
    {
        bootbox.confirm(instanceNotices.confirmStart, function(result)
        {
            if(!result) return;

            var loadingDialog = bootbox.dialog(
            {
                message: '<div class="text-center"><i class="icon icon-spinner-indicator icon-spin"></i>&nbsp;&nbsp;' + instanceNotices.starting + '</div>',
            });

            var id  = $(event.target).closest('button').attr('instance-id');
            var url = createLink('instance', 'ajaxStart', 'id=' + id, 'json');
            $.post(url).done(function(response)
            {
                loadingDialog.modal('hide');

                var res = JSON.parse(response);
                if(res.result == 'success')
                {
                    window.location.reload();
                }
                else
                {
                    bootbox.alert(
                    {
                        title:   instanceNotices.fail,
                        message: res.message,
                    });
                }
            });
        });
    });

    $('.btn-stop').on('click', function(event)
    {
        bootbox.confirm(instanceNotices.confirmStop, function(result)
        {
            if(!result) return;

            var loadingDialog = bootbox.dialog(
            {
                message: '<div class="text-center"><i class="icon icon-spinner-indicator icon-spin"></i>&nbsp;&nbsp;' + instanceNotices.stopping + '</div>',
            });

            var id  = $(event.target).closest('button').attr('instance-id');
            var url = createLink('instance', 'ajaxStop', 'id=' + id, 'json');
            $.post(url).done(function(response)
            {
                loadingDialog.modal('hide');

                var res = JSON.parse(response);
                if(res.result == 'success')
                {
                    window.location.reload();
                }
                else
                {
                    bootbox.alert(
                    {
                        title:   instanceNotices.fail,
                        message: res.message,
                    });
                }
            });
        });
    });

    $('.btn-backup').on('click', function(event)
    {
        $('#confirmRestore').modal('hide');
        bootbox.confirm(instanceNotices.confirmBackup, function(result)
        {
            if(!result) return;

            var loadingDialog = bootbox.dialog(
            {
                message: '<div class="text-center"><i class="icon icon-spinner-indicator icon-spin"></i>&nbsp;&nbsp;' + instanceNotices.backuping + '</div>',
            });

            var id  = $(event.target).closest('button').attr('instance-id');
            var url = createLink('instance', 'ajaxBackup', 'id=' + id, 'json');
            $.post(url).done(function(response)
            {
                loadingDialog.modal('hide');

                var res = JSON.parse(response);
                if(res.result == 'success')
                {
                    bootbox.alert(
                    {
                        title:   instanceNotices.success,
                        message: res.message,
                        callback: function(){window.location.reload();}
                    });
                }
                else
                {
                    bootbox.alert(
                    {
                        title:   instanceNotices.fail,
                        message: res.message,
                    });
                }
            });
        });
    });

    $('.btn-restore').click(function()
    {
        let backupName = $(this).attr('backup-name');
        let instanceID = $(this).attr('instance-id');
        $('#confirmRestore #submitRestore').data('backup-name', backupName);
        $('#confirmRestore #submitRestore').data('instance-id', instanceID);
        $('#confirmRestore').modal('show');
    });

    $('#submitRestore').on('click', function(event)
    {
        $('#confirmRestore').modal('hide');
        var loadingDialog = bootbox.dialog(
        {
            message: '<div class="text-center"><i class="icon icon-spinner-indicator icon-spin"></i>&nbsp;&nbsp;' + instanceNotices.restoring + '</div>',
        });

        var instanceID = $(event.target).closest('button').data('instance-id');
        var backupName = $(event.target).closest('button').data('backup-name');
        var url        = createLink('instance', 'ajaxRestore', '', 'json');
        $.post(url, { instanceID, backupName }).done(function(response)
        {
            loadingDialog.modal('hide');

            var res = JSON.parse(response);
            if(res.result == 'success')
            {
                bootbox.alert(
                {
                    title:   instanceNotices.success,
                    message: res.message,
                    callback: function(){window.location.reload();}
                });
            }
            else
            {
                bootbox.alert(
                {
                    title:   instanceNotices.fail,
                    message: res.message,
                });
            }
        });
    });

    $('.btn-delete-backup').on('click', function(event)
    {
        bootbox.confirm(instanceNotices.confirmDelete, function(result)
        {
            if(!result) return;

            var loadingDialog = bootbox.dialog(
            {
                message: '<div class="text-center"><i class="icon icon-spinner-indicator icon-spin"></i>&nbsp;&nbsp;' + instanceNotices.deleting + '</div>',
            });

            var id  = $(event.target).closest('button').attr('backup-id');
            var url = createLink('instance', 'ajaxDeleteBackup', 'id=' + id, 'json');
            $.post(url).done(function(response)
            {
                loadingDialog.modal('hide');

                var res = JSON.parse(response);
                if(res.result == 'success')
                {
                    bootbox.alert(
                    {
                        title:   instanceNotices.success,
                        message: res.message,
                        callback: function(){window.location.reload();}
                    });
                }
                else
                {
                    bootbox.alert(
                    {
                        title:   instanceNotices.fail,
                        message: res.message,
                    });
                }
            });
        });
    });

    $('button.db-login').on('click', function(event)
    {
        var dbName     = $(event.target).data('db-name');
        var dbType     = $(event.target).data('db-type');
        var instanceID = $(event.target).data('id');

        $.post(createLink('instance', 'ajaxDBAuthUrl'), {dbName, dbType, instanceID}).done(function(res)
        {
            var response = JSON.parse(res);
            if(response.result == 'success')
            {
                window.parent.open(response.data.url, 'Adminer');
            }
            else
            {
                bootbox.alert(response.message);
            }
        });
    });

    var enableTimer = true;
    window.parent.$(window.parent.document).on('showapp', function(event, app)
    {
        enableTimer = app.code == 'space';
    });

    setInterval(function()
    {
        if(!enableTimer) return;

        var statusURL = createLink('instance', 'ajaxStatus');
        $.post(statusURL, {idList: instanceIdList}).done(function(response)
        {
            var res = JSON.parse(response);
            if(res.result == 'success' && res.data instanceof Array)
            {
                res.data.forEach(function(instance)
                {
                    if($(".instance-status[instance-id=" + instance.id + "]").data('status') != instance.status) window.location.reload();
                });
            }
            if(res.locate) window.parent.location.href = res.locate;
        });
    }, 1000 * 5);

    $('[data-toggle="tooltip"]').tooltip();

    /* Count down for demo instance. */
    setInterval(function()
    {
        var nowSeconds = Math.round((new Date).getTime() / 1000);
        $('.count-down').each(function(index, item)
        {
            var createdAt   = $(item).data('created-at');
            var passSeconds = nowSeconds - createdAt;
            var leftSeconds = (demoAppLife ? demoAppLife : 30) * 60 - passSeconds;

            if(leftSeconds < 0)
            {
                window.parent.location.href = createLink('space', 'browse');
            }
            else
            {
                var minutes = Math.floor(leftSeconds / 60);
                var seconds = Math.round(leftSeconds % 60);
                $(item).find('.left-time').text(('0' + minutes).slice(-2) + ':' + ('0' + seconds).slice(-2));
            }

        })
    }, 1000)

    $('#replicas-edit').on('click', function()
    {
        $('#replicas-input').show()
        $('#replicas-text').hide()
        $('#replicas-save').show()
        $('#replicas-edit').hide()
    })

    $('#replicas-save').on('click', function()
    {
      var loadingDialog = bootbox.dialog(
        {
            message: '<div class="text-center"><i class="icon icon-spinner-indicator icon-spin"></i>&nbsp;&nbsp;' + instanceNotices.adjusting + '</div>',
        });

        var id         = $(event.target).closest('button').attr('instance-id');
        var url        = createLink('instance', 'replicas', 'id=' + id, 'json');
        var adjustData = {replicas: $('#replicas-input').val()};
        $.post(url, adjustData).done(function(response)
        {

        var res = JSON.parse(response);
        if(res.result == 'success')
        {
            window.parent.location.reload();
        }
        else
        {
            loadingDialog.modal('hide');
            bootbox.alert(
            {
                title:   instanceNotices.fail,
                message: res.message,
            });
        }
        });
    })
})
