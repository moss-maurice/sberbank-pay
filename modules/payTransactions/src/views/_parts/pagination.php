<?php $limits = 3; ?>

<?php if ($pages > 0) : ?>
    <br />
<div class="pagination">
    <div class="btn-group btn-group-sm">
    <?php if ($page >= 1) : ?>
        <?php if (($page - $limit) > 1) : ?>
        <span rel-tab="transactionsTab" rel-method="index" rel-page="1" class="btn tr-page-button tr-pagination-buttons">Первая</span>
        <?php endif; ?>
        <?php for ($i = $limits; $i > 0; $i--) : ?>
            <?php if (($page - $i) >= 1) : ?>
        <span rel-tab="transactionsTab" rel-method="index" rel-page="<?= ($page - $i); ?>" class="btn tr-page-button tr-pagination-buttons"><?= ($page - $i); ?></span>
            <?php endif; ?>
        <?php endfor; ?>
    <?php endif; ?>
        <span rel-tab="transactionsTab" rel-method="index" rel-page="<?= $page; ?>" class="btn btn-success tr-page-button tr-pagination-buttons"><?= $page; ?></span>
    <?php if ($page <= $pages) : ?>
        <?php for ($i = 1; $i <= $limits; $i++) : ?>
            <?php if (($page + $i) <= $pages) : ?>
        <span rel-tab="transactionsTab" rel-method="index" rel-page="<?= ($page + $i); ?>" class="btn tr-page-button tr-pagination-buttons"><?= ($page + $i); ?></span>
            <?php endif; ?>
        <?php endfor; ?>
        <?php if (($page + $limit) < $pages) : ?>
        <span rel-tab="transactionsTab" rel-method="index" rel-page="<?= $pages; ?>" class="btn tr-page-button tr-pagination-buttons">Последняя</span>
        <?php endif; ?>
    <?php endif; ?>
    </div>
</div>
<?php endif; ?>
