<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201129012111 extends AbstractMigration
{
    public function getDescription() : string
    {
        return "Create `media` table";
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("
            DROP TABLE IF EXISTS `media`;
            CREATE TABLE IF NOT EXISTS `media` (
              `id` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL,
              `type` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
              `format` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
              `ext` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
              `size` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
              `width` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
              `height` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
              `hash` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
              `token` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
              `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            COMMIT;
        ");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql("
            DROP TABLE IF EXISTS `media`;
        ");
    }
}
