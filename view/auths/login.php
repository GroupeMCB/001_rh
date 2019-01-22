<?php 
// if($this->Session->isLogged()){
//   if($controller=='auths' )
//      header("Location:".Router::url("personnels/dashboard")); 
// }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content=" ">
    <meta name="author" content="DRI BENIN">

    <title>RH APPS </title>
    <link href="<?php echo  BASE_URL; ?>/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?php echo  BASE_URL; ?>/vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo  BASE_URL; ?>/dist/css/sb-admin-2.css" rel="stylesheet">
  </head>

  <body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Connexion</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post" action="<?php echo BASE_URL ?>/auths/login" autocomplete="off">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control"  name="username" type="text" autofocus="">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>
                                
                                <!-- Change this to a button or input when using this as a form -->
                            <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-sign-in"></i> Se connecter</button>
                           
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    <script src="<?php echo BASE_URL; ?>/lib/jquery/jquery.js"></script>
    <script src="<?php echo BASE_URL; ?>/lib/popper.js/popper.js"></script>
    <script src="<?php echo BASE_URL; ?>/lib/bootstrap/bootstrap.js"></script>

  </body>
</html>
