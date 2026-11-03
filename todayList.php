<?php
session_start();
date_default_timezone_set('Europe/Vienna');
include_once("inc/crud.php");

if (empty($_SESSION['user_id'])) {
    header('location:../index.php');
    exit;
}

$labels = [
    'backwaren'           => 'Backwaren',
    'milchprodukte'       => 'Milchprodukte',
    'kase'                => 'Käse',
    'nudeln'              => 'Nudeln',
    'reis'                => 'Reis',
    'tiefkuhl-gefrorenes' => 'Tiefkühl / Gefrorenes',
    'obst-gemuse'         => 'Obst & Gemüse',
    'marmelade'           => 'Marmelade',
    'konserven'           => 'Konserven',
    'essig-ol'            => 'Essig & Öl',
    'gewurze'             => 'Gewürze',
    'nusse'               => 'Nüsse etc.',
    'tee'                 => 'Tee',
    'kaffee'              => 'Kaffee',
    'getränke'            => 'Getränke',
    'weiteres'            => 'Weiteres',
    'sonstiges'           => 'Sonstiges',
];

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

$crud   = new Crud();
$today  = date("Y-m-d");
$query  = "SELECT * FROM `items` WHERE item_date = '$today' ORDER BY item_category, item_time";
$result = $crud->getData($query) ?: [];

// group rows by category
$groups = [];
foreach ($result as $res) {
    $groups[$res['item_category']][] = $res;
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
                <a class="navbar-brand dropdown-toggle" style="margin:0;" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php printf('%s %s', e($_SESSION['firstname']), e($_SESSION['lastname'])); ?></a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a id="myInfo" class="dropdown-item" href="info.php">Mein Konto</a>
                    <a id="history" class="dropdown-item" href="history.php">Verlauf</a>
                    <div class="dropdown-divider"></div>
                    <a id="logout" class="dropdown-item" href="logout.php">Abmelden</a>
                </div>
                <div class="form-inline my-2 my-lg-0">
                    <button id="backBtn" class="btn btn-secondary btn-sm">Zurück</button>
                </div>
            </nav>

            <div class="alert alert-secondary mt-2 text-center" role="alert">
                <?php printf(
                    '<h5 class="mb-1"><i class="fas fa-shopping-basket mr-2"></i>Heutige Einkaufsliste</h5>
                     <small>%s &middot; %d Artikel</small>',
                    date('d.m.Y'), count($result)
                ); ?>
            </div>

            <?php if (empty($groups)): ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle mr-1"></i>Für heute wurden noch keine Artikel eingetragen.
                </div>
            <?php else: ?>
                <?php foreach ($groups as $cat => $items): ?>
                    <div class="card shadow-sm mb-3">
                        <?php printf(
                            '<div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                                <strong>%s</strong>
                                <span class="badge badge-light">%d</span>
                             </div>',
                            e($labels[$cat] ?? $cat), count($items)
                        ); ?>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item bg-light py-2">
                                <div class="row font-weight-bold small text-muted">
                                    <div class="col-6 col-sm-5">Artikel</div>
                                    <div class="col-3 col-sm-2 text-center">Menge</div>
                                    <div class="d-none d-sm-block col-sm-3">Eingetragen von</div>
                                    <div class="col-3 col-sm-2 text-right">Uhrzeit</div>
                                </div>
                            </li>
                            <?php foreach ($items as $it) {
                                printf(
                                    '<li class="list-group-item list-group-item-action py-2">
                                        <div class="row align-items-center">
                                            <div class="col-6 col-sm-5 text-break">%s</div>
                                            <div class="col-3 col-sm-2 text-center"><span class="badge badge-primary badge-pill px-3">%d</span></div>
                                            <div class="d-none d-sm-block col-sm-3">%s %s</div>
                                            <div class="col-3 col-sm-2 text-right text-muted">%s</div>
                                        </div>
                                     </li>',
                                    e($it['item_name']),
                                    (int)$it['item_quantity'],
                                    e($it['firstname']), e($it['lastname']),
                                    e(substr($it['item_time'], 0, 5))
                                );
                            } ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <script type="text/javascript">
			$("#backBtn").click(function () {
                window.history.back();
            });
        </script>
    </body>
</html>