<?php $books = $data["books"] ?? []; ?>
<?php if (empty($books)) : ?>
    <p>Aucun livre en base.</p>
<?php else : ?>
    <div class="grid">
        <?php foreach ($books as $book) : ?>
            <article>
                <?php if (!empty($book->getCover())) : ?>
                    <img
                        src="<?= htmlspecialchars($book->getCover()) ?>"
                        alt="Couverture de <?= htmlspecialchars($book->getTitle()) ?>"
                        style="width: 100%; max-height: 260px; object-fit: cover; margin-bottom: 1rem;"
                    >
                <?php endif; ?>

                <header>
                    <strong class="book-title"><?= htmlspecialchars($book->getTitle()) ?></strong>
                </header>

                <p>
                    par <strong><?= htmlspecialchars($book->getAuthor()) ?></strong><br>
                    <small class="availability-badge <?= $book->isAvailable() ? 'is-available' : 'is-unavailable' ?>">
                        <?= $book->isAvailable() ? 'Disponible' : 'Indisponible' ?>
                    </small>
                </p>

                <?php if ($book->getCategories() !== []) : ?>
                    <div class="category-list">
                        <?php foreach ($book->getCategories() as $category) : ?>
                            <span class="category-pill"><?= htmlspecialchars($category->getName()) ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <p><?= htmlspecialchars($book->getDescription()) ?></p>

                <?php if (isset($_SESSION["user"]["id"])) : ?>
                    <footer>
                        <?php if ($book->isAvailable()) : ?>
                            <a href="/lending/add?book_id=<?= $book->getId() ?>" role="button">Emprunter</a>
                        <?php endif; ?>
                        <a href="/book/edit/<?= $book->getId() ?>" role="button" class="secondary">Modifier</a>
                        <a href="/book/delete/<?= $book->getId() ?>" role="button" class="contrast" onclick="return confirm('Supprimer ce livre ?');">Supprimer</a>
                    </footer>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
