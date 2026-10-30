<?php 
    session_start();
    include_once("inc/crud.php");

    $crud = new Crud();
    $_SESSION['user_id'] = null;
    
    if(isset($_POST['Submit'])) {
        if($_POST['username'] == "")
        {
            $smsg = "Das Feld „Benutzername“ ist leer!";
        } 
        else if($_POST['password'] == "")
        {
            $smsg = "Das Feld „Passwort“ ist leer!";
        }
        else 
        {
            $username = $crud->escape($_POST['username']);
            $password = $crud->escape($_POST['password']);
            $crud = new Crud();
            $query = "SELECT * FROM `users` WHERE username = '$username' AND pass ='$password'"; 
            $result = $crud->getData($query);

            if ($result == false) 
            {   
                $fmsg = "Benutzername oder Passwort ist falsch!";          
            }
            else 
            {  
                foreach ($result as $res) 
                {
                    $_SESSION['user_id'] = $res['id'];
                    $_SESSION['firstname'] = $res['firstname'];
                    $_SESSION['lastname'] = $res['lastname'];
                }
                header('location:items/');
            }
        }
    }
 
?>
<!DOCTYPE html>
<html>
	<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" /> 
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <link rel="icon" href="inc/icon.png" type="image/x-icon">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css">
        <title>KHK</title>
    </head>
	<body>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div style="margin-top: 200px; border: 2px solid #534aff;" class="card">
                        <div style="background-color:#534aff;" class="card-header h4 text-white thc"><i class="fas fa-key"></i> Login</div>

                        <div class="card-body">
                            <form method="POST" action="#">
                                <div class="form-group row">
                                    <label for="username" class="col-md-4 col-form-label text-md-right">Benutzername</label>
                                    <div class="col-md-6">
                                        <input id="username" type="text" class="form-control" name="username">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">Passwort</label>
                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control" name="password">
                                    </div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button id="save" name="Submit" type="submit" class="btn btn-primary w-100">
                                        Bestätigen <i class="fas fa-sign-in-alt"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="container mt-2 text-center">
                                    <?php if(isset($smsg)){ ?><div class="alert alert-info" role="alert"> <?php echo $smsg; ?> </div>
                                    <?php } ?>	
                                    <?php if(isset($fmsg)){ ?><div class="alert alert-info" role="alert"> <?php echo $fmsg; ?> </div>
                                    <?php } ?>	
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>