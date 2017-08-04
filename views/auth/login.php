<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=1,initial-scale=1,user-scalable=1" />
    <title>Login - Enclave Training Course</title>

    <link href="http://fonts.googleapis.com/css?family=Lato:100italic,100,300italic,300,400italic,400,700italic,700,900italic,900" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="assets/bootstrap-3.3.7-dist/css/bootstrap.min.css" />
    <script src="assets/js/jquery-3.2.1.min.js"></script>
    <script src="assets/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    <style type="text/css" media="screen">        
        .error{
            color:red;
        }
        * {
            -webkit-box-sizing: border-box;
               -moz-box-sizing: border-box;
                    box-sizing: border-box;
            outline: none;
        }
        body {
            background: url(assets/img/bg.jpg) no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }

        .login-form {
            font: 16px/2em Lato, serif;
            margin: 100px auto;
            max-width: 400px;
        }

        form[role=login] {
            color: #5d5d5d;
            background: #f2f2f2;
            padding: 26px;
            border-radius: 10px;
            -moz-border-radius: 10px;
            -webkit-border-radius: 10px;
        }
            form[role=login] img {
                display: block;
                margin: 0 auto;
                margin-bottom: 35px;
            }
            form[role=login] input,
            form[role=login] button {
                font-size: 18px;
                margin: 16px 0;
            }
            form[role=login] > div {
                text-align: center;
            }
            
        .form-links {
            text-align: center;
            margin-top: 1em;
            margin-bottom: 50px;
        }
        .form-links a {
            color: #fff;
        }
        /**/
        .popup-cart{
            width: 40%;
            background: rgba(39, 40, 42, 0.7);
            text-align: center;
            margin: auto;
            position: fixed;
            top: 70px;
            left: 30%;
            display: none;
        }
        .popup-cart .content{
            padding: 40px 20px;
        }
        .popup-cart .content span{
            font-size: 4em;
            color:white;
        }
        .popup-cart .content p{
            font-size: 19px;
            color: #fe980f;
        }
        .warning{
            margin:0;
            color: red;
            font-size: 13px;
        }

    </style>
</head>

<body>

    <section class="container">
        <section class="login-form">
            <form id="formLogin" method="post" action="index.php?ctr=auth&act=postLogin" role="login">
                <img src="assets/img/enclave_logo.png" class="img-responsive" alt="" />
                <input type="text" name="id" placeholder="ID" class="form-control input-lg" />
                <input type="password" name="password" placeholder="Password" class="form-control input-lg" />
                <button type="submit" name="signin" class="btn btn-lg btn-primary btn-block">Sign in</button>
                <?php
                    if(isset($_SESSION['warning'])){
                        if($_SESSION['warning']){
                            echo '<p style="color:red; text-align:center">'.$_SESSION['warning'].'</p>';
                            unset($_SESSION['warning']);
                        }
                    }
                ?>
                <div style="text-align: center;">
                    <a href="#" class="pwd-modal">Forgot my password</a>                    
                </div>
            </form>
            <div class="form-links">
                <a target="_blank" href="http://enclaveit.com">www.enclaveit.com</a>
            </div>
        </section>
    </section>
   
    <!--modal-->
    <div id="pwdModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h1 class="text-center">What's My Password?</h1>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="text-center">
                                    <p>If you have forgotten your password you can reset it here.</p>
                                    <div class="panel-body">
                                        <fieldset>
                                            <form id="formSendNewPass" method="post" accept-charset="utf-8">
                                                <div class="form-group">
                                                    <input class="form-control input-lg" placeholder="E-mail Address" name="email" type="text">
                                                </div>
                                                <input class="btn btn-lg btn-primary btn-block" value="Send My Password" type="submit">
                                            </form>                                            
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-md-12">
                        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>    
    <!--ALERT-->
    <div class="popup-cart">
        <div class="content">
            <span><i class="fa fa-check-circle" aria-hidden="true"></i></span>
            <p></p>
        </div>
    </div>
    <script src="assets/js/jquery.validate.min.js"></script>
    <script type="text/javascript">
    $(function (){
        $.validator.addMethod("customemail", 
            function(value, element) {
                var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(value);
            }, 
            "Please enter a valid email address. For example johndoe@domain.com."
        );
        $("#formSendNewPass input[name=email]").change(function () {
            $('#formSendNewPass input[type="submit"]').prop('disabled', false);
        });
        
        $('.pwd-modal').click(function(e) {
            e.preventDefault();
            $('#formSendNewPass input[name=email]').val('');
            if (($(".warning").length > 0)){
                $(".warning").remove();
            }
            $('#formSendNewPass input[type="submit"]').prop('disabled', false);
            $('#pwdModal').modal('show');
        });

        $("#formSendNewPass").validate({
            rules: {
                email:  {
                    required: true,
                    customemail: true
                }
            },
            messages: {
            },
            submitHandler: function (form) {
                if (!confirm("Do you want to send your new password to this email ?")) return
                // form.submit();
                var email = $('#formSendNewPass').find('input[name="email"]').val();
                $('#formSendNewPass input[type="submit"]').prop('disabled', true);
                $.ajax({
                    method: "POST",
                    url: "index.php?ctr=auth&act=forgotPassword",
                    data: {                    
                        email : email             
                    }
                })
                .done(function( data ) {
                    // console.log(data);
                    var dataObj = JSON.parse(data);
                    // console.log(dataObj);
                    if(typeof dataObj.success !== 'undefined'){
                        $('#pwdModal').modal('hide');                        
                        $('#formSendNewPass').find('input[name="email"]').val('');
                        alert(dataObj.success);
                    }else{
                        if (($(".warning").length > 0)){
                           $(".warning").remove();
                        }
                        var html = '<p class="warning">' + dataObj.error + '</p>';
                        $('#formSendNewPass input[name="email"]').parent().append(html);
                    }

                }); 
            }
        });
        $("#formLogin").validate({
            rules: {
                id: {
                    required : true,
                    number :  true
                },
                password: "required"            
            },
            messages: {
                
            },
            submitHandler: function (form) {            
                form.submit();
            }            
        });

    });    
    </script>
</body>

</html>
