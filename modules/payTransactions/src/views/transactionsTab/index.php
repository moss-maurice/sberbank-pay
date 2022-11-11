<?php if (is_array($transactions) and !empty($transactions)) : ?>
<table id="transactionsList" class="table data">
    <thead>
        <tr>
            <td class="tableHeader right" style="width: 50px;">#</td>
            <td class="tableHeader">Номер транзакции</td>
            <td class="tableHeader">Общая стоимость</td>
            <td class="tableHeader">Заморожено</td>
            <td class="tableHeader">Остаток</td>
            <td class="tableHeader">Страница</td>
            <td class="tableHeader">Email</td>
            <td class="tableHeader">Телефон</td>
            <td class="tableHeader">Регистрация</td>
            <td class="tableHeader">Исполнение</td>
            <td class="tableHeader">Статус</td>
            <td class="tableHeader"></td>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($transactions as $transaction) : ?>
        <tr data-id="<?= $transaction['id']; ?>" data-order-id="<?= $transaction['order_id']; ?>" data-merchant="<?= $transaction['merchant']; ?>">
            <td class="tableItem right"><?= $transaction['id']; ?></td>
            <td class="tableItem right"><?= $transaction['order_number']; ?></td>
            <td class="tableItem right">
        <?php if (in_array(intval($transaction['status']), [1, 2])) : ?>
                <div class="public-block">
                    <?= floatval($transaction['total_amount']); ?> <i class="fa fa-ruble-sign"></i>
                    <span class="btn btn-primary btn-sm tr-edit-total-amount-button">
                        <i class="fa fa-edit"></i>
                    </span>
                </div>
                <div class="hidden-block hide">
                    <input class="form-control" type="number" name="edit-total-amount" value="<?= floatval($transaction['total_amount']); ?>" />
                    <span rel-id="<?= $transaction['id']; ?>" rel-tab="<?= $tabName; ?>" rel-method="saveTotalAmount" rel-page="<?= $page; ?>" class="btn btn-success btn-sm tr-save-total-amount-button">
                        <i class="fa fa-check"></i>
                    </span>
                </div>
            <?php else : ?>
                <?= floatval($transaction['total_amount']); ?> <i class="fa fa-ruble-sign"></i>
            <?php endif; ?>
            </td>
            <td class="tableItem right"><?= floatval($transaction['amount']); ?> <i class="fa fa-ruble-sign"></i></td>
            <td class="tableItem right"><?= (floatval($transaction['total_amount']) - floatval($transaction['amount'])); ?> <i class="fa fa-ruble-sign"></i></td>
            <td class="tableItem"><?= $transaction['pagetitle']; ?></td>
            <td class="tableItem">
                <a href="mailto:<?= $transaction['email']; ?>"><?= $transaction['email']; ?></a>
            </td>
            <td class="tableItem"><?= $transaction['phone']; ?></td>
            <td class="tableItem"><?= $transaction['register_date']; ?></td>
            <td class="tableItem right"><?= !is_null($transaction['deposit_date']) ? $transaction['deposit_date'] : '&mdash;'; ?></td>
            <td class="tableItem right">
                <div class="btn-group btn-group-sm">
        <?php if (intval($transaction['status']) === 1) : ?>
                    <span rel-id="<?= $transaction['id']; ?>" rel-tab="<?= $tabName; ?>" rel-method="deposit" rel-page="<?= $page; ?>" class="btn btn-primary tr-unlock-button tr-action-buttons">Разморозить</span>
                    <span rel-id="<?= $transaction['id']; ?>" rel-tab="<?= $tabName; ?>" rel-method="reverse" rel-page="<?= $page; ?>" class="btn btn-warning tr-refund-button tr-action-buttons">Отмена</span>
        <?php elseif (intval($transaction['status']) === 2) : ?>
                    <span rel-id="<?= $transaction['id']; ?>" rel-tab="<?= $tabName; ?>" rel-method="request" rel-page="<?= $page; ?>" class="btn btn-success tr-request-button tr-action-buttons">Остаток</span>
                    <span rel-id="<?= $transaction['id']; ?>" rel-tab="<?= $tabName; ?>" rel-method="refund" rel-page="<?= $page; ?>" class="btn btn-warning tr-refund-button tr-action-buttons">Возврат</span>
        <?php elseif (intval($transaction['status']) === 7) : ?>
                    <span rel-id="<?= $transaction['id']; ?>" rel-tab="<?= $tabName; ?>" rel-method="refund" rel-page="<?= $page; ?>" class="btn btn-warning tr-refund-button tr-action-buttons">Возврат</span>
        <?php else : ?>
                    <span class="btn"><?= $transaction['status_msg']; ?></span>
        <?php endif; ?>
                </div>
            </td>
            <td class="tableItem right">
                <div class="btn-group btn-group-sm">
                    <span rel-id="<?= $transaction['id']; ?>" rel-tab="<?= $tabName; ?>" rel-method="done" rel-page="<?= $page; ?>" class="btn btn-success tr-done-button tr-status-buttons">
                        <i class="fa fa-check"></i>
                    </span>
                    <span rel-id="<?= $transaction['id']; ?>" rel-tab="<?= $tabName; ?>" rel-method="delete" rel-page="<?= $page; ?>" class="btn btn-danger tr-delete-button tr-status-buttons">
                        <i class="fa fa-trash-o"></i>
                    </span>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php include realpath(dirname(__FILE__) . '/../_parts/pagination.php'); ?>
<?php endif; ?>
