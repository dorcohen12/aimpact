jQuery(document).ready(function($){
    App.AjaxSetup = () => {
        $.ajaxSetup({
            type: "post",
            url: App.base_url + "ajax_helper.php",
            headers: {
                token: $('meta[name="token"]').attr("content"),
                is_ajax: 1,
            },
            dataType: "json",
            error: function (jqXHR, exception) {
                if (jqXHR.status == 404) {
                    alert("Requested page not found. [404]");
                } else if (jqXHR.status == 500) {
                    alert("Internal Server Error [500]");
                } else if (jqXHR.status == "429") {
                    alert("Refresh page and try again! [429]");
                } else if (exception === "parsererror") {
                    alert(jqXHR.responseText);
                } else if (exception === "timeout") {
                    alert("Time out error");
                } else if (exception === "abort") {
                    alert("Ajax request aborted.");
                }
            }
        })
    }

    App.notify = (type, text) => {
        $("#notify").removeClass("info");
        $("#notify").removeClass("warn");
        if (type === "error") {
            $("#notify").addClass("warn");
        } else if (type === "info") {
            $("#notify").addClass("info");
        }
        $("#notify").text(text).fadeIn();
        setTimeout(() => {
            $("#notify").fadeOut();
        }, 3000);
    }

    App.showLoading = () => {
        $('.loader').addClass('active');
    }

    App.hideLoading = () => {
        $('.loader').removeClass('active');
    }

    App.createUser = async (formId, postFields) => {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: 'POST',
                data: postFields,
                beforeSend: function () {
                    $(`#${formId} .btn[type="submit"]`).attr('disabled', true);
                    $(`#${formId} .btn[type="submit"]`).css({'cursor': 'not-allowed'});
                    App.showLoading();
                },
                success: function (res) {
                    $(`#${formId} .btn[type="submit"]`).attr('disabled', false);
                    $(`#${formId} .btn[type="submit"]`).css({'cursor': 'pointer'});
                    App.hideLoading();
                    resolve(res);
                },
                error: function (jqXHR, exception) {
                    $(`#${formId} .btn[type="submit"]`).attr('disabled', false);
                    $(`#${formId} .btn[type="submit"]`).css({'cursor': 'pointer'});
                    App.hideLoading();
                    if (jqXHR.status == 404) {
                        alert("Requested page not found. [404]");
                    } else if (jqXHR.status == 500) {
                        alert("Internal Server Error [500]");
                    } else if (jqXHR.status == "429") {
                        alert("Refresh page and try again! [429]");
                    } else if (exception === "parsererror") {
                        alert(jqXHR.responseText);
                    } else if (exception === "timeout") {
                        alert("Time out error");
                    } else if (exception === "abort") {
                        alert("Ajax request aborted.");
                    }
                    reject(jqXHR);
                }
            });
        });
    };

    App.editUser = async (postFields) => {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: 'POST',
                data: postFields,
                beforeSend: function () {
                    App.showLoading();
                },
                success: function (res) {
                    App.hideLoading();
                    resolve(res);
                },
                error: function (jqXHR, exception) {
                    App.hideLoading();
                    if (jqXHR.status == 404) {
                        alert("Requested page not found. [404]");
                    } else if (jqXHR.status == 500) {
                        alert("Internal Server Error [500]");
                    } else if (jqXHR.status == "429") {
                        alert("Refresh page and try again! [429]");
                    } else if (exception === "parsererror") {
                        alert(jqXHR.responseText);
                    } else if (exception === "timeout") {
                        alert("Time out error");
                    } else if (exception === "abort") {
                        alert("Ajax request aborted.");
                    }
                    reject(jqXHR);
                }
            });
        });
    };

    App.deleteUser = async (postFields) => {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: 'POST',
                data: postFields,
                beforeSend: function () {
                    $(`.deleteUser`).attr('disabled', true);
                    $(`.deleteUser`).css({'cursor': 'not-allowed'});
                    App.showLoading();
                },
                success: function (res) {
                    $(`.deleteUser`).attr('disabled', false);
                    $(`.deleteUser`).css({'cursor': 'pointer'});
                    App.hideLoading();
                    resolve(res);
                },
                error: function (jqXHR, exception) {
                    $(`.deleteUser`).attr('disabled', false);
                    $(`.deleteUser`).css({'cursor': 'pointer'});
                    App.hideLoading();
                    if (jqXHR.status == 404) {
                        alert("Requested page not found. [404]");
                    } else if (jqXHR.status == 500) {
                        alert("Internal Server Error [500]");
                    } else if (jqXHR.status == "429") {
                        alert("Refresh page and try again! [429]");
                    } else if (exception === "parsererror") {
                        alert(jqXHR.responseText);
                    } else if (exception === "timeout") {
                        alert("Time out error");
                    } else if (exception === "abort") {
                        alert("Ajax request aborted.");
                    }
                    reject(jqXHR);
                }
            });
        });
    }
    App.syncUser = async (postFields) => {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: 'POST',
                data: postFields,
                beforeSend: function () {
                    App.showLoading();
                },
                success: function (res) {
                    App.hideLoading();
                    resolve(res);
                },
                error: function (jqXHR, exception) {
                    App.hideLoading();
                    if (jqXHR.status == 404) {
                        alert("Requested page not found. [404]");
                    } else if (jqXHR.status == 500) {
                        alert("Internal Server Error [500]");
                    } else if (jqXHR.status == "429") {
                        alert("Refresh page and try again! [429]");
                    } else if (exception === "parsererror") {
                        alert(jqXHR.responseText);
                    } else if (exception === "timeout") {
                        alert("Time out error");
                    } else if (exception === "abort") {
                        alert("Ajax request aborted.");
                    }
                    reject(jqXHR);
                }
            });
        });
    }
    

    App.Initialize = () => {
        App.AjaxSetup();
        $('[data-toggle="tooltip"]').tooltip()
        $(document).on('submit', '#createUser', async function (e) {
            var parent = $(this);
            e.preventDefault();
            const postFields = $(this).serialize();
            if (!postFields || postFields.includes('undefined')) {
                return App.notify('error', 'אנא מלא את הטופס!');
            }
            try {
                const response = await App.createUser((parent.attr('id')), postFields);
                console.log(response);
                if (response.error) {
                    App.notify('error', response.error);
                    return;
                }
                $(parent)[0].reset();
                App.notify('success', response.success);
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } catch (error) {
                console.error('Error occurred:', error);
                App.notify('error', 'לא ניתן לשלוח בקשה');
            }
        });
        $(document).on('click', '.deleteUser', async function(e) {
            e.preventDefault();
            if(confirm('האם אתה בטוח שברצונך למחוק משתמש זה? פעולה זו בלתי הפיכה!')) {
                var user = $(this).data('user');
                if(!user) {
                    App.notify('error', 'מזהה משתמש לא תקין!');
                    return;
                }
                try {
                    const response = await App.deleteUser({action: 'users', sub_action: 'delete_user', user_id: user});
                    console.log(response);
                    if (response.error) {
                        App.notify('error', response.error);
                        return;
                    }
                    $(`#user-${user}`).fadeOut('fast', () => {
                        $(`#user-${user}`).remove();
                    })
                    App.notify('success', response.success);
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } catch (error) {
                    console.error('Error occurred:', error);
                    App.notify('error', 'לא ניתן לשלוח בקשה');
                }
            }
            return;
        });
        $(document).on('click', '.syncUser', async function(e) {
            e.preventDefault();
            var user = $(this).data('user');
            if(!user) {
                App.notify('error', 'מזהה משתמש לא תקין!');
                return;
            }
            try {
                const response = await App.syncUser({action: 'users', sub_action: 'sync_user', user_id: user});
                console.log(response);
                if (response.error) {
                    App.notify('error', response.error);
                    return;
                }
                App.notify('success', response.success);
            } catch (error) {
                console.error('Error occurred:', error);
                App.notify('error', 'לא ניתן לשלוח בקשה');
            }
        });
        $(document).on('click', '.editUser', async function(e) {
            e.preventDefault();
            var user = $(this).data('user');
            if(!user) {
                App.notify('error', 'מזהה משתמש לא תקין!');
                return;
            }
            var name = $(`#user-${user} td:nth-child(2)`).text();
            var last_name = $(`#user-${user} td:nth-child(3)`).text();
            var phone_number = $(`#user-${user} td:nth-child(4)`).text().replace(/^972/, '0');
            $('#editUserModal #editUserModalLabel').text(`עריכת משתמש - ${name}`);
            $('#editUserModal input[name="user_id"]').val(user);
            $('#editUserModal input[name="first_name"]').val(name);
            $('#editUserModal input[name="last_name"]').val(last_name);
            $('#editUserModal input[name="phone"]').val(phone_number);
            $('#editUserModal').modal('show');
        });
        $(document).on('submit', '#editUser', async function (e) {
            var parent = $(this);
            e.preventDefault();
            const postFields = $(this).serialize();
            if (!postFields || postFields.includes('undefined')) {
                return App.notify('error', 'אנא מלא את הטופס!');
            }
            try {
                const response = await App.editUser(postFields);
                console.log(response);
                if (response.error) {
                    App.notify('error', response.error);
                    return;
                }
                $(parent)[0].reset();
                App.notify('success', response.success);
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } catch (error) {
                console.error('Error occurred:', error);
                App.notify('error', 'לא ניתן לשלוח בקשה');
            }
        });
    }

    App.Initialize();

});
