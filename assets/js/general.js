


$(document).ready(function () {


    jQuery.validator.addMethod("accept", function(value, element, param) {
        value= value.trim();
        if(!value) return false;
        return value.match(new RegExp(param));
    });

    $.validator.addMethod("day", function(value, elem, param) {
        if($(".week-day:checkbox:checked").length > 0){
            return true;
        }else {
            return false;
        }
    },"You must select at least one!");

     $.validator.addMethod("description", function(value, element, param) {
        return (/^[^;]+$/.test(value))
    }, "Invalid description!");

    $.validator.addMethod("myName", function(value, element, param) {
        return (/^\.{0,1}[\w\s]+#{0,1}(\+{2})*$/.test(value))
    }, "Invalid name!");

    $.validator.addMethod("room", function(value, element, param) {
        return (/^[\d]{0,1}[1-9]{1}[\d]{2}$/.test(value))
    }, "Invalid room ID!");


     // update num of new notifications
     function updateNumOfNotis() {
         $.post(
             "index.php?ctr=notice&act=getNumOfNewNotis",
             function(data) {
                 if(!$.isNumeric(data)){
                     window.location.replace(window.location.origin + "/index.php?ctr=auth&act=getLogin");
                 }else{
                     if(data>0){
                         $("#numOfNotis").show();
                         $("#numOfNotis").html(data);
                     }else{
                         $("#numOfNotis").hide();
                     }
                 }
             }
         );
         setTimeout(
	 		function() {
	 				updateNumOfNotis();
	 		},
	 		500
	 	);
     }
     updateNumOfNotis();



    //HUNTER
    //change tab click effect
    function clickEffect(){
        var mainURL= [
                    "index.php?ctr=home&act=index",
                    "index.php?ctr=schedule&act=getSchedule",
                    "index.php?ctr=course&act=getAvailableCourse",
                    "index.php?ctr=trainee&act=getInforById",
                    "index.php?ctr=trainer&act=getInforById",
                    "index.php?ctr=course&act=postSchedule",
                    "index.php?ctr=trainee&act=getList",
                    "index.php?ctr=trainer&act=getList",
                    "index.php?ctr=room&act=getRoom",
                    "index.php?ctr=major&act=getMajor",
                    "index.php?ctr=notice&act=newNotis",
                    "index.php?ctr=course&act=getAvailableCourse",
                    "index.php?ctr=course&act=getRegisteredCourse"
                ]
        var fullUrl= window.location.href
        var fullPrevUrl= document.referrer
        var subUrl = fullUrl.split("/")
        var subPrevUrl = fullPrevUrl.split("/")
        var url= subUrl[subUrl.length-1]
        var prevUrl= subPrevUrl[subPrevUrl.length-1]

        if(mainURL.indexOf(url)<0&&mainURL.indexOf(prevUrl)<0) return
        if(mainURL.indexOf(url)<0&&mainURL.indexOf(prevUrl)>-1) fullUrl= fullPrevUrl
        // $('ul.nav a[href="' + fullUrl + '"]').parent().addClass('active')
        var li= $('ul.nav a').filter(function () {
            return this.href == fullUrl
        }).parent();
        var parent_li= li.parent().closest('li');
        if(parent_li.length>0){
            parent_li.addClass('active');
        }else{
            li.addClass('active');
        }
    }
    clickEffect();

});

$('.dateTime').daterangepicker(
    {
        singleDatePicker: true,
        timePicker: true,
        showDropdowns: true,
        // minDate: new Date(),
        // startDate: moment().subtract(6, 'days'),
        locale: {
            format: 'YYYY-MM-DD HH:mm:ss'
        }
    }
);

$('.datee').daterangepicker(
    {
        singleDatePicker: true,
        showDropdowns: true,
        // minDate: new Date(),
        // startDate: moment().subtract(6, 'days'),
        locale: {
            format: 'YYYY-MM-DD'
        }
    }
);

$('.datee2').daterangepicker(
    {
        singleDatePicker: true,
        showDropdowns: true,
        // minDate: new Date(),
        // startDate: moment().subtract(6, 'days'),
        locale: {
            format: 'YYYY-MM-DD'
        }
    }
);

$('.dateTimeRange').daterangepicker(
    {
        timePicker: true,
        showDropdowns: true,
        minDate: new Date(),
        locale: {
            format: 'YYYY-MM-DD HH:mm:ss'
        }
    }
);


$('.dateRange').daterangepicker(
    {
        showDropdowns: true,
        minDate: new Date(),
        locale: {
            format: 'YYYY-MM-DD'
        }
    }
);

$('.dataTable').DataTable().page(10).draw('page');

$('.dataTableListTrainee').DataTable({
    "paging":   false,
    "info":     false
});

$('.dataTableScroll').DataTable( {
        "scrollY":        "300px",
        "scrollCollapse": true,
        "paging":         false
    } );


function inform(sms)
	{
        if(typeof sms.success !== 'undefined'){
            $('.modal').modal('hide');
		    alert(sms.success);
            location.reload();
		}else{
            alert(sms.error);
		}

	}
