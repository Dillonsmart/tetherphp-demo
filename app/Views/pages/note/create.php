<?php
/**
 * What Responders\Note\Create — or Responders\Note\Store, refusing a write — hands this view.
 *
 * @var string                $id
 * @var array<string, mixed>  $attributes
 * @var array<string, string> $errors
 */
$pageTitle = 'New note';
include views_dir() . '/partials/header.php';
?>

<main class="max-w-2xl mx-auto px-6 py-16">
    <p class="mb-6"><a href="/notes" class="text-sm underline underline-offset-4 hover:opacity-70">&larr; All notes</a></p>
    <h1 class="text-3xl mb-8">New note</h1>

    <?php include views_dir() . '/partials/note-form.php'; ?>
</main>

<?php include views_dir() . '/partials/footer.php'; ?>
