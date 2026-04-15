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
                <h1>Ajouter une categorie</h1>
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

                <label for="name">Nom</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="<?= htmlspecialchars($form["name"] ?? "") ?>"
                    aria-invalid="<?= !empty($data["errors"]["name"]) ? 'true' : 'false' ?>"
                >
                <?php if (!empty($data["errors"]["name"])) : ?>
                    <small><?= htmlspecialchars($data["errors"]["name"]) ?></small>
                <?php endif; ?>

                <button type="submit" name="submit">Ajouter</button>
            </form>
        </article>
    </main>
    <?php $this->renderComponent("footer"); ?>
</body>
</html>
