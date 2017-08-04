function detailNewNoti(d) {
    // `d` is the original data object for the row
    if (d.type.localeCompare('requested course') == 0) {
        var content = d.content.split(";");
        return "<div>" +
            "<div class='row'>" +
            "<div class='col-sm-11'>" +
            "<div class='alert alert-warning'>" +
            "<span class='glyphicon glyphicon-envelope'></span> Trainee <strong>" + d.fullName + "</strong> suggested you to create new <strong>" + content[1] + "</strong> course." +
            "</div>" +
            "</div>" +
            "<div class='col-sm-1'>" +
            "<button class='btn btn-success btn-xs pull-right' data-toggle='modal' data-target='#popupAddCourse' onclick='fillAddCourseForm(\"" + d.id + "\",\"" + content[0] + "\")'><i class='fa fa-thumbs-up'></i></button>" +
            "<button style='margin-top:5px;' class='btn btn-danger btn-xs pull-right' onclick='denyNotification(\"" + d.id + "\")'><i class='fa fa-thumbs-down'></i></button>" +
            "</div>" +
            "</div>" +
            "</div>";
    } else if (d.type.localeCompare('requested major') == 0) {
        var content = d.content.split(";");
        return "<div>" +
            "<div class='row'>" +
            "<div class='col-sm-11'>" +
            "<div class='alert alert-warning'>" +
            "<span class='glyphicon glyphicon-envelope'></span> Trainee <strong>" + d.fullName + "</strong> suggested you to create <strong>" + content[0] + "</strong> major.<br>" +
            "<strong>" + content[0] + "</strong> description:<i> " + content[1] + "</i>" +
            "</div>" +
            "</div>" +
            "<div class='col-sm-1'>" +
            "<button class='btn btn-success btn-xs pull-right' data-toggle='modal' data-target='#popupAddMajor' onclick='fillAddMajorForm(\"" + d.id + "\", \"" + content[0] + "\", \"" + content[1] + "\")'><i class='fa fa-thumbs-up'></i></button>" +
            "<button style='margin-top:5px;' class='btn btn-danger btn-xs pull-right' onclick='denyNotification(\"" + d.id + "\")'><i class='fa fa-thumbs-down'></i></button>" +
            "</div>" +
            "</div>" +
            "</div>";
    } else if (d.type.localeCompare('requested schedule') == 0) {
        var content = d.content.split(";");
        return "<div>" +
            "<div class='row'>" +
            "<div class='col-sm-11'>" +
            "<div class='alert alert-warning'>" +
            "<span class='glyphicon glyphicon-envelope'></span> Trainer <b>" + d.fullName + "</b> suggested you to change time of lesson <b>" + content[0] + "</b> to date <b>" + content[1] + "</b> (<b>" + content[2] + "</b> to <b>" + content[3] + "</b>)<br>" +
            "</div>" +
            "</div>" +
            "<div class='col-sm-1'>" +
            "<button class='btn btn-success btn-xs pull-right' data-toggle='modal' data-target='#popupUpdateLesson2' onclick='fillUpdateLessonForm3("+d.id+", "+content[0]+", \""+content[1]+"\", \""+content[2]+" - "+content[3]+"\")'><i class='fa fa-thumbs-up'></i></button>" +
            "<button style='margin-top:5px;' class='btn btn-danger btn-xs pull-right' onclick='denyNotification(\"" + d.id + "\")'><i class='fa fa-thumbs-down'></i></button>" +
            "</div>" +
            "</div>" +
            "</div>";

    } else if (d.type.localeCompare('modified schedule') == 0) {
        var content = d.content.split(";");
        return "<div>" +
            "<div class='row'>" +
            "<div class='col-sm-11'>" +
            "<div class='alert alert-warning'>" +
            "<span class='glyphicon glyphicon-envelope'></span> Trainer <b>" + d.fullName + "</b> suggested you to change the lessons of course (ID:<b>" + content[0] + "</b>) holding on <b>" + content[2] + "</b> (from <b>" + content[3] + "</b> to <b>" + content[4] + "</b>) to <b>" + content[5] + "</b> (from <b>" + content[6] + "</b> to <b>" + content[7] + "</b> at room <b>" + content[1] + "</b>)<br>" +
            "</div>" +
            "</div>" +
            "<div class='col-sm-1'>" +
            "<button class='btn btn-success btn-xs pull-right' data-toggle='modal' data-target='#popupUpdateLesson' onclick='fillUpdateLessonForm2(" + d.id + ", " + content[0] + ", " + d.sender + ", \"" + content[1] + "\", \"" + content[2] + "\", \"" + content[3] + " - " + content[4] + "\", \"" + content[5] + "\", \"" + content[6] + " - " + content[7] + "\")'><i class='fa fa-thumbs-up'></i></button>" +
            "<button style='margin-top:5px;' class='btn btn-danger btn-xs pull-right' onclick='denyNotification(\"" + d.id + "\")'><i class='fa fa-thumbs-down'></i></button>" +
            "</div>" +
            "</div>" +
            "</div>";
    }
}

function detailReadNoti(d) {
    // `d` is the original data object for the row
    var alertStyle;
    if (d.isApproved == 1) {
        alertStyle = 'success';
        isApproved = "ok";
    }
    else {
        alertStyle = 'danger';
        isApproved = "remove";
    }
    if (d.type.localeCompare('requested course') == 0) {
        var content = d.content.split(";");
        return "<div class='alert alert-" + alertStyle + "'>" +
            "<div class= 'row'>" +
            "<div class='col-md-11'>" +
            "<strong><span class='glyphicon glyphicon-" + isApproved + "'></span></strong> Trainee <strong>" + d.fullName + "</strong> suggested you to create new <strong>" + content[1] + "</strong> course." +
            "</div>" +
            "</div>" +
            "</div>";
    } else if (d.type.localeCompare('requested major') == 0) {
        var content = d.content.split(";");
        return "<div class='alert alert-" + alertStyle + "'>" +
            "<div class= 'row'>" +
            "<div class='col-md-11'>" +
            "<strong><span class='glyphicon glyphicon-" + isApproved + "'></span></strong> Trainee <strong>" + d.fullName + "</strong> suggested you to create <strong>" + content[0] + "</strong> major.<br>" +
            "<strong>" + content[0] + "</strong> description:<i> " + content[1] + "</i>" +
            "</div>" +
            "</div>" +
            "</div>";
    } else if (d.type.localeCompare('requested schedule') == 0) {
        var content = d.content.split(";");
        return "<div class='alert alert-" + alertStyle + "'>" +
            "<div class= 'row'>" +
            "<div class='col-md-11'>" +
            "<strong><span class='glyphicon glyphicon-" + isApproved + "'></span></strong> Trainer <b>" + d.fullName + "</b> suggested you change time of lesson <b>" + content[0] + "</b> to date <b>" + content[1] + "</b> from <b>" + content[2] + "</b> to <b>" + content[3] + "</b><br>" +
            "</div>" +
            "</div>" +
            "</div>";
    } else if (d.type.localeCompare('modified schedule') == 0) {
        var content = d.content.split(";");
        return "<div class='alert alert-" + alertStyle + "'>" +
            "<div class= 'row'>" +
            "<div class='col-md-11'>" +
             "<strong><span class='glyphicon glyphicon-" + isApproved + "'></span></strong> Trainer <b>" + d.fullName + "</b> requested you to change the lessons of course (ID:<b>" + content[0] + "</b>) holding on <b>" + content[2] + "</b> (from <b>" + content[3] + "</b> to <b>" + content[4] + "</b>) to <b>" + content[5] + "</b> (from <b>" + content[6] + "</b> to <b>" + content[7] + "</b> at room <b>" + content[1] + "</b>)<br>" +
            "</div>" +
            "</div>" +
            "</div>";
    } 
}


function loadConflictLessons(form) {
    var weekDays = ""
    if ($(form).find("input[name='mon']").is(":checked")) weekDays += "Mon;"
    if ($(form).find("input[name='tue']").is(":checked")) weekDays += "Tue;"
    if ($(form).find("input[name='wed']").is(":checked")) weekDays += "Wed;"
    if ($(form).find("input[name='thu']").is(":checked")) weekDays += "Thu;"
    if ($(form).find("input[name='fri']").is(":checked")) weekDays += "Fri;"
    if ($(form).find("input[name='sat']").is(":checked")) weekDays += "Sat;"
    if (weekDays) weekDays = weekDays.substr(0, weekDays.length - 1);
    else {
        weekDays = null
    }
    $.post(
        "index.php?ctr=course&act=loadConflictLessons",
        {
            // majorId: $(form).find("select[name='majorId']").val(),
            trainerId: $(form).find("select[name='trainerId']").val(),
            roomId: $(form).find("select[name='roomId']").val(),
            rangeOfDate: $(form).find("input[name='rangeOfDate']").val(),
            rangeOfHour: $(form).find("select[name='rangeOfHour']").val(),
            weekDays: weekDays
        },
        function (data, status) {
            if(data){
                $(form).find(".tableConflictLessons").parent().show();
                $(form).find(".tableConflictLessons").find("tbody").html(data);
            }else{
                $(form).find(".tableConflictLessons").parent().hide();
            }
        }
    );
};


//Load lessons of a course by day in week
function loadLessonsByDay(courseId, dayOfWeek, rangeOfHour) {
    $.post(
        "index.php?ctr=course&act=loadLessonsByDay",
        {
            courseId: courseId,
            dayOfWeek: dayOfWeek,
            rangeOfHour: rangeOfHour
        },
        function (data, status) {
            // $(form).find(".tableConflictLessons").find("tbody").html(data);
            $("#listOfLessons > tbody").html(data);
        }
    );
}


//Change trainer
function loadConflictLessons2(form) {
    $.post(
        "index.php?ctr=course&act=loadConflictLessons2",
        {
            courseId: $(form).find("input[name='courseId']").val(),
            trainerId: $(form).find("select[name='trainerId']").val()
        },
        function (data, status) {
            $(form).find(".tableConflictLessons").find("tbody").html(data);
            // $("#tableConflictLessons > tbody").html(data);
        }
    );
}


//When update lesson
function loadConflictLessons3(form) {
    $.post(
        "index.php?ctr=course&act=loadConflictLessons3",
        {
            courseId: $(form).find("input[name='courseId']").val(),
            trainerId: $(form).find("input[name='trainerId']").val(),
            roomId: $(form).find("select[name='roomId']").val(),
            dayOfWeek: $(form).find("select[name='dayOfWeek']").val(),
            oldDayOfWeek: $(form).find("input[name='oldDayOfWeek']").val(),
            rangeOfHour: $(form).find("select[name='rangeOfHour']").val(),
            oldRangeOfHour: $(form).find("input[name='oldRangeOfHour']").val()
        },
        function (data, status) {
            if (data) {
                objData = JSON.parse(data);
                if (objData.conflictLessons) {
                    $(form).find(".tableConflictLessons").parent().show();
                    $(form).find(".tableConflictLessons").find("tbody").html(objData.conflictLessons);
                } else {
                    $(form).find(".tableConflictLessons").parent().hide();
                }
                if (objData.conflictTrainees) {
                    $(form).find(".tableConflictTrainees").parent().show();
                    $(form).find(".tableConflictTrainees").find("tbody").html(objData.conflictTrainees);
                } else {
                    $(form).find(".tableConflictTrainees").parent().hide();
                }
                if (objData.error) {
                    $(form).find(".alert-danger").parent().show();
                    $(form).find(".alert-danger").html(objData.error);
                } else {
                    $(form).find(".alert-danger").parent().hide();
                }
                if (objData.success) {
                    $(form).find(".alert-success").parent().show();
                    $(form).find(".alert-success").html(objData.success);
                } else {
                    $(form).find(".alert-success").parent().hide();
                }
            }else{
                $(form).find(".tableConflictLessons, .tableConflictTrainees, .alert-danger, .alert-success").parent().hide();
            }
            // $("#tableConflictLessons > tbody").html(data);
        }
    );
}

function loadConflictLessons4(form){
    $.post(
        "index.php?ctr=course&act=loadConflictLessons4",
        {
            lessonId: $(form).find("input[name='lessonId']").val(),
            date: $(form).find("input[name='date']").val(),
            rangeOfHour: $(form).find("select[name='rangeOfHour']").val()
        },
        function (data, status) {
            if (data) {
                objData = JSON.parse(data);
                if (objData.conflictLessons) {
                    $(form).find(".tableConflictLessons").parent().show();
                    $(form).find(".tableConflictLessons").find("tbody").html(objData.conflictLessons);
                } else {
                    $(form).find(".tableConflictLessons").parent().hide();
                }
                if (objData.conflictTrainees) {
                    $(form).find(".tableConflictTrainees").parent().show();
                    $(form).find(".tableConflictTrainees").find("tbody").html(objData.conflictTrainees);
                } else {
                    $(form).find(".tableConflictTrainees").parent().hide();
                }
                if (objData.error) {
                    $(form).find(".alert-danger").parent().show();
                    $(form).find(".alert-danger").html(objData.error);
                } else {
                    $(form).find(".alert-danger").parent().hide();
                }
                if (objData.success) {
                    $(form).find(".alert-success").parent().show();
                    $(form).find(".alert-success").html(objData.success);
                } else {
                    $(form).find(".alert-success").parent().hide();
                }
            }else{
                $(form).find(".tableConflictLessons, .tableConflictTrainees, .alert-danger, .alert-success").parent().hide();
            }
            // $("#tableConflictLessons > tbody").html(data);
        }
    );
}

function getCourseById(courseId) {
    $.post(
        "index.php?ctr=course&act=passCourseToJS",
        {
            courseId: courseId
        },
        function (data, status) {
            if (data) return JSON.parse(data);
            return false;
        }
    );
}

function loadTrainerSelector(form) {
    $.post(
        "index.php?ctr=trainer&act=getTrainerSelector",
        {
            majorId: $(form).find("select[name='majorId']").val()
        },
        function (data, status) {
            $(form).find("select[name='trainerId']").html(data)
        }
    );
};

function loadAddCourseFormDymically() {
    loadTrainerSelector("#formAddCourse");
    loadConflictLessons("#formAddCourse")
    $("#formAddCourse").find("select[name='majorId']").change(function () {
        loadTrainerSelector("#formAddCourse");
        setTimeout(
            function () {
                loadConflictLessons("#formAddCourse")
            },
            200
        );
    });
    $("#formAddCourse").find("select, input").change(function () {
        loadConflictLessons("#formAddCourse")
    });
}

function loadUpdateCourseFormDymically() {
    loadConflictLessons2("#formUpdateCourse")
    $("#formUpdateCourse").find("select[name='trainerId']").change(function () {
        loadConflictLessons2("#formUpdateCourse")
    });
}


function loadUpdateLessonFormDymically() {
    loadConflictLessons3("#formUpdateLesson")
    $("#formUpdateLesson").find("select, input").change(function () {
        loadConflictLessons3("#formUpdateLesson")
    });
}



//Fill data into approve course form 
function fillAddCourseForm(notiId, majorId) {
    $("#formAddCourse").find("input[name='notiId']").val(notiId);
    $("#formAddCourse").find("select[name='majorId']").val(majorId);
    $("#formAddCourse").find("select[name='majorId']").prop("disabled", true);
    loadAddCourseFormDymically()
};

//Fill data into update lesson form
function fillUpdateLessonForm(courseId, trainerId, dayOfWeek, roomId, hours) {
    form = "#formUpdateLesson"
    $(form).find("input[name='courseId']").val(courseId);
    $(form).find("input[name='trainerId']").val(trainerId);
    $(form).find("select[name='roomId']").val(roomId);
    $(form).find("select[name='dayOfWeek']").val(dayOfWeek);
    $(form).find("input[name='oldDayOfWeek']").val(dayOfWeek);
    $(form).find("select[name='rangeOfHour']").val(hours);
    $(form).find("input[name='oldRangeOfHour']").val(hours);
    loadUpdateLessonFormDymically();
}

//Fill data into update lesson form when approve "modified schedule" notification
function fillUpdateLessonForm2(notiId, courseId, trainerId, roomId, oldDayOfWeek, oldRangeOfHours, newDayOfWeek, newRangeOfHours) {
    form = "#formUpdateLesson"
    $(form).find("input[name='notiId']").val(notiId)
    $(form).find("input[name='courseId']").val(courseId)
    $(form).find("input[name='trainerId']").val(trainerId)
    $(form).find("select[name='roomId']").val(roomId)
    $(form).find("select[name='dayOfWeek']").val(newDayOfWeek)
    $(form).find("input[name='oldDayOfWeek']").val(oldDayOfWeek)
    $(form).find("select[name='rangeOfHour']").val(newRangeOfHours)
    $(form).find("input[name='oldRangeOfHour']").val(oldRangeOfHours)
    $(form).find("select[name='roomId']").attr("disabled", true)
    $(form).find("select[name='dayOfWeek']").attr("disabled", true)
    $(form).find("select[name='rangeOfHour']").attr("disabled", true)
    loadUpdateLessonFormDymically();
}

//Fill data into update lesson form when approve "requested schedule" notification
function fillUpdateLessonForm3(notiId, lessonId, date, rangeOfHour){
    form="#formUpdateLesson2"
    $(form).find("input[name='notiId']").val(notiId)
    $(form).find("input[name='lessonId']").val(lessonId)
    $(form).find("input[name='date']").val(date)
    $(form).find("select[name='rangeOfHour']").val(rangeOfHour)
$(form).find("input[name='date']").attr("disabled", true)
    $(form).find("select[name='rangeOfHour']").attr("disabled", true)
    loadConflictLessons4(form);
    $(form).find("select, input").change(function () {
        loadConflictLessons4(form);
    });
}


//Fill data into approve major form 
function fillAddMajorForm(notiId, majorName, majorDescription) {
    $("#formAddMajor").find("input[name='notiId']").val(notiId);
    $("#formAddMajor").find("input[name='majorName']").val(majorName);
    $("#formAddMajor").find("textarea[name='majorDescription']").text(majorDescription.replace(/\n/g, "\\n"));
};



function denyNotification(notiId) {
    if (!confirm("Do you want to deny this message?")) return
    $.post(
        "index.php?ctr=notice&act=denyNotification",
        {
            notiId: notiId
        },
        function (data, status) {
            inform(JSON.parse(data));
        }
    );
}




$(document).ready(function () {
    var tableNewNotis = $('#tableNewNotis').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "index.php?ctr=notice&act=loadNewNotifications",
        "columns": [
            {
                "class": "details-control",
                "orderable": false,
                "data": null,
                "defaultContent": ""
            },
            { "data": "type" },
            { "data": "sender" },
            { "data": "createAt" }
        ],
        "order": [[3, 'desc']],
        "info": false
    });

    var tableReadNotis = $('#tableReadNotis').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "index.php?ctr=notice&act=loadReadNotifications",
        "columns": [
            {
                "class": "details-control",
                "orderable": false,
                "data": null,
                "defaultContent": ""
            },
            { "data": "type" },
            { "data": "sender" },
            { "data": "readAt" }
        ],
        "order": [[3, 'desc']],
        "info": false
    });

    // Array to track the ids of the details displayed rows
    // var detailRows = [];

    $('#tableNewNotis tbody').on('click', 'tr td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = tableNewNotis.row(tr);
        // var idx = $.inArray(tr.attr('id'), detailRows);

        if (row.child.isShown()) {
            tr.removeClass('shown');
            row.child.hide();

            // Remove from the 'open' array
            // detailRows.splice(idx, 1);
        }
        else {
            tr.addClass('shown');
            row.child(detailNewNoti(row.data())).show();

            // Add to the 'open' array
            // if (idx === -1) {
            //     detailRows.push(tr.attr('id'));
            // }
        }
    });

    
    $('#tableReadNotis tbody').on('click', 'tr td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = tableReadNotis.row(tr);
        // var idx = $.inArray(tr.attr('id'), detailRows);

        if (row.child.isShown()) {
            tr.removeClass('shown');
            row.child.hide();

            // Remove from the 'open' array
            // detailRows.splice(idx, 1);
        }
        else {
            tr.addClass('shown');
            row.child(detailReadNoti(row.data())).show();


            // Add to the 'open' array
            // if (idx === -1) {
            //     detailRows.push(tr.attr('id'));
            // }
        }
    });

    // On each draw, loop over the `detailRows` array and show any child rows
    // tableNewNotis.on('draw', function () {
    //     alert("OK");
    //     $.each(detailRows, function (i, id) {
    //         $('#' + id + ' td.details-control').trigger('click');
    //     });
    // });
    // tableReadNotis.on('draw', function () {
    //     $.each(detailRows, function (i, id) {
    //         $('#' + id + ' td.details-control').trigger('click');
    //     });
    // });










    //ADD, APPROVE NEW COURSE
    $('#btnPopupAddCourse').click(function () {
        loadAddCourseFormDymically()
    });
    $("#formAddCourse").validate({
        ignore: "",
        rules: {
            majorId: "required",
            trainerId: "required",
            roomId: "required",
            rangeOfDate: "required",
            weekDay: {
                day: true
            },
            rangeOfHour: "required"
        },
        messages: {
            majorId: "Please choose a major!",
            trainerId: "Please choose a trainer!",
            roomId: "Please choose room!",
            rangeOfDate: "Please set range of date!",
            weekDay: {
                day: "Must select at least one!"
            },
            rangeOfHour: "Please set range of hour in a day!"
        },
        submitHandler: function (form) {
            if (!confirm("Do you want to create this course ?")) return;

            var weekDays = ""
            if ($(form).find("input[name='mon']").is(":checked")) weekDays += "Mon;"
            if ($(form).find("input[name='tue']").is(":checked")) weekDays += "Tue;"
            if ($(form).find("input[name='wed']").is(":checked")) weekDays += "Wed;"
            if ($(form).find("input[name='thu']").is(":checked")) weekDays += "Thu;"
            if ($(form).find("input[name='fri']").is(":checked")) weekDays += "Fri;"
            if ($(form).find("input[name='sat']").is(":checked")) weekDays += "Sat;"
            weekDays = weekDays.substr(0, weekDays.length - 1);
            // alert(weekDays.split(";").length+"\n"+weekDays);
            $.post(
                "index.php?ctr=course&act=addCourse",
                {
                    notiId: $(form).find("input[name='notiId']").val(),
                    majorId: $(form).find("select[name='majorId']").val(),
                    trainerId: $(form).find("select[name='trainerId']").val(),
                    roomId: $(form).find("select[name='roomId']").val(),
                    rangeOfDate: $(form).find("input[name='rangeOfDate']").val(),
                    rangeOfHour: $(form).find("select[name='rangeOfHour']").val(),
                    weekDays: weekDays
                },
                function (data, status) {
                    inform(JSON.parse(data))
                }
            );
        }
    });


    //UPDATE LESSON
    $("#formUpdateLesson").validate({
        ignore: "",
        rules: {
        },
        messages: {
        },
        submitHandler: function (form) {
            if (!confirm("Do you want to update these lessons ?")) return;
            $.post(
                "index.php?ctr=course&act=changeLessonByDay",
                {
                    notiId: $(form).find("input[name='notiId']").val(),
                    courseId: $(form).find("input[name='courseId']").val(),
                    trainerId: $(form).find("input[name='trainerId']").val(),
                    roomId: $(form).find("select[name='roomId']").val(),
                    dayOfWeek: $(form).find("select[name='dayOfWeek']").val(),
                    oldDayOfWeek: $(form).find("input[name='oldDayOfWeek']").val(),
                    rangeOfHour: $(form).find("select[name='rangeOfHour']").val(),
                    oldRangeOfHour: $(form).find("input[name='oldRangeOfHour']").val()
                },
                function (data, status) {
                    if (data) {
                        inform(JSON.parse(data));
                    }
                }
            );
        }
    });


    //UPDATE LESSON ONE BY ONE
    $("#formUpdateLesson2").validate({
        ignore: "",
        rules: {
        },
        messages: {
        },
        submitHandler: function (form) {
            if (!confirm("Do you want to update the lesson ?")) return;
            $.post(
                "index.php?ctr=course&act=changeLessonOneByOne",
                {
                    notiId: $(form).find("input[name='notiId']").val(),
                    lessonId: $(form).find("input[name='lessonId']").val(),
                    date: $(form).find("input[name='date']").val(),
                    rangeOfHour: $(form).find("select[name='rangeOfHour']").val()
                },
                function (data, status) {
                    if (data) {
                        inform(JSON.parse(data));
                    }
                }
            );
        }
    });



    //UPDATE COURSE
    loadUpdateCourseFormDymically()
    $("#formUpdateCourse").validate({
        rules: {
        },
        messages: {
        },
        submitHandler: function (form) {
            if (!confirm("Do you want to change trainer for this course ?")) return
            $.post(
                "index.php?ctr=course&act=changeTrainer",
                {
                    courseId: $(form).find("input[name='courseId']").val(),
                    trainerId: $(form).find("select[name='trainerId']").val(),
                },
                function (data, status) {
                    inform(JSON.parse(data))
                }
            );
        }
    });




    //ADD , APPROVE NEW MAJOR
    $("#formAddMajor").validate({
        rules: {
            majorName: {
                required: true,
                myName: true,
                maxlength: 32
            },
            majorDescription: {
                description: true,
                required: true
            }
        },
        messages: {
            majorName: {
                required: "Please enter major's name !",
                maxlength: "Less than 32 characters!"
            },
            majorDescription: {
                required: "Please write description !"

            }
        },
        submitHandler: function (form) {
            if (!confirm("Do you want to create this major ?")) return
            $.post(
                "index.php?ctr=major&act=postAddMajor",
                {
                    notiId: $(form).find("input[name='notiId']").val(),
                    majorName: $(form).find("input[name='majorName']").val(),
                    majorDescription: $(form).find("textarea[name='majorDescription']").val()
                },
                function (data, status) {
                    inform(JSON.parse(data))
                }
            );
        }
    });
});




