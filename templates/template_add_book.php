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
                <h1><?= ($data["mode"] ?? "create") === "edit" ? "Modifier un livre" : "Ajouter un livre" ?></h1>
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

            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($data["csrf_token"] ?? "") ?>">
                <?php $form = $data["form"] ?? []; ?>
                <?php $selectedCategories = $form["categories"] ?? []; ?>

                <?php if (($data["mode"] ?? "create") === "edit" && !empty($data["book"]) && $data["book"]->getCover() !== "") : ?>
                    <p>Couverture actuelle :</p>
                    <img
                        src="<?= htmlspecialchars($data["book"]->getCover()) ?>"
                        alt="Couverture actuelle"
                        style="width: 160px; max-height: 220px; object-fit: cover; margin-bottom: 1rem;"
                    >
                <?php endif; ?>

                <div class="grid">
                    <div>
                        <label for="title">Titre</label>
                        <input type="text" id="title" name="title" value="<?= htmlspecialchars($form["title"] ?? "") ?>" aria-invalid="<?= !empty($data["errors"]["title"]) ? 'true' : 'false' ?>">
                        <?php if (!empty($data["errors"]["title"])) : ?>
                            <small><?= htmlspecialchars($data["errors"]["title"]) ?></small>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="author">Auteur</label>
                        <input type="text" id="author" name="author" value="<?= htmlspecialchars($form["author"] ?? "") ?>" aria-invalid="<?= !empty($data["errors"]["author"]) ? 'true' : 'false' ?>">
                        <?php if (!empty($data["errors"]["author"])) : ?>
                            <small><?= htmlspecialchars($data["errors"]["author"]) ?></small>
                        <?php endif; ?>
                    </div>
                </div>

                <label for="description">Description</label>
                <textarea id="description" name="description" aria-invalid="<?= !empty($data["errors"]["description"]) ? 'true' : 'false' ?>"><?= htmlspecialchars($form["description"] ?? "") ?></textarea>
                <?php if (!empty($data["errors"]["description"])) : ?>
                    <small><?= htmlspecialchars($data["errors"]["description"]) ?></small>
                <?php endif; ?>

                <div class="grid">
                    <div>
                        <label for="publish_at">Date de publication</label>
                        <input type="date" id="publish_at" name="publish_at" value="<?= htmlspecialchars($form["publish_at"] ?? "") ?>" aria-invalid="<?= !empty($data["errors"]["publish_at"]) ? 'true' : 'false' ?>">
                        <?php if (!empty($data["errors"]["publish_at"])) : ?>
                            <small><?= htmlspecialchars($data["errors"]["publish_at"]) ?></small>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="cover">Couverture</label>
                        <input type="file" id="cover" name="cover" accept=".png,.jpg,.jpeg,.webp" aria-invalid="<?= !empty($data["errors"]["cover"]) ? 'true' : 'false' ?>">
                        <?php if (!empty($data["errors"]["cover"])) : ?>
                            <small><?= htmlspecialchars($data["errors"]["cover"]) ?></small>
                        <?php endif; ?>
                    </div>
                </div>

                <label>
                    <input type="checkbox" name="available" <?= isset($form["available"]) || $form === [] ? "checked" : "" ?>>
                    Disponible
                </label>

                <label for="categories">Categories</label>
                <select id="categories" name="categories[]" multiple size="6">
                    <?php foreach (($data["categories"] ?? []) as $category) : ?>
                        <option
                            value="<?= $category->getId() ?>"
                            <?= in_array((string) $category->getId(), array_map('strval', (array) $selectedCategories), true) ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($category->getName()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small>Maintiens Ctrl ou Cmd pour selectionner plusieurs categories.</small>

                <button type="submit" name="submit"><?= ($data["mode"] ?? "create") === "edit" ? "Modifier" : "Ajouter" ?></button>
            </form>
        </article>
    </main>
    <?php $this->renderComponent("footer"); ?>
</body>
</html>
