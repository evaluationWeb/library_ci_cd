<?php $users = $data["users"] ?? []; ?>
<?php if (empty($users)) : ?>
    <p>Aucun utilisateur en base.</p>
<?php else : ?>
    <ul>
        <?php foreach ($users as $user) : ?>
            <li>
                <?= htmlspecialchars($user->getFirstname() . ' ' . $user->getLastname()) ?>
                - <?= htmlspecialchars($user->getEmail()) ?>
                - <?= htmlspecialchars($user->getRoles()) ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
