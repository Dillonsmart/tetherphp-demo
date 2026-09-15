<?php

declare(strict_types=1);

namespace Commands;

use PDO;
use TetherPHP\framework\Commands\Command;
use TetherPHP\framework\Modules\Env;

/**
 * Applies database/schema.sql to the database the application uses.
 *
 * There is no migration tool. The schema is one file, every statement in it
 * is idempotent, and applying it is one command — which is all a small
 * application needs, and it keeps the whole database story readable.
 */
class DbSchemaCommand extends Command
{
    public string $command = 'db:schema';

    public string $description = 'Apply database/schema.sql to the configured database';

    public function execute(): int
    {
        $dsn = Env::current()->get('DB_DSN') ?: 'sqlite:' . project_root() . '/storage/notes.sqlite';

        $db = new PDO($dsn, options: [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $db->exec((string) file_get_contents(project_root() . '/database/schema.sql'));

        $this->info("Schema applied to {$dsn}");

        return self::COMMAND_SUCCESS;
    }
}
