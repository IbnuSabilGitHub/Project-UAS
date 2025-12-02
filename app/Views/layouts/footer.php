<script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>


<!-- Toast Notification System -->
<?php require_once __DIR__ . '/components/toast.php'; ?>
<script src="<?= asset('js/toast.js') ?>"></script>

<!-- Auto-show flash messages dari PHP session -->
<script>
    <?php 
    $flash = getFlash();
    if ($flash): 
    ?>
    // Tampilkan toast dari flash message
    document.addEventListener('DOMContentLoaded', function() {
        ToastManager.show('<?= $flash['type'] ?>', '<?= addslashes($flash['message']) ?>');
    });
    <?php endif; ?>
</script>

</body>
</html>
