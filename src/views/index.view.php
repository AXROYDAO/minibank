<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MINIBANK // NERV Financial System</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="bg-rei"></div>
    <div class="overlay"></div>

    <div class="container">
        <!-- ===== HEADER ===== -->
        <header class="header">
            <div class="header__logo">
                <span class="header__icon">◆</span>
                <h1 class="header__title">MINI<span class="accent">BANK</span></h1>
            </div>
            <div class="header__subtitle">NERV FINANCIAL CONTROL SYSTEM — PERSONNEL ACCESS ONLY</div>
        </header>

        <!-- ===== STATISTICS CARDS ===== -->
        <section class="stats">
            <div class="stats__card stats__card--balance">
                <div class="stats__label">ТЕКУЩИЙ БАЛАНС</div>
                <div class="stats__value">
                    <?= $balance >= 0 ? '+' : '' ?>
                    <?= number_format($balance, 2, '.', ' ') ?> ₽
                </div>
                <div class="stats__bar"></div>
            </div>
            <div class="stats__card stats__card--income">
                <div class="stats__label">ОБЩИЙ ДОХОД</div>
                <div class="stats__value stats__value--income">
                    +<?= number_format($income, 2, '.', ' ') ?> ₽
                </div>
                <div class="stats__bar"></div>
            </div>
            <div class="stats__card stats__card--expense">
                <div class="stats__label">ОБЩИЙ РАСХОД</div>
                <div class="stats__value stats__value--expense">
                    -<?= number_format($expenses, 2, '.', ' ') ?> ₽
                </div>
                <div class="stats__bar"></div>
            </div>
        </section>

        <!-- ===== FORM: ADD TRANSACTION ===== -->
        <section class="form-section">
            <h2 class="section-title">
                <span class="section-title__bracket">[</span>
                ДОБАВИТЬ ОПЕРАЦИЮ
                <span class="section-title__bracket">]</span>
            </h2>
            <form method="POST" class="transaction-form">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">ТИП ОПЕРАЦИИ</label>
                        <div class="form-type-toggle">
                            <input type="radio" name="type" value="income" id="type-income" class="form-type-input">
                            <label for="type-income" class="form-type-btn form-type-btn--income">Доход</label>
                            <input type="radio" name="type" value="expense" id="type-expense" class="form-type-input" checked>
                            <label for="type-expense" class="form-type-btn form-type-btn--expense">Расход</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="amount">СУММА</label>
                        <input type="number" step="0.01" name="amount" id="amount" required placeholder="0.00" class="form-input">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="category">КАТЕГОРИЯ</label>
                        <select name="category" id="category" class="form-select">
                            <option value="Еда">Еда</option>
                            <option value="Аренда/Жилье">Аренда/Жилье</option>
                            <option value="Серверы/IT">Серверы/IT</option>
                            <option value="Развлечения">Развлечения</option>
                            <option value="Зарплата/Фриланс">Зарплата/Фриланс</option>
                            <option value="Другое">Другое</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="comment">ОПИСАНИЕ</label>
                        <input type="text" name="comment" id="comment" placeholder="Например: Оплата хостинга" class="form-input">
                    </div>
                    <div class="form-group form-group--btn">
                        <button type="submit" name="action" value="add_transaction" class="form-submit">
                            <span class="form-submit__text">Добавить запись</span>
                            <span class="form-submit__glow"></span>
                        </button>
                    </div>
                </div>
            </form>
        </section>

        <!-- ===== FILTERS ===== -->
        <section class="filters">
            <h2 class="section-title">
                <span class="section-title__bracket">[</span>
                ФИЛЬТР
                <span class="section-title__bracket">]</span>
            </h2>
            <div class="filters__btns">
                <a href="?filter=all" class="filters__btn filters__btn--active">Все</a>
                <a href="?filter=income" class="filters__btn filters__btn--income">Только доходы</a>
                <a href="?filter=expense" class="filters__btn filters__btn--expense">Только расходы</a>
            </div>
        </section>

        <!-- ===== TRANSACTIONS TABLE ===== -->
        <section class="table-section">
            <h2 class="section-title">
                <span class="section-title__bracket">[</span>
                ИСТОРИЯ ОПЕРАЦИЙ
                <span class="section-title__bracket">]</span>
            </h2>
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="table__th">ДАТА И ВРЕМЯ</th>
                            <th class="table__th">КАТЕГОРИЯ</th>
                            <th class="table__th">ОПИСАНИЕ</th>
                            <th class="table__th table__th--right">СУММА</th>
                            <th class="table__th table__th--center">ДЕЙСТВИЕ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $t) { ?>
                        <tr class="table__row <?= $t['type'] === 'income' ? 'table__row--income' : 'table__row--expense' ?>">
                            <td class="table__td"><?= htmlspecialchars($t['date']) ?></td>
                            <td class="table__td">
                                <span class="badge badge--<?= $t['type'] ?>">
                                    <?= htmlspecialchars($t['category']) ?>
                                </span>
                            </td>
                            <td class="table__td"><?= htmlspecialchars($t['comment']) ?></td>
                            <td class="table__td table__td--amount <?= $t['type'] === 'income' ? 'amount--income' : 'amount--expense' ?>">
                                <?= $t['type'] === 'income' ? '+' : '-' ?>
                                <?= number_format($t['amount'], 2, '.', ' ') ?> ₽
                            </td>
                            <td class="table__td table__td--center">
                                <form method="POST" class="delete-form">
                                    <button type="submit" name="delete_id" value="<?= $t['id'] ?>" class="delete-btn" title="Удалить">
                                        ✕
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- CLEAR ALL -->
            <div class="clear-section">
                <form method="POST">
                    <button type="submit" name="action" value="clear_all" class="clear-btn">
                        <span class="clear-btn__text">Очистить всё</span>
                        <span class="clear-btn__glow"></span>
                    </button>
                </form>
            </div>
        </section>

        <!-- ===== FOOTER ===== -->
        <footer class="footer">
            <div class="footer__line"></div>
            <div class="footer__text">NERV FINANCIAL SYSTEM v1.0 — ALL RIGHTS RESERVED</div>
            <div class="footer__classification">ПРОЕКТ: МОЛНИЯ // УРОВЕНЬ ДОПУСКА: 5</div>
        </footer>
    </div>

    <script src="/js/app.js"></script>
</body>
</html>
