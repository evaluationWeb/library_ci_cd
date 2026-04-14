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
        <header>
            <h1>Liste des categories</h1>
            <?php if (isset($_SESSION["user"]["id"])) : ?>
                <p>
                    <a href="/category/add" role="button">Ajouter une categorie</a>
                </p>
            <?php endif; ?>
        </header>
        <?php $this->renderComponent("all_categories", $data); ?>
    </main>
    <?php $this->renderComponent("footer"); ?>
</body>
</html>
