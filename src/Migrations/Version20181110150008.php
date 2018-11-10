<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181110150008 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD roles JSON NOT NULL, DROP first_name, DROP last_name, DROP created_at, CHANGE email email VARCHAR(180) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD first_name VARCHAR(45) NOT NULL COLLATE utf8mb4_unicode_ci, ADD last_name VARCHAR(45) NOT NULL COLLATE utf8mb4_unicode_ci, ADD created_at DATETIME NOT NULL, DROP roles, CHANGE email email VARCHAR(150) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE password password VARCHAR(100) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
