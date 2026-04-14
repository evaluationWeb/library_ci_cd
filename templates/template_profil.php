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
        <?php $errors = $data["errors"] ?? []; ?>
        <?php $form = $data["form"] ?? []; ?>

        <article>
            <header>
                <strong>Profil : <?= htmlspecialchars(($data["user"]["firstname"] ?? "") . ' ' . ($data["user"]["lastname"] ?? "")) ?></strong>
            </header>
            <p>Prenom : <?= htmlspecialchars($data["user"]["firstname"] ?? "") ?></p>
            <p>Nom : <?= htmlspecialchars($data["user"]["lastname"] ?? "") ?></p>
            <p>Email : <?= htmlspecialchars($data["user"]["email"] ?? "") ?></p>
            <footer><strong>Role : <?= htmlspecialchars($data["user"]["roles"][0] ?? "") ?></strong></footer>
        </article>

        <article>
            <header>
                <h2>Mettre a jour le profil</h2>
            </header>

            <?php if (!empty($data["msg"])) : ?>
                <article><?= htmlspecialchars((string) $data["msg"], ENT_QUOTES) ?></article>
            <?php endif; ?>

            <?php if (!empty($errors["_form"])) : ?>
                <article aria-invalid="true"><?= htmlspecialchars((string) $errors["_form"], ENT_QUOTES) ?></article>
            <?php endif; ?>

            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string) ($data["csrf_token"] ?? ""), ENT_QUOTES) ?>">

                <div class="grid">
                    <div>
                        <label for="firstname">Prenom</label>
                        <input type="text" id="firstname" name="firstname" value="<?= htmlspecialchars((string) ($form["firstname"] ?? ""), ENT_QUOTES) ?>" aria-invalid="<?= !empty($errors["firstname"]) ? 'true' : 'false' ?>">
                        <small><?= htmlspecialchars((string) ($errors["firstname"] ?? ""), ENT_QUOTES) ?></small>
                    </div>
                    <div>
                        <label for="lastname">Nom</label>
                        <input type="text" id="lastname" name="lastname" value="<?= htmlspecialchars((string) ($form["lastname"] ?? ""), ENT_QUOTES) ?>" aria-invalid="<?= !empty($errors["lastname"]) ? 'true' : 'false' ?>">
                        <small><?= htmlspecialchars((string) ($errors["lastname"] ?? ""), ENT_QUOTES) ?></small>
                    </div>
                </div>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars((string) ($form["email"] ?? ""), ENT_QUOTES) ?>" aria-invalid="<?= !empty($errors["email"]) ? 'true' : 'false' ?>">
                <small><?= htmlspecialchars((string) ($errors["email"] ?? ""), ENT_QUOTES) ?></small>

                <div class="grid">
                    <div>
                        <label for="password">Nouveau mot de passe</label>
                        <input type="password" id="password" name="password" aria-invalid="<?= !empty($errors["password"]) ? 'true' : 'false' ?>">
                        <small>Laisse vide pour conserver le mot de passe actuel.</small>
                    </div>
                    <div>
                        <label for="confirm-password">Confirmation</label>
                        <input type="password" id="confirm-password" name="confirm-password" aria-invalid="<?= !empty($errors["confirm-password"]) ? 'true' : 'false' ?>">
                        <small><?= htmlspecialchars((string) ($errors["confirm-password"] ?? ""), ENT_QUOTES) ?></small>
                    </div>
                </div>

                <button type="submit" name="submit">Mettre a jour</button>
            </form>
        </article>
    </main>
    <?php $this->renderComponent("footer"); ?>
</body>
</html>
