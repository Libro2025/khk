<?php
session_start();
include_once("inc/crud.php");
if($_SESSION['user_id'] == null)
{
    header('location:../index.php');
}
$userId = $_SESSION['user_id'];
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

            <div class="alert alert-secondary text-center mt-2" role="alert">
                <h1 class="alert-heading">Im Gange</h1>
                <p>Der Verlauf ist noch nicht implementiert.</p>
            </div>

        </div>  

        <script type="text/javascript">
			$("#backBtn").click(function (){
                window.history.back();
            });
        </script>
    </body>
</html>