<?php
session_start();
if($_SESSION['user_id'] == null)
{
    header('location:../index.php');
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
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <link rel="icon" href="../inc/icon.png" type="image/x-icon">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css">
        <title>KHK</title>
      
    </head>
	<body>
        <div class="container">
            <nav class="navbar navbar-dark bg-dark mt-1">
                <a class="navbar-brand dropdown-toggle" style="margin:0;" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['firstname']; ?> <?php echo $_SESSION['lastname']; ?></a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a id="myInfo" class="dropdown-item" href="../info.php">Mein Konto</a>
                    <a id="history" class="dropdown-item" href="../history.php">Verlauf</a>
                    <div class="dropdown-divider"></div>
                    <a  id="logout" class="dropdown-item" href="../logout.php">Abmelden</a>
                </div>
                <div class="form-inline my-2 my-lg-0">
                    <button id="backBtn" class="btn btn-secondary btn-sm"><?php echo date("d.m.Y"); ?></button>
                </div>
            </nav>

            <input id="hiddenId" type="hidden" value="<?php echo $_SESSION['user_id'] ?>">
            <input id="firstname" type="hidden" value="<?php echo $_SESSION['firstname'] ?>">
            <input id="lastname" type="hidden" value="<?php echo $_SESSION['lastname'] ?>">

            <div class="alert alert-secondary mt-2" role="alert">
                <div class="form-inline justify-content-center my-lg-0">
                    <select id="selectItem" class="form-control form-control-sm w-auto">
                        <option value="backwaren">Backwaren</option>
                        <option value="milchprodukte">Milchprodukte</option>
                        <option value="kase">Käse</option>
                        <option value="nudeln">Nudeln</option>
                        <option value="reis">Reis</option>
                        <option value="tiefkuhl-gefrorenes">Tiefkühl / Gefrorenes</option>
                        <option value="obst-gemuse">Obst & Gemüse</option>
                        <option value="marmelade">Marmelade</option>
                        <option value="konserven">Konserven</option>
                        <option value="essig-ol">Essig & Öl</option>
                        <option value="gewurze">Gewürze</option>
                        <option value="nusse">Nüsse etc.</option>
                        <option value="tee">Tee</option>
                        <option value="kaffee">Kaffee</option>
                        <option value="getränke">Getränke</option>
                        <option value="weiteres">Weiteres</option>
                        <option value="sonstiges">Sonstiges</option>
                    </select>
                </div>
			</div>
            <div id="itemContainer" class="mt-2"></div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="headerItem" class="modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="itemQuantity" class="font-weight-bold">Bitte Produktmenge eingeben</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button class="btn btn-outline-secondary" type="button" id="qtyMinus">&minus;</button>
                            </div>
                            <input type="number" id="itemQuantity" class="form-control text-center"min="1" step="1" value="1" inputmode="numeric">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="qtyPlus">+</button>
                                <span class="input-group-text">Stück</span>
                            </div>
                        </div>
                        <small class="form-text text-muted">Nur ganze Zahlen ab 1.</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Schließen</button>
                        <button type="button" class="btn btn-primary" id="saveItem">Speichern</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="../data/script.js" type="text/javascript"></script>
        <script src="../js/script.js" type="text/javascript"></script>
    </body>
</html>