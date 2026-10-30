<?php
session_start();
include_once("inc/crud.php");
if($_SESSION['user_id'] == null)
{
    header('location:../index.php');
}
$userId = $_SESSION['user_id'];

$crud = new Crud();
$query = "SELECT * FROM `users` WHERE id = '$userId'"; 
$result = $crud->getData($query);
foreach($result as $res){
    $firstname = $res['firstname'];
    $lastname = $res['lastname'];
    $username = $res['username'];
    $pass = $res['pass'];
}

if(isset($_POST['save'])){
    $user = $crud->escape($_POST['user']);
    $pass = $crud->escape($_POST['pass']);
    $firstname = $crud->escape($_POST['firstname']);
    $lastname = $crud->escape($_POST['lastname']);

    $crud = new Crud();
    $query = "UPDATE `users` SET `firstname`='$firstname',`lastname`='$lastname',`username`='$user',`pass`='$pass' WHERE `id` = $userId";
    $result = $crud->execute($query);

    header('location:items/');
}

?>
<!DOCTYPE html>
<html>
	<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" /> 
        <link rel="stylesheet" type="text/css" href="../css/style.css">
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
            <nav class="navbar navbar-dark bg-dark mt-1">
                <a class="navbar-brand dropdown-toggle" style="margin:0;" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['firstname']; ?> <?php echo $_SESSION['lastname']; ?></a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a id="myInfo" class="dropdown-item" href="info.php">Mein Konto</a>
                    <a id="history" class="dropdown-item" href="history.php">Verlauf</a>
                    <div class="dropdown-divider"></div>
                    <a  id="logout" class="dropdown-item" href="logout.php">Abmelden</a>
                </div>
                <div class="form-inline my-2 my-lg-0">
                    <button id="backBtn" class="btn btn-secondary btn-sm">Zurück</button>
                </div>
            </nav>

            <form method="POST" action="#">
                <div class="row mt-2">
                    <div class="col-12">
                        Benutzername :<br>
                        <input id="user" name="user" type="text" class="form form-control" value="<?php echo $username; ?>" required>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-12">
                        Passwort :<br>
                        <input id="pass" name="pass" type="text" class="form form-control" value="<?php echo $pass; ?>" required>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-12">
                        Vorname : <br>
                        <input id="firstname" name="firstname" type="text" class="form form-control" value="<?php echo $firstname; ?>" required>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-12">
                        Nachname : <br>
                        <input id="lastname" name="lastname" type="text" class="form form-control" value="<?php echo $lastname; ?>" required>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <button id="save" name="save" type="submit" class="btn btn-primary w-100">Speichern</button>
                    </div>
                </div>
            </form>

        </div>  

        <script type="text/javascript">
			$("#backBtn").click(function (){
                window.history.back();
            });
        </script>
    </body>
</html>