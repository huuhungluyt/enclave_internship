
$(function(){

    
     $(document).on('click','.btn-submit',function() {
      var courseId = $(this).parent().siblings('td:first-child').text();
       if (!confirm("Do you want to register this course ?")) return

          $.ajax({
            method: "POST",
            url: "index.php?ctr=course&act=registerCourse",
            data: {
              courseId : courseId
            }
          })
          .done(function(data){
            console.log(data);
            var dataObj = JSON.parse(data);
            console.log(dataObj);
            inform(dataObj);
        });
    });

    

    $("#formUpdateInfoTrainee").validate({

        rules: {

            fullName: {
                           required: true,
                           minlength: 3,
                           namevalidation: true
                   },

            address: {
                            required : true,
                            accept: "[a-zA-Z\s]+"
            },

            phoneNumber: {
                          required : true,
                          number: true,
                          minlength: 10,
                          maxlength: 11
            },

            email:{
                          required : true,
                          email : true
            },

            school:{
                          required : true,
                          accept: "[a-zA-Z\s]+"
            },

            faculty:{
                          required: true,
                         accept: "[a-zA-Z\s]+"
            },
            dateOfBirth:{
                          custombirthday:true
            }


        },
        messages: {
            fullName: {
                           required: "Please enter a fullname",
                           minlength: "Your fullname must consist of at least 3 characters"
                   },
           school:{
            number: "Please enter characters !"
           }

           
        },

        submitHandler: function (form) {
            //alert("123");
            if (!confirm("Do you want to update your infomation this course ?")) return
                form.submit();

        }



    });

     $("#formUpdateInfoTrainer").validate({

        rules: {

            fullName: {
                           required: true,
                           minlength: 3,
                           namevalidation: true
                   },

            address: {
                            required : true,
                            accept: "[a-zA-Z\s]+"
            },

            phoneNumber: {
                          required : true,
                          number: true,
                          maxlength: 11,
                          minlength:10
            },

            email:{
                          required : true,
                          email : true
            },

            experience:{
                          required : true,
                          number: true,
                          minlength: 1,
                          maxlength: 2
            },
             dateOfBirth:{
                custombirthday:true
            }

        },
        messages: {
            fullName: {
                           required: "Please enter a fullname",
                           minlength: "Your fullname must consist of at least 3 characters"
                   }

        },

        submitHandler: function (form) {
            //alert("123");
            if (!confirm("Do you want to update your infomation this course ?")) return
                form.submit();
        }



    });

    $.validator.addMethod("namevalidation", function(value, element) {        
        var unicodeWord = XRegExp('^[\\p{L} ]+$');       
     return unicodeWord.test(value);      
       },       
      "Please enter a valid full name"    );


    $.validator.addMethod("custombirthday",
        function(value, element) {
            var status = false;
            var currentYear = new Date().getFullYear();
            var yearOfBirth = new Date(value).getFullYear();
            if(currentYear - yearOfBirth >= 17) status = true;
            return status;
        },
        "Please enter your age is over 17 years old."
        );
    


    $("#changePasswordTrainee").validate({
            rules: {
                currentPass: "required",
                newPassword: "required",
                confirmPassword:{
                    equalTo: "#newPassword"
                }
            },
            messages: {
                currentPass: "Please enter your current password !",
                newPassword: "Please enter your new password !",
                confirmPassword:{
                    equalTo: "This password is not match  !"

                }
            },
            submitHandler: function (form) {
                if (!confirm("Do you want to update your password this course ?")) return
                $.post(
                    "index.php?ctr=trainee&act=postUpdatePassword",
                    {
                        currentPass: $("#currentPass").val(),
                        newPassword: $("#newPassword").val(),
                        confirmPassword: $("#confirmPassword").val()
                    },
                    function (data, status) {
                        //console.log(data);
                        var dataObj = JSON.parse(data);
                        inform(dataObj);
                    }
                );
            }
        });

    $("#changePasswordTrainer").validate({
            rules: {
                currentPass: "required",
                newPassword: "required",
                confirmPassword:{
                    equalTo: "#newPassword"
                }
            },
            messages: {
                currentPass: "Please enter your current password !",
                newPassword: "Please enter your new password !",
                confirmPassword:{
                    equalTo: "This password is not match  !"

                },

            },
            submitHandler: function (form) {
                if (!confirm("Do you want to update your password this course ?")) return
                $.post(
                    "index.php?ctr=trainer&act=postUpdatePassword",
                    {
                        currentPass: $("#currentPass").val(),
                        newPassword: $("#newPassword").val(),
                        confirmPassword: $("#confirmPassword").val()
                    },
                    function (data, status) {
                        //console.log(data);
                        var dataObj = JSON.parse(data);
                        inform(dataObj);
                    }
                );
            }
    });

    $("#formChangeScheduleRequest").validate({
        rules: {
        },
        messages: {
        },
        submitHandler: function (form) {
            var courseId = $('#courseId').val();
            var roomId =$('#roomId').val();
            var time = $('#rangeOfHour').val();
            var lessonId = $('#lessonId').val();
            var date=$("#rangeOfDate").val();
            var oldDate=$("#oldRangeOfDate").val();
            var oldHour=$("#oldRangeOfHour").val();
            var listTime= time.split(" - ");
            var startTime= listTime[0];
            var endTime= listTime[1];
            var content = lessonId+";"+date+";"+startTime+";"+endTime;

            if (!confirm("Do you want to send this request ?")) return
            $.post(
                "index.php?ctr=trainer&act=sendBusyRequest",
                {
                    courseId: courseId,
                    oldDate : oldDate,
                    oldHour : oldHour,
                    roomId : roomId,
                    content: content

                },
                function (data, status) {
                    console.log(data);
                    var dataObj = JSON.parse(data);
                    inform(dataObj);
                }
            );
        }
    });
    $("#formChangeTimeRequest").validate({
        rules: {
        },
        messages: {
        },
        submitHandler: function (form) {
            var courseId = $('#courseId').val();
            var roomId = $('#roomId').val();
            var oldRoomId= $('#oldRoomId').val();
            var time = $('#rangeOfHour').val();
            var oldDay=$('#oldWeekDay').val();
            var newDay = $('#newWeekDay').val();
            var oldHour= oldDay.split(";")[1];
            var content = courseId+";"+roomId+";"+oldDay.split(";")[0]+";"+oldHour.split(" - ")[0]+";"
            +oldHour.split(" - ")[1]+";"+newDay+";"+time.split(" - ")[0]+";"+time.split(" - ")[1];

            if (!confirm("Do you want to send this request ?")) return
            $.post(
                "index.php?ctr=trainer&act=sendChangeDayRequest",
                {
                    rangeOfDate: $('#rangeOfDate').val(),
                    newWeekDay: newDay,
                    oldRoomId: oldRoomId,
                    content: content

                },
                function (data, status) {
                    console.log(data);
                    var dataObj = JSON.parse(data);
                    inform(dataObj);
                }
            );
        }
    });

    $('#formSuggestNewCourse').validate({
        rules: {
            majorName:{
                required:true,
                myName: true,
                maxlength: 32
            },
            majorDescription: {
                required: true,
                description: true
                // noSpace:true;
            }

        },
        messages: {
            majorName: {
                required: "Please enter major's name !",
                // myText: "Invalid value!",
                maxlength: "Less than 32 characters!"
            },
            majorDescription: {
                required: "Please write description !",

            }

        },
        submitHandler: function (form) {
            //alert("123");
            if (!confirm("Do you want to send a suggest new course ?")) return
            var majorId = $('#suggestPopup').find('select[name="major"]').val();
            var majorName = $("#major option:selected").text();
            var name = $('#suggestPopup').find('input[name="majorName"]').val();
            var description = $("textarea#majorDescription").val();
            var selected = $('input:radio[name=choose]:checked').val();

             $.post(
                "index.php?ctr=Notice&act=sendRequest",
                {
                    majorId: majorId,
                    majorName: majorName,
                    name: name,
                    description: description,
                    choose: selected

                },
                function (data, status) {
                    var dataObj = JSON.parse(data);
                    inform(dataObj);
                }
            );
        }




    });

     $('#availableMajor, #newMajor').change(function() {
        $('#major').prop('disabled', true);
         // $("input:radio").prop('disabled', true);
        // $("#major").prop('disabled', true);
      if($('#availableMajor').is(':checked')){
        $("#majorName").prop('disabled', true);
        $("#majorDescription").prop('disabled', true);
        $("#major").prop('disabled', false);
      }else{
        $("#majorName").prop('disabled', false);
        $("#majorDescription").prop('disabled', false);
        $("#major").prop('disabled', true);
      }
    });

    $(document).on('click','.btn-delete-registered-course',function() {
        var id = $(this).parent().siblings('td:first-child').text();
        var majorName = $(this).parent().siblings('td:nth-child(2)').text();
        var trainerName = $(this).parent().siblings('td:nth-child(3)').text();
        $('#delete-registered-course-modal .modal-body').find('input').val(id);
        $('#delete-registered-course-modal .modal-body').find('span.major').text(majorName);
        $('#delete-registered-course-modal .modal-body').find('span.trainer').text(trainerName);
        $('#delete-registered-course-modal').modal('show');
    });
    var noChange = true;
    $('#listOfTrainees .status').change(function(){
        noChange = false;
    })
    $('#update-status-trainee').click(function(e){
        if(noChange){
            alert("Please make any changes before submit");
            e.preventDefault();
            return;
        }
        if (!window.confirm("Do you really want to update?")) { 
            e.preventDefault();   
        }
    });


});


    function addPopUpSuccess(data)
    {
        $('.popup-cart .content p').text(data);
        $('#suggestPopup').modal('hide');
        $('.popup-cart').fadeIn();
        setTimeout(
            function()
            {
           // location.reload();
            //do something special
            $('.popup-cart').fadeOut()

        }, 500);
    }

    function clearForm()
    {
        if (($(".warning-add").length > 0)){
           $(".warning-add").remove();
        }
        //$('#sendChangeScheduleRequestPopup input:not(.btnClose,.btnSend)').val('');
    }


    function fillFormRequest(detail){
        var time=detail.startTime;
        var time2=detail.endTime;
        var date= time.split(" ", 2);
        var date2= time2.split(" ", 2);
        $('#courseId').val(detail.courseId);
        $('#lessonId').val(detail.id);
        $('#roomId').val(detail.roomId);
        $('#rangeOfDate').val(date[0]);
        $('#rangeOfHour').val(date[1]+" - "+date2[1]);
        $('#oldRangeOfDate').val(date[0]);
        $('#oldRangeOfHour').val(date[1]+" - "+date2[1]);
        sendRequest_loadConflictTrainees("#formChangeScheduleRequest")
        sendRequest_loadConflictLessons("#formChangeScheduleRequest")
        sendRequest_loadCourseInfoDymically("#formChangeScheduleRequest");
    }


//, roomId, rangeOfHour, daysOfWeek
    function fillFormChangeTime(courseId){
        //Load course ID
        $("#formChangeTimeRequest").find("input[name='courseId']").val(courseId)

        //Load groups of lesson in this course into selector
        changeTime_loadOldWeekDays("#formChangeTimeRequest")

        //Load room and range of hour of selected group lesson
        setTimeout(
                function() {
                    changeTime_loadRoomForDay("#formChangeTimeRequest")
                },
                100
            );

        //Catch change group of lesson event
        $("#formChangeTimeRequest").find("select[name='oldWeekDay']").change(function () { 
            changeTime_loadRoomForDay("#formChangeTimeRequest");
        });
        changeTimeRequest_loadConflictTrainees("#formChangeTimeRequest")
        changeTimeRequest_loadConflictLessons("#formChangeTimeRequest")
        changeTimeRequest_loadCourseInfoDymically("#formChangeTimeRequest")
    }

    function changeTime_loadOldWeekDays(form) {
        $.post(
            "index.php?ctr=trainer&act=getOldWeekDays",
            {
                changeTimeCourseId: $(form).find("input[name='courseId']").val()
            },
            function (data, status) {
                $(form).find("select[name='oldWeekDay']").html(data)
            }
        ); 
       
    }

    function changeTimeRequest_loadCourseInfoDymically(form){
        $(form).find("select,input").change(function () {

            setTimeout(
                function() {
                        changeTimeRequest_loadConflictTrainees(form),
                        changeTimeRequest_loadConflictLessons(form)
                },
                200
            );
        });
    }
    

    //load room for old schedule of changing-time request
    function changeTime_loadRoomForDay(form){
        var temp= $(form).find("select[name='oldWeekDay']").val();
        $("#rangeOfHour").val(temp.split(";")[1]);

        $.post(
            "index.php?ctr=trainer&act=loadRoomForDay",
            {
                courseId: $(form).find("input[name='courseId']").val(),
                day: $(form).find("select[name='oldWeekDay']").val()
            },
            function (data, status) {
                var ob=JSON.parse(data);
                //console.log(ob);
                $(form).find("select[name='roomId']").val(ob.roomId);
                $(form).find("input[name='oldRoomId']").val(ob.roomId);


            }
        );
    }

    function sendRequest_loadCourseInfoDymically(form){
        $(form).find("select,input").change(function () {

            setTimeout(
                function() {
                        sendRequest_loadConflictTrainees(form),
                        sendRequest_loadConflictLessons(form)
                },
                200
            );
        });
    }
    function sendRequest_loadConflictLessons(form){
        $.post(
            "index.php?ctr=trainer&act=loadConflictLessons",
            {
                
                roomId: $(form).find("input[name='roomId']").val(),
                lessonId: $(form).find("input[name='lessonId']").val(),
                courseId: $(form).find("input[name='courseId']").val(),
                rangeOfDate: $(form).find("input[name='rangeOfDate']").val(),
                rangeOfHour: $(form).find("select[name='rangeOfHour']").val()
            },
            function (data, status) {
                $("#tableCourseConflict > tbody").html(data);
            }
        );
    }

    function sendRequest_loadConflictTrainees(form){
       
        $.post(
            "index.php?ctr=trainer&act=loadConflictTrainees",
            {
                
                courseId: $(form).find("input[name='courseId']").val(),
                lessonId: $(form).find("input[name='lessonId']").val(),
                rangeOfDate: $(form).find("input[name='rangeOfDate']").val(),
                rangeOfHour: $(form).find("select[name='rangeOfHour']").val()
            },
            function (data, status) {
                // alert(data);
                $("#tableTraineeConflict > tbody").html(data);
                // $("#tableConflictLessons > tbody").html(data);
            }
        );
    }


    function showDetailCourse(courseId){
        $.post(
            "index.php?ctr=course&act=getDetailCourse",
            {
                courseId : courseId
            },
            function (data, status) {
                $("#tableDetailCourse > tbody").html(data);
                $("#tableDetailCourse ").dataTable();
            }
        );
    }
    function changeTimeRequest_loadConflictTrainees(form){
       
        $.post(
            "index.php?ctr=trainer&act=getConflictTrainees",
            {
                
                courseId: $(form).find("input[name='courseId']").val(),
                newWeekDay: $(form).find("select[name='newWeekDay']").val(),
                rangeOfDate: $(form).find("input[name='rangeOfDate']").val(),
                rangeOfHour: $(form).find("select[name='rangeOfHour']").val()
            },
            function (data, status) {
               $("#tableTrainee > tbody").html(data);
               
            }
        );
    }
    function changeTimeRequest_loadConflictLessons(form){
        $.post(
            "index.php?ctr=trainer&act=getConflictLessons",
            {
                
                roomId: $(form).find("select[name='roomId']").val(),
                newWeekDay: $(form).find("select[name='newWeekDay']").val(),
                courseId: $(form).find("input[name='courseId']").val(),
                rangeOfDate: $(form).find("input[name='rangeOfDate']").val(),
                rangeOfHour: $(form).find("select[name='rangeOfHour']").val()
            },
            function (data, status) {
                $("#tableCourseConflict > tbody").html(data);
            }
        );
    }
    


