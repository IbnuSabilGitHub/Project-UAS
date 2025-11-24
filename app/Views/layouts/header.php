<!DOCTYPE html>
<html lang="id">
<?php
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'HRIS' ?></title>
    <link rel="stylesheet" href="<?= asset('css/output.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body class="dark bg-neutral-secondary-medium">