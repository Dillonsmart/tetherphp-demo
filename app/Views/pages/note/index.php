<?php
/**
 * What Responders\Note\Index hands this view.
 *
 * @var list<array<string, mixed>> $records
 * @var array<string, mixed>       $query
 */
$pageTitle = 'Notes';
include views_dir() . '/partials/header.php';
?>

<main class="max-w-2xl mx-auto px-6 py-16">
    <div class="flex items-baseline justify-between mb-10">
        <h1 class="text-3xl">Notes</h1>
        <a href="/notes/create" class="underline underline-offset-4 hover:opacity-70">New note</a>
    </div>

    <?php if ($records === []): ?>
        <p class="opacity-70">Nothing here yet. <a href="/notes/create" class="underline underline-offset-4">Write the first one.</a></p>
    <?php else: ?>
        <ul class="space-y-6">
            <?php foreach ($records as $record): ?>
                <li>
                    <a href="/notes/<?php echo htmlspecialchars((string) $record['id']); ?>" class="text-xl underline underline-offset-4 hover:opacity-70">
                        <?php echo htmlspecialchars((string) $record['title']); ?>
                    </a>
                    <p class="opacity-70 mt-1"><?php echo htmlspecialchars((string) $record['excerpt']); ?></p>
                    <p class="text-sm opacity-50 mt-1"><?php echo htmlspecialchars((string) $record['updated_at']); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</main>

<?php include views_dir() . '/partials/footer.php'; ?>
