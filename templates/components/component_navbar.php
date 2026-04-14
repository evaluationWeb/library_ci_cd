<header class="container">
    <nav>
        <ul>
            <li><strong>Library</strong></li>
        </ul>
        <ul>
            <li><a href="/">Accueil</a></li>
            <?php if (isset($_SESSION["user"]["id"])) : ?>
                <li><a href="/category/all">Categories</a></li>
                <li><a href="/book/all">Livres</a></li>
                <li><a href="/lending/all">Emprunts</a></li>
                <li><a href="/book/add">Ajouter livre</a></li>
                <li><a href="/profil">Profil</a></li>
                <li><a href="/logout">Deconnexion</a></li>
            <?php else : ?>
                <li><a href="/login">Connexion</a></li>
                <li><a href="/register">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
