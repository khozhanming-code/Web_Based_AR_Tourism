<?php require_once __DIR__ . '/functions.php'; $title = $title ?? APP_NAME; ?>
<!doctype html><html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($title) ?> · <?= APP_NAME ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
<link href="<?= e(BASE_URL) ?>assets/css/app.css" rel="stylesheet"></head><body>
<nav class="navbar navbar-expand-lg navbar-dark bg-night"><div class="container">
<a class="navbar-brand fw-bold" href="<?= e(BASE_URL) ?>"><i class="fa-solid fa-vr-cardboard text-gold"></i> AR Tourism</a>
<button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
<div class="collapse navbar-collapse" id="nav"><ul class="navbar-nav ms-auto">
<li class="nav-item"><a class="nav-link" href="<?= e(BASE_URL) ?>">Home</a></li><li class="nav-item"><a class="nav-link" href="<?= e(BASE_URL) ?>destinations.php">Destinations</a></li>
<li class="nav-item"><a class="nav-link" href="<?= e(BASE_URL) ?>attractions.php">Attractions</a></li><li class="nav-item"><a class="btn btn-gold ms-lg-2" href="<?= e(BASE_URL) ?>ar.php"><i class="fa-solid fa-camera"></i> Start AR</a></li>
</ul></div></div></nav>
<?php foreach (flashes() as [$type,$message]): ?><div class="container mt-3"><div class="alert alert-<?= e($type) ?>"><?= e($message) ?></div></div><?php endforeach; ?>
