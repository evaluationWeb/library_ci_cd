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
            <h1>Bienvenue sur le site Library</h1>
            <p>Gestion simple des categories, livres, utilisateurs et emprunts.</p>
        </header>

        <section class="grid">
            <article>
                <header>Categories</header>
                <strong><?= (int) ($data["categoriesCount"] ?? 0) ?></strong>
            </article>
            <article>
                <header>Livres</header>
                <strong><?= (int) ($data["booksCount"] ?? 0) ?></strong>
            </article>
            <article>
                <header>Utilisateurs</header>
                <strong><?= (int) ($data["usersCount"] ?? 0) ?></strong>
            </article>
            <article>
                <header>Emprunts</header>
                <strong><?= (int) ($data["lendingsCount"] ?? 0) ?></strong>
            </article>
        </section>
    </main>
    <?php $this->renderComponent("footer"); ?>
</body>
</html>
