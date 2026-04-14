<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css"
    >
    <link rel="stylesheet" href="/assets/style/site.css">
    <title><?= $title ?? "" ?></title>
</head>
<body>
    <?php $this->renderComponent("navbar"); ?>
    <main class="container">
        <article>
            <header>
                <h1>Se connecter</h1>
            </header>
            <?php $errors = $data["errors"] ?? []; ?>
            <?php if (!empty($errors["_form"])) : ?>
                <article aria-invalid="true"><?= htmlspecialchars((string) $errors["_form"], ENT_QUOTES) ?></article>
            <?php endif; ?>
            <form action="" method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string) ($data["csrf_token"] ?? ""), ENT_QUOTES) ?>">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" aria-invalid="<?= !empty($errors["email"]) ? 'true' : 'false' ?>">
                <small><?= htmlspecialchars((string) ($errors["email"] ?? ""), ENT_QUOTES) ?></small>

                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" aria-invalid="<?= !empty($errors["password"]) ? 'true' : 'false' ?>">
                <small><?= htmlspecialchars((string) ($errors["password"] ?? ""), ENT_QUOTES) ?></small>

                <button type="submit" name="submit">Se connecter</button>
            </form>

            <footer>
                <small>Pas encore de compte ? <a href="/register">Creer un compte</a></small>
            </footer>
        </article>
    </main>
    <?php $this->renderComponent("footer"); ?>
</body>
</html>
