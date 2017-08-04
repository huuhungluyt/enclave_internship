<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/models/Course.php';
include("views/inc/_header.php");?>

<section>
    <div class="container">
        <div class="row">

            <div class="col-md-6">
                <div class= "page-header">
                    <h3><span class="glyphicon glyphicon-certificate"></span> New notifications</h3>
                </div>
                <table id="tableNewNotis" class="order-column hover" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Detail</th>
                            <th>Message ID</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Detail</th>
                            <th>Message ID</th>
                            <th>Time</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>


            <div class="col-md-6">
                <div class= "page-header">
                    <h3><span class="glyphicon glyphicon-comment"></span> Seen notifications</h3>
                </div>
                <table id="tableReadNotis" class="order-column hover" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Detail</th>
                            <th>Message ID</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Detail</th>
                            <th>Message ID</th>
                            <th>Time</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        
</div>
</section>
<?php include("views/inc/_footer.php");?>
