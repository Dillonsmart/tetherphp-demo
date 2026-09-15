<?php
/**
 * What Responders\Note\Show hands this view.
 *
 * @var string               $id
 * @var array<string, mixed> $attributes
 */
$pageTitle = $attributes['title'];
include views_dir() . '/partials/header.php';
?>

<main class="max-w-2xl mx-auto px-6 py-16">
    <p class="mb-6"><a href="/notes" class="text-sm underline underline-offset-4 hover:opacity-70">&larr; All notes</a></p>

    <h1 class="text-3xl mb-2"><?php echo htmlspecialchars((string) $attributes['title']); ?></h1>
    <p class="text-sm opacity-50 mb-8">Updated <?php echo htmlspecialchars((string) $attributes['updated_at']); ?></p>

    <div class="whitespace-pre-wrap leading-relaxed mb-10"><?php echo htmlspecialchars((string) $attributes['body']); ?></div>

    <div class="flex items-center gap-6">
        <a href="/notes/<?php echo htmlspecialchars($id); ?>/edit" class="underline underline-offset-4 hover:opacity-70">Edit</a>

        <?php
        /*
         * A browser form sends GET or POST. The hidden _method field asks for
         * DELETE, and Middleware\OverridesMethod in routes/middleware.php
         * rewrites the verb before routing. Leave that middleware out and this
         * form posts to a route that does not exist.
         */
        ?>
        <form method="post" action="/notes/<?php echo htmlspecialchars($id); ?>" onsubmit="return confirm('Delete this note?');">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
            <button type="submit" class="underline underline-offset-4 hover:opacity-70 text-red-700">Delete</button>
        </form>
    </div>
</main>

<?php include views_dir() . '/partials/footer.php'; ?>
