<?php
session_start();
date_default_timezone_set('Europe/Vienna');
include_once("inc/crud.php");

if (empty($_SESSION['user_id'])) {
    header('location:../index.php');
    exit;
}
$userId = $_SESSION['user_id'];

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

$weekdays = ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'];

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// "Di, 03.11.2026"
function germanDate($ymd, $weekdays) {
    $t = strtotime($ymd);
    return $weekdays[date('w', $t)] . ', ' . date('d.m.Y', $t);
}

$crud = new Crud();

// 1) all days that have products, newest first
$days = $crud->getData(
    "SELECT item_date, COUNT(*) AS cnt FROM `items` GROUP BY item_date ORDER BY item_date DESC"
) ?: [];
$dayList = array_column($days, 'item_date');

// 2) selected day from URL (?date=2026-11-03), validated; default = newest day
$selected = $_GET['date'] ?? '';
if (!in_array($selected, $dayList, true)) {
    $selected = $dayList[0] ?? null;
}

// 3) products of the selected day
$result = [];
$groups = [];
if ($selected !== null) {
    // $selected is guaranteed to be one of the dates from the database (checked above)
    $result = $crud->getData(
        "SELECT * FROM `items` WHERE item_date = '$selected' ORDER BY item_category, item_time"
    ) ?: [];
    foreach ($result as $res) {
        $groups[$res['item_category']][] = $res;
    }
}

// previous (older) / next (newer) day for the arrow buttons
$pos   = $selected !== null ? array_search($selected, $dayList, true) : false;
$older = ($pos !== false && isset($dayList[$pos + 1])) ? $dayList[$pos + 1] : null;
$newer = ($pos !== false && $pos > 0) ? $dayList[$pos - 1] : null;
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

            <?php if (empty($dayList)): ?>
                <div class="alert alert-info text-center mt-2">
                    <i class="fas fa-info-circle mr-1"></i>Es gibt noch keine Einträge im Verlauf.
                </div>
            <?php else: ?>

                <!-- day picker -->
                <div class="alert alert-secondary mt-2 text-center" role="alert">
                    <h5 class="mb-2"><i class="fas fa-history mr-2"></i>Verlauf</h5>
                    <div class="input-group input-group-sm mx-auto" style="max-width: 380px;">
                        <div class="input-group-prepend">
                            <?php printf(
                                '<a class="btn btn-outline-dark %s" href="%s" title="Älterer Tag"><i class="fas fa-chevron-left"></i></a>',
                                $older ? '' : 'disabled',
                                $older ? 'history.php?date=' . e($older) : '#'
                            ); ?>
                        </div>
                        <select id="selectDay" class="custom-select">
                            <?php foreach ($days as $d) {
                                printf(
                                    '<option value="%s"%s>%s &middot; %d Artikel</option>',
                                    e($d['item_date']),
                                    $d['item_date'] === $selected ? ' selected' : '',
                                    e(germanDate($d['item_date'], $weekdays)),
                                    (int)$d['cnt']
                                );
                            } ?>
                        </select>
                        <div class="input-group-append">
                            <?php printf(
                                '<a class="btn btn-outline-dark %s" href="%s" title="Neuerer Tag"><i class="fas fa-chevron-right"></i></a>',
                                $newer ? '' : 'disabled',
                                $newer ? 'history.php?date=' . e($newer) : '#'
                            ); ?>
                        </div>
                    </div>
                </div>

                <!-- products of the selected day -->
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
                                    <div class="col-8 col-sm-5">Artikel</div>
                                    <div class="col-4 col-sm-2 text-center">Menge</div>
                                    <div class="d-none d-sm-block col-sm-3">Eingetragen von</div>
                                    <div class="d-none d-sm-block col-sm-2 text-right">Uhrzeit</div>
                                </div>
                            </li>
                            <?php foreach ($items as $it) {
                                printf(
                                    '<li class="list-group-item list-group-item-action py-2 itemRow" style="cursor:pointer;"
                                         data-kategorie="%s" data-artikel="%s" data-menge="%d"
                                         data-person="%s %s" data-datum="%s" data-zeit="%s">
                                        <div class="row align-items-center">
                                            <div class="col-8 col-sm-5 text-break">%s</div>
                                            <div class="col-4 col-sm-2 text-center">
                                                <span class="badge badge-primary badge-pill px-3">%d</span>
                                                <i class="fas fa-chevron-right text-muted ml-2 d-sm-none"></i>
                                            </div>
                                            <div class="d-none d-sm-block col-sm-3">%s %s</div>
                                            <div class="d-none d-sm-block col-sm-2 text-right text-muted">%s</div>
                                        </div>
                                     </li>',
                                    // data attributes for the modal
                                    e($labels[$cat] ?? $cat),
                                    e($it['item_name']),
                                    (int)$it['item_quantity'],
                                    e($it['firstname']), e($it['lastname']),
                                    e(germanDate($it['item_date'], $weekdays)),
                                    e(substr($it['item_time'], 0, 5)),
                                    // visible columns
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

        <!-- Detail modal (one for all records, filled on click) -->
        <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="detailTitle"></h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Schließen">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted"><i class="fas fa-tags fa-fw mr-2"></i>Kategorie</span>
                                <strong id="detailKategorie"></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted"><i class="fas fa-sort-numeric-up fa-fw mr-2"></i>Menge</span>
                                <span class="badge badge-primary badge-pill px-3" id="detailMenge"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted"><i class="fas fa-user fa-fw mr-2"></i>Eingetragen von</span>
                                <strong id="detailPerson"></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted"><i class="fas fa-calendar-alt fa-fw mr-2"></i>Datum</span>
                                <strong id="detailDatum"></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted"><i class="fas fa-clock fa-fw mr-2"></i>Uhrzeit</span>
                                <strong id="detailZeit"></strong>
                            </li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Schließen</button>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
			$("#backBtn").click(function () {
                window.history.back();
            });

            // choose a day -> reload page with that date
            $('#selectDay').on('change', function () {
                window.location.href = 'history.php?date=' + encodeURIComponent($(this).val());
            });

            $(document).on('click', '.itemRow', function () {
                const row = $(this);
                $('#detailTitle').text(row.attr('data-artikel'));
                $('#detailKategorie').text(row.attr('data-kategorie'));
                $('#detailMenge').text(row.attr('data-menge'));
                $('#detailPerson').text(row.attr('data-person'));
                $('#detailDatum').text(row.attr('data-datum'));
                $('#detailZeit').text(row.attr('data-zeit') + ' Uhr');
                $('#detailModal').modal('show');
            });
        </script>
    </body>
</html>