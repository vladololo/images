function Progress(value) {
    $(".progress-bar").text("Завершено " + value + "%");
    $(".progress-bar").css("width", value + "%");
}

// Отображаем ошибочные сообщения
function Error(obj) {
    $(obj).removeClass('success').addClass('error');
}

// Отображаем успешные сообщения
function Success(obj) {
    $(obj).removeClass('error').addClass('success');
}

// Получаем ответ сервера
function EventResponse(response) {
    $("#errors").empty();
    if (response['success'] !== undefined) {
        $("#message").addClass("mess-success").text(response['success']);

        var interval = 3000;
        if ($("#send").hasClass('add')) {
            setTimeout(function () {
                resetForm()
            }, interval);
        } else {
            setTimeout(function () {
                resetUpdate();
            }, interval);
        }
    }
    else if (response['error'] !== undefined) {
        Progress(0);
        $("#message").addClass("mess-error").text(response['error']);
    } else {
        //file.rejectDimensions();
        $.each(response, function (key, value) {
            Progress(0);
            $("#errors").append("<li>" + value + "</li>");
        });
    }
}

// Устанавливаем начальное отображение данных
function resetForm() {
    Progress(0);
    $("#Name").val("").removeClass('success');
    $("#Description").val("").removeClass('success');
    $("#Images").removeClass('success');

    dropzone_Images.removeAllFiles();
    $("#errors").empty();
    $("#message").empty();
}

// Ставим отображение после обноваления данных
function resetUpdate() {
    Progress(0);
    $("#message").empty();
}

function InitAccept() {
    dropzone_Images.options.accept = function (file, done) {
        file.acceptDimensions = done;
        file.rejectDimensions = function () {
            done("Картинка не добавлена");
        };
    };
}

function getSortImages() {
    var arr = [];
    $("#Images .dz-preview").each(function () {
        if ($(this).hasClass('dz-complete')) {
            arr.push({
                alt: $(this).find(".dz-image > img").attr("alt"),
                src: $(this).find(".dz-image > img").attr("src")
            });
        } else {
            arr.push({alt: $(this).find(".dz-image > img").attr("alt"), src: ""});
        }
    });
    return JSON.stringify(arr);
}

function appendToFormData(formData) {
    formData.append("DocumentForm[Name]", $("#Name").val());
    formData.append("DocumentForm[Description]", $("#Description").val());
    formData.append("Sort", getSortImages());
    formData.append("_csrf", $("[name=_csrf]").val());

    return formData;
}

$(document).ready(function () {

    $("#send").click(function () {
        var Name = $("#Name");
        var Description = $("#Description");
        var Images = $("#Images");

        if ($(Name).val()) {
            Success(Name);
            if (Description.val()) {
                Success(Description);

                var fd = new FormData();
                fd = appendToFormData(fd);
                $.each(dropzone_Images.files, function (key, value) {
                    fd.append('DocumentForm[file][' + key + ']', value);
                });

                $.ajax({
                    url: $("#url").val(),
                    type: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,
                    cache: false,
                    dataType: 'json',
                    xhr: function () {
                        var xhr = new window.XMLHttpRequest();
                        //Download progress
                        xhr.addEventListener("progress", function (evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                Progress(Math.round(percentComplete * 100));
                            }
                        }, false);
                        return xhr;
                    },
                    success: function (response) {
                        EventResponse(response);
                    },
                });
            } else Error(Description);
        } else Error(Name);
    });
});

