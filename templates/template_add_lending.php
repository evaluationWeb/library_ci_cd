<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css"
    >
    <link rel="stylesheet" href="/assets/style/site.css">
</head>
<body>
    <?php $this->renderComponent("navbar"); ?>
    <main class="container">
        <article>
            <header>
                <h1>Ajouter un emprunt</h1>
            </header>

            <?php if (!empty($data["msg"])) : ?>
                <article>
                    <?= htmlspecialchars($data["msg"]) ?>
                </article>
            <?php endif; ?>

            <?php if (!empty($data["errors"]["_form"])) : ?>
                <article aria-invalid="true">
                    <?= htmlspecialchars($data["errors"]["_form"]) ?>
                </article>
            <?php endif; ?>

            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($data["csrf_token"] ?? "") ?>">
                <?php $form = $data["form"] ?? []; ?>

                <div class="grid">
                    <div>
                        <label for="book_id">Livre</label>
                        <select id="book_id" name="book_id" aria-invalid="<?= !empty($data["errors"]["book_id"]) ? 'true' : 'false' ?>">
                            <option value="">Choisir un livre</option>
                            <?php foreach (($data["books"] ?? []) as $book) : ?>
                                <option value="<?= $book->getId() ?>" <?= (string) ($form["book_id"] ?? "") === (string) $book->getId() ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($book->getTitle()) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (!empty($data["errors"]["book_id"])) : ?>
                            <small><?= htmlspecialchars($data["errors"]["book_id"]) ?></small>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="grid">
                    <div>
                        <label for="return_at">Date de retour</label>
                        <input type="datetime-local" id="return_at" name="return_at" value="<?= htmlspecialchars($form["return_at"] ?? "") ?>" aria-invalid="<?= !empty($data["errors"]["return_at"]) ? 'true' : 'false' ?>">
                        <?php if (!empty($data["errors"]["return_at"])) : ?>
                            <small><?= htmlspecialchars($data["errors"]["return_at"]) ?></small>
                        <?php endif; ?>
                    </div>
                </div>

                <label for="mandatory_at">Date limite</label>
                <input type="date" id="mandatory_at" name="mandatory_at" value="<?= htmlspecialchars($form["mandatory_at"] ?? "") ?>" aria-invalid="<?= !empty($data["errors"]["mandatory_at"]) ? 'true' : 'false' ?>">
                <?php if (!empty($data["errors"]["mandatory_at"])) : ?>
                    <small><?= htmlspecialchars($data["errors"]["mandatory_at"]) ?></small>
                <?php endif; ?>

                <button type="submit" name="submit">Ajouter</button>
            </form>
        </article>
    </main>
    <?php $this->renderComponent("footer"); ?>
</body>
</html>
