$(document).ready(function () {


	$('#btn-close-form').click(function (){
		if (($(".warning-add").length > 0)){
		   $(".warning-add").remove();
		}
	});

	$('#updatePopup input[name="profilePic"]').change(function (){
		var file = this.files[0];
		var fileType = file["type"];
		var ValidImageTypes = ["image/gif", "image/jpeg", "image/png"];
		if ($.inArray(fileType, ValidImageTypes) < 0) {
			$(this).parent().siblings('.col-sm-8').html('<p class="warning-add">Please choose an image file!</p>');
			return;
		}
		readURL(this);
	});
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {
				$(input).parent().siblings('#profile-picture').html('<img src="' + e.target.result + '" style="height:190px;width: 190px;margin-left: 60px;border:1px solid">');
			}

			reader.readAsDataURL(input.files[0]);
		}
	}
	//custom validation rule
	$.validator.addMethod("customemail",
		function (value, element) {
			var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test(value);
		},
		"Please enter a valid email address. For example johndoe@domain.com."
	);
	$.validator.addMethod("custombirthday",
		function (value, element) {
			var status = false;
			var currentYear = new Date().getFullYear();
			var yearOfBirth = new Date(value).getFullYear();
			if (currentYear - yearOfBirth >= 17) status = true;
			return status;
		},
		"Please enter a valid birthday over 17 years old."
	);
	$.validator.addMethod("namevalidation", function (value, element) {
		var unicodeWord = XRegExp('^[\\p{L} ]+$');

        return unicodeWord.test(value);
    	},
    	"Please enter a valid full name"
    );
    $(document).on('click','.btn-popup-infor',function() {
	    if (($(".error").length > 0)){
		   $("label.error").remove();
		   $("input.error").removeClass('error')
		}
	});

	$("#formUpdateTrainee").validate({
		rules: {
			fullName: {
				required: true,
				namevalidation: true
			},
			address: {
				required: true,
				accept: "[a-zA-Z\s]+",
			},
			phoneNumber: {
				required: true,
				number: true,
				rangelength: [10, 11]
			},
			email: {
				required: true,
				customemail: true
			},
			school: {
				required: true,
				accept: "[a-zA-Z\s]+",

			},
			faculty: {
				required: true,
				accept: "[a-zA-Z\s]+",
			},
			dateOfBirth: {
				custombirthday: true
			}
		},
		messages: {
			school: {
				accept: "Please enter a valid school !"
			},
			faculty: {
				accept: "Please enter a valid faculty !"
			},
			address: {
				accept: "Please enter a valid address !"
			},
		},
		submitHandler: function (form) {
			if (!confirm("Do you want to update this trainee profile infomation ?")) return;
			form.submit();
		}
	});

	$("#formUpdateTrainer").validate({
		rules: {
			fullName: {
				required: true,
				namevalidation: true
			},
			address: {
				required: true,
				accept: "[a-zA-Z\s]+",
			},
			phoneNumber: {
				required: true,
				number: true,
				maxlength: 11,
				minlength: 10
			},
			email: {
				required: true,
				customemail: true
			},
			dateOfBirth: {
				custombirthday: true
			}
		},
		messages: {
			address: {
				accept: "Please enter a valid address !"
			},
		},
		submitHandler: function (form) {
			if (!confirm("Do you want to update this trainer profile infomation ?")) return;
			form.submit();
		}
	});

	$("#formAddTrainee").validate({
		rules: {
			fullName: {
				required: true,
				namevalidation: true
			},
			address: {
				required: true,
				accept: "[a-zA-Z\s]+",
			},
			phoneNumber: {
				required: true,
				number: true,
				rangelength: [10, 11]
			},
			email: {
				required: true,
				customemail: true
			},
			school: {
				required: true,
				accept: "[a-zA-Z\s]+",

			},
			faculty: {
				required: true,
				accept: "[a-zA-Z\s]+",
			},
			dateOfBirth: {
				custombirthday: true
			}
		},
		messages: {
			school: {
				accept: "Please enter a valid school !"
			},
			faculty: {
				accept: "Please enter a valid faculty !"
			},
			address: {
				accept: "Please enter a valid address !"
			},
		},
		submitHandler: function (form) {
			if (!confirm("Do you want to add this trainee profile infomation ?")) return;
			var fullName = $('#addPopup').find('input[name="fullName"]').val();
			var gender = $('#addPopup').find('select[name="gender"]').val();
			var dateOfBirth = $('#addPopup').find('input[name="dateOfBirth"]').val();
			var address = $('#addPopup').find('input[name="address"]').val();
			var phoneNumber = $('#addPopup').find('input[name="phoneNumber"]').val();
			var email = $('#addPopup').find('input[name="email"]').val();
			var school = $('#addPopup').find('input[name="school"]').val();
			var faculty = $('#addPopup').find('input[name="faculty"]').val();
			var typeOfStudent = $('#addPopup').find('select[name="typeOfStudent"]').val();
			$.ajax({
				method: "POST",
				url: "index.php?ctr=trainee&act=postAddTrainee",
				data: {
					fullName: fullName,
					gender: gender,
					dateOfBirth: dateOfBirth,
					address: address,
					phoneNumber: phoneNumber,
					email: email,
					school: school,
					faculty: faculty,
					typeOfStudent: typeOfStudent
				}
			})
				.done(function (data) {
					// console.log(data);
					var dataObj = JSON.parse(data);
					// console.log(dataObj);
					if (typeof dataObj.success !== 'undefined') {
						addPopUpSuccess('You just added successfully a new trainer');
						clearForm();
					}
					if (typeof dataObj.existEmail !== 'undefined') {
						if ($('.warning-add').length > 0) {
							$('.warning-add').remove();
						}
						var html = "<p class='warning-add'>The email existed. Please enter other email.</p>"
						$('#formAddTrainee input[name=email]').parent().append(html);
					}
				});
		}
	});

	$("#formAddTrainer").validate({
		rules: {
			fullName: {
				required: true,
				namevalidation: true
			},
			address: {
				required: true,
				accept: "[a-zA-Z\s]+",
			},
			phoneNumber: {
				required: true,
				number: true,
				maxlength: 11,
				minlength: 10
			},
			email: {
				required: true,
				customemail: true
			},
			dateOfBirth: {
				custombirthday: true
			}
		},
		messages: {
			address: {
				accept: "Please enter a valid address !"
			},
		},
		submitHandler: function (form) {
			if (!confirm("Do you want to update this trainer profile infomation ?")) return;
			var fullName = $('#addPopup').find('input[name="fullName"]').val();
			var gender = $('#addPopup').find('select[name="gender"]').val();
			var dateOfBirth = $('#addPopup').find('input[name="dateOfBirth"]').val();
			var address = $('#addPopup').find('input[name="address"]').val();
			var phoneNumber = $('#addPopup').find('input[name="phoneNumber"]').val();
			var email = $('#addPopup').find('input[name="email"]').val();
			var majorId = parseInt($('#addPopup').find('select[name="majorId"]').val());
			var majorName = $('#addPopup').find('select[name="majorId"] option:selected').text();
			var experience = parseInt($('#addPopup').find('select[name="experience"]').val());
			$.ajax({
				method: "POST",
				url: "index.php?ctr=trainer&act=postAddTrainer",
				data: {
					fullName: fullName,
					gender: gender,
					dateOfBirth: dateOfBirth,
					address: address,
					phoneNumber: phoneNumber,
					email: email,
					majorId: majorId,
					experience: experience
				}
			})
				.done(function (data) {
					// console.log(data);
					var dataObj = JSON.parse(data);
					// console.log(dataObj);
					if (typeof dataObj.success !== 'undefined') {
						addPopUpSuccess('You just added successfully a new trainer');
						clearForm();
					}
					if (typeof dataObj.existEmail !== 'undefined') {
						if ($('.warning-add').length > 0) {
							$('.warning-add').remove();
						}
						var html = "<p class='warning-add'>The email existed. Please enter other email.</p>"
						$('#formAddTrainer input[name=email]').parent().append(html);
					}
				});
		}
	});
	// Delete Account
	// put ID into modal form
	$(document).on('click', '.btn-delete-account', function () {
		var id = $(this).parent().siblings('td:first-child').text();
		$('#delete-account-modal .modal-body').find('input').val(id);
		$('#delete-account-modal .modal-body').find('span').text(id);
		$('#delete-account-modal').modal('show');
	});

	$(document).on('click', '.btn-delete-course', function () {
		var id = $(this).parent().siblings('td:first-child').text();
		$('#delete-course-modal .modal-body').find('input').val(id);
		$('#delete-course-modal .modal-body').find('span').text(id);
		$('#delete-course-modal').modal('show');
	});
	// jQuery.validator.addMethod("alphanumeric", function(value, element) {
	//     return this.optional(element) || /^\w+$/i.test(value);
	// }, );
	$("#formAddRoom").validate({
		rules: {
			addRoomId: {
				required: true,
				room: true
			},
			addRoomCapacity: {
				required: true,
				max: 50
			},
			addRoomQuality: "required"
		},
		messages: {
			addRoomId: {
				required: "Please enter room ID !"
			},
			addRoomCapacity: {
				required: "Please enter room capacity !",
				max: "Capacity less than 50 !"
			},
			addRoomQuality: "Please choose room quality !"
		},
		submitHandler: function (form) {
			if (!confirm("Do you want to add this room ?")) return
			$.post(
				"index.php?ctr=room&act=postAddRoom",
				{
					roomId: $("#addRoomId").val(),
					roomCapacity: $("#addRoomCapacity").val(),
					roomQuality: $("#addRoomQuality").val()
				},
				function (data, status) {
					var dataObj = JSON.parse(data);
					inform(dataObj);
				}
			);
		}
	});

	$("#formUpdateRoom").validate({
		rules: {
			updateRoomId: "required",
			updateRoomCapacity: {
				required: true,
				max: 50
			},

		},
		messages: {
			updateRoomId: "Please enter room ID !",
			updateRoomCapacity: {
				required: "Please enter room capacity !",
				max: "Capacity less than 50 !"
			},

		},
		submitHandler: function (form) {
			if (!confirm("Do you want to update this room ?")) return
			$.post(
				"index.php?ctr=room&act=postUpdateRoom",
				{
					updateRoomId: $("#updateRoomId").val(),
					updateRoomCapacity: $("#updateRoomCapacity").val(),
					updateRoomQuality: $("#updateRoomQuality").val()
				},
				function (data, status) {
					var dataObj = JSON.parse(data);
					inform(dataObj);
				}
			);
		}
	});


	$("#formUpdateMajor").validate({
		rules: {
			updateMajorName: {
				required: true,
				myName: true,
				maxlength: 32
			},
			updateMajorDescription: {
				description: true,
				required: true
			}
		},
		messages: {
			updateMajorName: {
				required: "Please enter major's name !",
				maxlength: "Less than 32 characters!"
			},
			updateMajorDescription: {
				required: "Please write description !"

			}
        },
        submitHandler: function (form) {
            if (!confirm("Do you want to update this major ?")) return
            $.post(
                "index.php?ctr=major&act=postUpdateMajor",
                {
                    majorId: $("#updateMajorId").val(),
                    majorName: $("#updateMajorName").val(),
                    majorDescription: $("#updateMajorDescription").val()
                },
                 function (data, status) {
                    var dataObj = JSON.parse(data);
					inform(dataObj);
				}
			);
		}
	});

	$('.btn-open').click(function () {
		var courseId = getParameterByName('id', window.location.href);
		if (!confirm("Do you want to open this course ?")) return
		//var notify = "opened course";
		$.ajax({
			method: "POST",
			url: "index.php?ctr=course&act=openCourse",
			data: {
				courseId: courseId
				//notify : notify
			}
		})
			.done(function (data) {
				console.log(data);
				var dataObj = JSON.parse(data);
				//console.log(dataObj);
				if (dataObj.success == true) {
					addPopUpSuccess("You open this course succesfully");
					//location.reload();
				} else {
					alert("You can't open this course, IDIOT.");
				}
			});
	});
	$('.btn-close').click(function () {
		var courseId = getParameterByName('id', window.location.href);
		if (!confirm("Do you want to close this course ?")) return
		$.ajax({
			method: "POST",
			url: "index.php?ctr=course&act=closeCourse",
			data: {
				courseId: courseId
			}
		})
			.done(function (data) {
				console.log(data);
				var dataObj = JSON.parse(data);
				//console.log(dataObj);
				if (dataObj.success == true) {
					//location.reload();
					addPopUpSuccess('You closed this course sucessfully!');
				} else if (dataObj.fail == true) {
					alert("Fail to close this course..");
				}
				//else { alert("All trainee status in this course haven't updated by trainer yet");	}
			});
	});
	function getParameterByName(name, url) {
		if (!url) url = window.location.href;
		name = name.replace(/[\[\]]/g, "\\$&");
		var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
			results = regex.exec(url);
		if (!results) return null;
		if (!results[2]) return '';
		return decodeURIComponent(results[2].replace(/\+/g, " "));
	}

	// set default course
	ids=[];
	$('#tableCourseList').on('click', 'input[type="checkbox"]', function() {
		var getRow = $(this).parents('tr');
		var value = (getRow.find('td:eq(0)').html());
		// alert($(this));
		if($(this).prop("checked")){
			if(ids.indexOf(value)<0) ids.push(value)
		}else{
			var i
			if((i=ids.indexOf(value))>-1) ids.splice(i, 1);
		}

	});
	$("#btnSubmitDefaultCourse").click(function(){
		var string=ids.join(" ")
		if (!confirm("Do you want to open and set all this course (IDs: "+string+") to default ?")) return


		    $.ajax({
					method: "POST",
					url: "index.php?ctr=course&act=setDefaultCourse",
					data: {
						id : string
					}
			})
			 .done(function(data){
			 	// console.log(data);
				var dataObj = JSON.parse(data);
				//console.log(dataObj);
				if (dataObj.success == true) {
					addPopUpSuccess("You set checked courses to default succesfully");
				}else if (dataObj.fail == true) {
					alert("This class had trainees registering, so it can not be default course!.");
				}
			});
	});


	defaultIds=[];
	$('#tableDefaultCourse').on('click', 'input[type="checkbox"]', function() {
		var getRow = $(this).parents('tr');
		var value = (getRow.find('td:eq(0)').html());
		// alert($(this));
		if($(this).prop("checked")){
			if(defaultIds.indexOf(value)<0) defaultIds.push(value)
		}else{
			var i
			if((i=defaultIds.indexOf(value))>-1) defaultIds.splice(i, 1);
		}

	});

	$("#btnRemoveDefaultCourse").click(function(){
		var string=defaultIds.join(" ")
		 if (!confirm("Do you want to reset these default courses (IDs: "+string+") to normal course ?")) return

		    $.ajax({
					method: "POST",
					url: "index.php?ctr=course&act=removeDefaultCourse",
					data: {
						id : string
					}
			})
			 .done(function(data){
			 	//console.log(data);
				var dataObj = JSON.parse(data);
				//console.log(dataObj);
				if (dataObj.success == true) {
					addPopUpSuccess("You change courses from default to normal succesfully");
				} else if (dataObj.fail == true) {
					alert("Something wrong here.");
				}
			});
	});

	// clear empty notification
	$('.btn-close-popup').click(function () {
		if ($('.warning-add').length > 0) {
			$('.warning-add').remove();
		}
	});

	// Show detail information of the trainee
	$(document).on("click", ".btnUpdatePopupTrainee", function () {
		var id = $(this).parent().siblings('td:first-child').text();
		var urlOrigin = window.location.origin;
		$.ajax({
			method: "GET",
			url: "index.php?ctr=trainee&act=getTraineeAjax",
			data: {
				id: id
			}
		})
			.done(function (data) {
				// console.log(data);
				var dataObj = JSON.parse(data);
				if (typeof dataObj.success !== 'undefined') {
					$('#updatePopup input[name=id]').val(id);
					$('#updatePopup input[name=fullName]').val(dataObj.success.fullName);
					$('#updatePopup input[name=faculty]').val(dataObj.success.faculty);
					$('#updatePopup select[name=typeOfStudent]').val(dataObj.success.typeOfStudent);
					$('#updatePopup select[name=gender]').val(dataObj.success.gender);
					$('#updatePopup input[name=address]').val(dataObj.success.address);
					$('#updatePopup input[name=school]').val(dataObj.success.school);
					$('#updatePopup input[name=email]').val(dataObj.success.email);
					$('#updatePopup input[name=phoneNumber]').val(dataObj.success.phoneNumber);
					urlImg = dataObj.success.profilePic;
					if (urlImg == 'account.jpg') {
						$('#profile-picture').html('<span style="border:1px solid; padding:5px; font-size: 170px;margin-left: 60px;" class="glyphicon glyphicon-user icon-size"></span>');
					} else {
						$('#profile-picture').html('<img src="' + urlOrigin + '/upload/Trainee/' + urlImg + '" style="height:190px;width: 190px;margin-left: 60px; border:1px solid">');
					}
					$('#updatePopup input[name=dateOfBirth]').val(dataObj.success.dateOfBirth);
					$('#updatePopup').modal('show');
					$('.date1').daterangepicker(
						{
							singleDatePicker: true,
							showDropdowns: true,
							// minDate: new Date(),
							startDate: dataObj.success.dateOfBirth,
							locale: {
								format: 'YYYY-MM-DD'
							}
						}
					);
				} else {

				}
			});
	});

	// Show detail information of the trainee
	$(document).on("click", ".btnUpdatePopupTrainer", function () {
		var id = $(this).parent().siblings('td:first-child').text();
		var urlOrigin = window.location.origin;
		$.ajax({
			method: "GET",
			url: "index.php?ctr=trainer&act=getTrainerAjax",
			data: {
				id: id
			}
		})
			.done(function (data) {
				// console.log(data);
				var dataObj = JSON.parse(data);
				if (typeof dataObj.success !== 'undefined') {

					$('#updatePopup input[name=id]').val(id);
					$('#updatePopup input[name=fullName]').val(dataObj.success.fullName);
					$('#updatePopup select[name=experience]').val(dataObj.success.experience);
					$('#updatePopup select[name=majorId]').val(dataObj.success.majorId);
					$('#updatePopup select[name=gender]').val(dataObj.success.gender);
					$('#updatePopup input[name=address]').val(dataObj.success.address);
					$('#updatePopup input[name=email]').val(dataObj.success.email);
					$('#updatePopup input[name=phoneNumber]').val(dataObj.success.phoneNumber);
					urlImg = dataObj.success.profilePic;
					if (urlImg == 'account.jpg') {
						$('#profile-picture').html('<span style="border:1px solid; padding:5px; font-size: 170px;margin-left: 60px;" class="glyphicon glyphicon-user icon-size"></span>');
					} else {
						$('#profile-picture').html('<img src="' + urlOrigin + '/upload/Trainer/' + urlImg + '" style="height:190px;width: 190px;margin-left: 60px;border:1px solid">');
					}
					$('#updatePopup input[name=dateOfBirth]').val(dataObj.success.dateOfBirth);
					$('#updatePopup').modal('show');
					$('.date1').daterangepicker(
						{
							singleDatePicker: true,
							showDropdowns: true,
							// minDate: new Date(),
							startDate: dataObj.success.dateOfBirth,
							locale: {
								format: 'YYYY-MM-DD'
							}
						}
					);
				} else {

				}
			});
	});


	$("#formReopenCourse").validate({
        ignore: "",
        rules: {
            trainerId: "required",
            roomId: "required",
            rangeOfDate: "required",
            weekDay:{
                day: true
            },
            rangeOfHour: "required"
        },
        messages: {
            majorId: "Please choose a major!",
            trainerId: "Please choose a trainer!",
            roomId: "Please choose room!",
            rangeOfDate: "Please set range of date!",
            weekDay:{
                day: "Must select at least one!"
            },
            rangeOfHour: "Please set range of hour in a day!"
        },
        submitHandler: function (form) {
            if (!confirm("Do you want to reopen this course ?")) return;

            var weekDays="";
            if($(form).find("input[name='monday']").is(":checked")) weekDays+="Mon;"
            if($(form).find("input[name='tuesday']").is(":checked")) weekDays+="Tue;"
            if($(form).find("input[name='wednesday']").is(":checked")) weekDays+="Wed;"
            if($(form).find("input[name='thursday']").is(":checked")) weekDays+="Thu;"
            if($(form).find("input[name='friday']").is(":checked")) weekDays+="Fri;"
            if($(form).find("input[name='saturday']").is(":checked")) weekDays+="Sat;"
            weekDays= weekDays.substr(0, weekDays.length-1);
                // alert(weekDays.split(";").length+"\n"+weekDays);

            $.post(
                "index.php?ctr=course&act=reopenCourse",
                {
                	courseId: $("#reopenCourseId").val(),
                    majorId: $("#reopenMajorId").val(),
                    trainerId: $("#reopenTrainerId").val(),
                    roomId: $("#reopenRoomId").val(),
                    rangeOfDate: $("#rangeOfDate").val(),
                    rangeOfHour: $("#rangeOfHour").val(),
                    weekDays: weekDays
                },
                function (data, status) {
                	// alert(data);
                	console.log(data);
                	var dataObj = JSON.parse(data)
                    inform(dataObj)
                }
            );
        }
    });


	// Display sucessful notification
	function addPopUpSuccess(data) {
		$('.popup-cart .content p').text(data);
		$('#addPopup, #updatePopup ,#addMajorPopup,#updateMajorPopup, #loadCoursePopup, #loadDefaultCoursePopup').modal('hide');
		$('.popup-cart').fadeIn();
		setTimeout(
			function () {
				location.reload();
				//do something special
				$('.popup-cart').fadeOut()
			}, 2000);
	}
	// show error notification into form add trainer information
	function showErrorEmpty(dataObj, parent = '#addPopup') {
		if (($(".warning-add").length > 0)) {
			$(".warning-add").remove();
		}
		for (var name in dataObj.error) {
			var selector = parent + ' input[name="' + name + '"]';
			$(selector).parent().append('<p class="warning-add">This field is empty</p>');
		}
	}

	function showErrorEmptyForUpdate(dataObj) {
		if (($(".warning-add").length > 0)) {
			$(".warning-add").remove();
		}
		for (var name in dataObj.error) {
			var selector = '#updatePopup input[name="' + name + '"]';

			$(selector).parent().append('<p class="warning-add">This field is empty</p>');
		}
	}
	// remove error notification and empty field input after added a new trainer successfully
	function clearForm() {
		if (($(".warning-add").length > 0)) {
			$(".warning-add").remove();
		}
		$('#addPopup input:not(.btnClose,.btnAdd)').val('');
	}
	//
	function noscript(strCode) {
		var html = $(strCode.bold());
		html.find('script').remove();
		return html.html();
	}

	/*Validate form function*/
	function isNumber(data, selector) {
		var reg = /^\d+$/;
		var status = true;
		if (data == "") {
			$(selector).parent().append('<p class="warning-add">This field is empty.</p>');
			status = false;
			return status;
		}
		if (data.length > 11) {
			$(selector).parent().append('<p class="warning-add">The length number is too long.</p>');
			status = false;
			return status;
		}
		if (!reg.test(data)) {
			$(selector).parent().append('<p class="warning-add">Please enter a valid number.</p>');
			status = false;
		}
		return status;
	}
	function isNotEmpty(data, selector) {
		var status = true;
		if (data == "") {
			$(selector).parent().append('<p class="warning-add">This field is empty.</p>');
			status = false;
		}
		return status;
	}
	function isEmail(data, selector) {
		var status = true;
		var reg = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		if (data == "") {
			$(selector).parent().append('<p class="warning-add">This field is empty.</p>');
			status = false;
			return status;
		}
		if (!reg.test(data)) {
			$(selector).parent().append('<p class="warning-add">Please enter a valid email address.</p>');
			status = false;
		}
		return status;
	}
	function existWaring() {
		if (($(".warning-add").length > 0)) {
			$(".warning-add").remove();
		}
	}
});


function deleteRoom(roomId) {
	if (!confirm("Do you want to delete " + roomId + " ?")) return;
	$.post(
		"index.php?ctr=room&act=postDeleteRoom",
		{
			id: roomId
		},
		function (data, status) {
			console.log(data);
			var dataObj = JSON.parse(data);
			inform(dataObj);
		}
	);
}

function setDefaultCourse(courseId) {
	if (!confirm("Do you want to set default this course?")) return;
	$.post(
		"index.php?ctr=course&act=postDefaultCourse",
		{
			id: courseId
		},
		function (data, status) {
			console.log(data);
			var dataObj = JSON.parse(data);
			inform(dataObj);
		}
	);
}

function deleteMajor(majorId, majorName) {
	if (!confirm("Do you want to delete " + majorName + " ?")) return;
	$.post(
		"index.php?ctr=major&act=postDeleteMajor",
		{
			id: majorId
		},
		function (data, status) {
			console.log(data);
			var dataObj = JSON.parse(data);
			inform(dataObj);
		}
	);
}

function loadTrainerList(majorId) {
	$.post(
		"index.php?ctr=trainer&act=showTrainerList",
		{
			majorId: majorId
		},
		function (data, status) {
			$("#tableTrainerList > tbody").html(data);
			$("#tableTrainerList ").dataTable();
		}
	);
}



function fillFormUpdateRoom(id, capacity, quality) {
	$('#updateRoomId').val(id);
	$('#updateRoomCapacity').val(capacity);
	$('#updateRoomQuality').val(quality);
}

function fillFormUpdateMajor(id, name, description) {
	$('#updateMajorId').val(id);
	$('#updateMajorName').val(name);
	$('#updateMajorDescription').val(description);
}

function reopen_loadTrainerSelector(form) {
    $.post(
        "index.php?ctr=trainer&act=getTrainerSelector",
        {
            majorId: $(form).find("input[name='reopenMajorId']").val()
        },
        function (data, status) {
            $(form).find("select[name='reopenTrainerId']").html(data)
        }
    );
}


function loadDetailCourse(detailOB) {
	$('#mon, #tue, #wed, #thu, #fri, #sat').prop('checked', false);
	$("#reopenCourseId").val(detailOB.id);
	$("#reopenMajorId").val(detailOB.majorId);
	$('#reopenMajor').val(detailOB.majorName);
	reopen_loadTrainerSelector("#formReopenCourse");
	$('#reopenTrainerId').val(detailOB.trainerId);
	reopen_loadCourseInfoDymically("#formReopenCourse");

}
function reopen_loadConflictLessons(form) {
	var weekDays = ""
	if ($(form).find("input[name='monday']").is(":checked")) weekDays += "Mon;"
	if ($(form).find("input[name='tuesday']").is(":checked")) weekDays += "Tue;"
	if ($(form).find("input[name='wednesday']").is(":checked")) weekDays += "Wed;"
	if ($(form).find("input[name='thursday']").is(":checked")) weekDays += "Thu;"
	if ($(form).find("input[name='friday']").is(":checked")) weekDays += "Fri;"
	if ($(form).find("input[name='saturday']").is(":checked")) weekDays += "Sat;"
	if (weekDays) weekDays = weekDays.substr(0, weekDays.length - 1);
	else {
		weekDays = null
	}
	$.post(
		"index.php?ctr=course&act=loadConflictLessons",
		{
			// majorId: $(form).find("select[name='reopenMajorId']").val(),
			trainerId: $(form).find("select[name='reopenTrainerId']").val(),
			roomId: $(form).find("select[name='reopenRoomId']").val(),
			rangeOfDate: $(form).find("input[name='rangeOfDate']").val(),
			rangeOfHour: $(form).find("select[name='rangeOfHour']").val(),
			weekDays: weekDays
		},
		function (data, status) {
			// alert(data);
			$("#tableCourseConflict > tbody").html(data);
			// $("#tableConflictLessons > tbody").html(data);
		}
	);
}


function reopen_loadConflictTrainees(form){
    var weekDays= ""
    if($(form).find("input[name='monday']").is(":checked")) weekDays+="Mon;"
    if($(form).find("input[name='tuesday']").is(":checked")) weekDays+="Tue;"
    if($(form).find("input[name='wednesday']").is(":checked")) weekDays+="Wed;"
    if($(form).find("input[name='thursday']").is(":checked")) weekDays+="Thu;"
    if($(form).find("input[name='friday']").is(":checked")) weekDays+="Fri;"
    if($(form).find("input[name='saturday']").is(":checked")) weekDays+="Sat;"
    if(weekDays) weekDays= weekDays.substr(0, weekDays.length-1);
    else{
        weekDays= null
    }
    $.post(
        "index.php?ctr=course&act=loadConflictTrainees",
        {

            courseId: $(form).find("input[name='reopenCourseId']").val(),
            rangeOfDate: $(form).find("input[name='rangeOfDate']").val(),
            rangeOfHour: $(form).find("select[name='rangeOfHour']").val(),
            weekDays: weekDays
        },
        function (data, status) {
        	// alert(data);
            $("#tableTrainee > tbody").html(data);
            // $("#tableConflictLessons > tbody").html(data);
        }
    );
}

function reopen_loadCourseInfoDymically(form) {
	$(form).find("select, input").change(function () {

		setTimeout(
			function () {
				reopen_loadConflictTrainees(form),
					reopen_loadConflictLessons(form)
			},
			200
		);
	});
}
