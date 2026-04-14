<?php $lendings = $data["lendings"] ?? []; ?>
<?php if (empty($lendings)) : ?>
    <p>Aucun emprunt en base.</p>
<?php else : ?>
    <div class="grid">
        <?php foreach ($lendings as $lending) : ?>
            <article>
                <header>
                    <strong><?= htmlspecialchars($lending->getBook()->getTitle()) ?></strong>
                </header>

                <p>
                    Emprunte par
                    <?= htmlspecialchars($lending->getUser()->getFirstname() . ' ' . $lending->getUser()->getLastname()) ?>
                </p>
                <p>
                    <small>
                        Du <?= htmlspecialchars($lending->getLendAt()->format('Y-m-d H:i')) ?><br>
                        Au <?= htmlspecialchars($lending->getReturnAt()->format('Y-m-d H:i')) ?><br>
                        Limite : <?= htmlspecialchars($lending->getMandatoryAt()->format('Y-m-d')) ?>
                    </small>
                </p>

                <?php if (isset($_SESSION["user"]["id"])) : ?>
                    <footer>
                        <a
                            href="/lending/return/<?= $lending->getId() ?>"
                            role="button"
                            class="secondary"
                            onclick="return confirm('Confirmer le retour de ce livre ?');"
                        >
                            Retourner
                        </a>
                    </footer>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
