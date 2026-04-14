<?php $categories = $data["categories"] ?? []; ?>
<?php if (empty($categories)) : ?>
    <p>Aucune categorie en base.</p>
<?php else : ?>
    <ul>
        <?php foreach ($categories as $category) : ?>
            <li><?= htmlspecialchars($category->getName()) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
