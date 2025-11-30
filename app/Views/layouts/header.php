<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'HRIS' ?></title>
    <link href="<?= asset('css/output.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Jika di komputer kalian style flowbite tidak berfungsi gunakan aja cdn ini -->
    <!-- Flowbite tidak berfungsi mungkin karena htdoc selalu tricky dalam hal path-->
    <!-- <link href="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.css" rel="stylesheet" /> -->
    
    <!-- Cegah FOUC(flash of unstyled content) – terapkan tema sedini mungkin -->
    <script src="<?= asset('js/apply-theme.js') ?>"></script>

</head>

<body class=" bg-neutral-primary text-heading">

