<?php if (isset($pagination) && $pagination['total'] > 1): ?>
    <div class="bg-neutral-secondary-soft px-6 py-4 border-t border-default flex items-center justify-between">
        <div class="text-sm text-body">
            Menampilkan halaman <?= $pagination['current'] ?> dari <?= $pagination['total'] ?>
            <?php if (isset($pagination['totalRecords'])): ?>
                (Total: <?= $pagination['totalRecords'] ?> record)
            <?php endif; ?>
        </div>
        <div class="flex gap-2">
            <?php if ($pagination['current'] > 1): ?>
                <a href="<?= $pagination['prevUrl'] ?? url($pagination['baseUrl'] . '?page=' . ($pagination['current'] - 1)) ?>"
                    class="px-4 py-2 bg-neutral-primary-soft border border-default rounded-base hover:bg-neutral-secondary-soft text-sm">
                    Sebelumnya
                </a>
            <?php endif; ?>

            <?php 
            $start = max(1, $pagination['current'] - 2);
            $end = min($pagination['total'], $pagination['current'] + 2);
            for ($i = $start; $i <= $end; $i++): 
            ?>
                <a href="<?= $pagination['pageUrl'][$i] ?? url($pagination['baseUrl'] . '?page=' . $i) ?>"
                    class="px-4 py-2 border rounded-base text-sm <?= $i === $pagination['current'] ? 'bg-brand text-white border-brand' : 'bg-neutral-primary-soft border-default hover:bg-neutral-secondary-soft' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($pagination['current'] < $pagination['total']): ?>
                <a href="<?= $pagination['nextUrl'] ?? url($pagination['baseUrl'] . '?page=' . ($pagination['current'] + 1)) ?>"
                    class="px-4 py-2 bg-neutral-primary-soft border border-default rounded-base hover:bg-neutral-secondary-soft text-sm">
                    Selanjutnya
                </a>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
