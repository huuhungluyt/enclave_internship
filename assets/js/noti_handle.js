function format(d) {
    if (d.type.localeCompare("denied course") == 0)
        return "<div class='alert alert-danger'>" +
            "<strong><span class='glyphicon glyphicon-remove'></span></strong> Admin denied your suggestion about <b>new " + d.content + " course</b>!" +
            "</div>";

    if (d.type.localeCompare("denied major") == 0)
        return "<div class='alert alert-danger'>" +
            "<strong><span class='glyphicon glyphicon-remove'></span></strong> Admin denied your suggestion about <b>new major " + d.content + "</b>!" +
            "</div>";

    if (d.type.localeCompare("denied schedule") == 0)
        return "<div class='alert alert-danger'>" +
            "<strong><span class='glyphicon glyphicon-remove'></span></strong> Admin denied your suggestion about <b>changing schedule of course (ID: " + d.content + ")</b>!" +
            "</div>";

    if (d.type.localeCompare("approved course") == 0){
        var content=d.content.split(";");
        return "<div class='alert alert-success'>" +
            "<strong><span class='glyphicon glyphicon-ok'></span></strong> Admin approved your new <b>" + content[0] + "</b> course suggestion. Please join the new course <b>"+content[1]+"</b>!" +
            "</div>";
    }

    if (d.type.localeCompare("approved schedule") == 0)
        return "<div class='alert alert-success'>" +
            "<strong><span class='glyphicon glyphicon-ok'></span></strong> Admin approved your suggestion about <b>changing schedule of course (ID: " + d.content + ")</b>. Please check your schedule!" +
            "</div>";

    if (d.type.localeCompare("approved major") == 0)
        return "<div class='alert alert-success'>" +
            "<strong><span class='glyphicon glyphicon-ok'></span></strong> Admin approved your suggestion about <b> new major " + d.content + "</b>!" +
            "</div>";

    if (d.type.localeCompare("opened course") == 0){
        var content= d.content.split(";");
        return "<div class='alert alert-info'>" +
            "<strong><span class='glyphicon glyphicon-info-sign'></span></strong> The <b>"+content[1]+"</b> course (ID:<b>"+content[0]+"</b>, Trainer: <b>"+content[2]+"</b>) was <b>opened</b>!"+
            "</div>";
    }

    if (d.type.localeCompare("reopened course") == 0){
        var content= d.content.split(";");
        return "<div class='alert alert-info'>" +
            "<strong><span class='glyphicon glyphicon-info-sign'></span></strong> The <b>"+content[1]+"</b> course (ID:<b>"+content[0]+"</b>, Trainer: <b>"+content[2]+"</b>) was <b>re-opened</b>!"+
            "</div>";
    }

    if (d.type.localeCompare("changed trainer") == 0){
        var content= d.content.split(";");
        return "<div class='alert alert-info'>" +
            "<strong><span class='glyphicon glyphicon-info-sign'></span></strong> The <b>"+content[1]+"</b> course (ID: <b>"+content[0]+"</b>) was changed trainer!<br>"+
            "Trainer <b>"+content[5]+"</b> (ID: "+content[4]+") will take place of trainer <b>"+content[3]+"</b> (ID: <b>"+content[2]+"</b>) in the coming time"+
            "</div>";
    }

    if (d.type.localeCompare("updated course") == 0){
        var content= d.content.split(";");
        return "<div class='alert alert-info'>" +
            "<strong><span class='glyphicon glyphicon-info-sign'></span></strong> The <strong>"+content[1]+"</strong> course (ID:<strong>"+content[0]+"</strong>) was updated. Please check your schedule!<br>"+
            "Trainer: <strong>"+content[2]+"</strong><br>"+
            "</div>";
    }
}


$(document).ready(function () {

    var tableReadNotis = $('#tableReadNotis').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "index.php?ctr=notice&act=loadReadNotis",
        "columns": [
            {
                "class": "details-control",
                "orderable": false,
                "data": null,
                "defaultContent": ""
            },
            { "data": "id" },
            { "data": "readAt" }
        ],
        "order": [[2, 'desc']],
    });

    var tableNewNotis = $('#tableNewNotis').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "index.php?ctr=notice&act=loadNewNotis",
        "columns": [
            {
                "class": "details-control",
                "orderable": false,
                "data": null,
                "defaultContent": ""
            },
            {
                "data": "id"
            },
            {
                "data": "createAt"
            }
        ],
        "order": [[2, 'desc']],
    });

    


    


    $('#tableReadNotis tbody').on('click', 'tr td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = tableReadNotis.row(tr);
        var idx = $.inArray(tr.attr('id'), detailRows);

        if (row.child.isShown()) {
            tr.removeClass('shown');
            row.child.hide();

            // Remove from the 'open' array
            detailRows.splice(idx, 1);
        }
        else {
            tr.addClass('shown');
            row.child(format(row.data())).show();

            // Add to the 'open' array
            if (idx === -1) {
                detailRows.push(tr.attr('id'));
            }
        }
    });
    

    // Array to track the ids of the details displayed rows
    var detailRows = [];

    $('#tableNewNotis tbody').on('click', 'tr td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = tableNewNotis.row(tr);
        var idx = $.inArray(tr.attr('id'), detailRows);

        if (row.child.isShown()) {
            tr.removeClass('shown');
            row.child.hide();

            // Remove from the 'open' array
            detailRows.splice(idx, 1);
        }
        else {
            tr.addClass('shown');
            row.child(format(row.data())).show();

            // Add to the 'open' array
            if (idx === -1) {
                detailRows.push(tr.attr('id'));
            }
        }
    });

    

    // On each draw, loop over the `detailRows` array and show any child rows
    tableNewNotis.on('draw', function () {
        $.each(detailRows, function (i, id) {
            $('#' + id + ' td.details-control').trigger('click');
        });
    });

    tableReadNotis.on('draw', function () {
        $.each(detailRows, function (i, id) {
            $('#' + id + ' td.details-control').trigger('click');
        });
    });
});
