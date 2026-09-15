<?php
/**
 * Set by the page that includes this partial — create.php and edit.php share
 * one form, and the Responder that refused a write re-renders it with the
 * attributes that were sent and the errors that refused them.
 *
 * @var string                $id          '' for a note that does not exist yet
 * @var array<string, mixed>  $attributes  title and body
 * @var array<string, string> $errors      field => message; empty when nothing is wrong
 */

/*
 * A browser form can only send GET or POST. An edit declares the verb it
 * means in _method, and Middleware\OverridesMethod turns the POST into a PUT
 * before routing.
 */
$action = $id === '' ? '/notes' : '/notes/' . $id;
?>
<form method="post" action="<?php echo htmlspecialchars($action); ?>" class="space-y-6">
    <?php if ($id !== ''): ?>
        <input type="hidden" name="_method" value="PUT">
    <?php endif; ?>

    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">

    <div>
        <label for="title" class="block mb-1">Title</label>
        <input
            id="title"
            name="title"
            value="<?php echo htmlspecialchars((string) ($attributes['title'] ?? '')); ?>"
            class="w-full border px-3 py-2 bg-transparent"
            autofocus
        >
        <?php if (isset($errors['title'])): ?>
            <p class="text-sm text-red-700 mt-1"><?php echo htmlspecialchars($errors['title']); ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="body" class="block mb-1">Body</label>
        <textarea id="body" name="body" rows="12" class="w-full border px-3 py-2 bg-transparent"><?php echo htmlspecialchars((string) ($attributes['body'] ?? '')); ?></textarea>
        <?php if (isset($errors['body'])): ?>
            <p class="text-sm text-red-700 mt-1"><?php echo htmlspecialchars($errors['body']); ?></p>
        <?php endif; ?>
    </div>

    <div class="flex items-center gap-6">
        <button type="submit" class="border px-4 py-2 hover:opacity-70">Save</button>
        <a href="<?php echo $id === '' ? '/notes' : '/notes/' . htmlspecialchars($id); ?>" class="text-sm underline underline-offset-4 hover:opacity-70">Cancel</a>
    </div>
</form>
