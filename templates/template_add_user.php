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
                <h1>Ajouter un utilisateur</h1>
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

                <div class="grid">
                    <div>
                        <label for="firstname">Prenom</label>
                        <input type="text" id="firstname" name="firstname" aria-invalid="<?= !empty($data["errors"]["firstname"]) ? 'true' : 'false' ?>">
                        <?php if (!empty($data["errors"]["firstname"])) : ?>
                            <small><?= htmlspecialchars($data["errors"]["firstname"]) ?></small>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="lastname">Nom</label>
                        <input type="text" id="lastname" name="lastname" aria-invalid="<?= !empty($data["errors"]["lastname"]) ? 'true' : 'false' ?>">
                        <?php if (!empty($data["errors"]["lastname"])) : ?>
                            <small><?= htmlspecialchars($data["errors"]["lastname"]) ?></small>
                        <?php endif; ?>
                    </div>
                </div>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" aria-invalid="<?= !empty($data["errors"]["email"]) ? 'true' : 'false' ?>">
                <?php if (!empty($data["errors"]["email"])) : ?>
                    <small><?= htmlspecialchars($data["errors"]["email"]) ?></small>
                <?php endif; ?>

                <div class="grid">
                    <div>
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" aria-invalid="<?= !empty($data["errors"]["password"]) ? 'true' : 'false' ?>">
                        <?php if (!empty($data["errors"]["password"])) : ?>
                            <small><?= htmlspecialchars($data["errors"]["password"]) ?></small>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="roles">Role</label>
                        <select id="roles" name="roles">
                            <option value="ROLE_USER">ROLE_USER</option>
                            <option value="ROLE_ADMIN">ROLE_ADMIN</option>
                        </select>
                    </div>
                </div>

                <button type="submit" name="submit">Ajouter</button>
            </form>
        </article>
    </main>
    <?php $this->renderComponent("footer"); ?>
</body>
</html>
