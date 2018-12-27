<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181226125714 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE file_manager (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, filename VARCHAR(255) NOT NULL, origin_name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, file_size INT NOT NULL, file_mime VARCHAR(255) NOT NULL, status SMALLINT NOT NULL, created DATETIME NOT NULL, handler VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD image_fid INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E661511CB7C FOREIGN KEY (image_fid) REFERENCES file_manager (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_23A0E661511CB7C ON article (image_fid)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E661511CB7C');
        $this->addSql('DROP TABLE file_manager');
        $this->addSql('DROP INDEX UNIQ_23A0E661511CB7C ON article');
        $this->addSql('ALTER TABLE article DROP image_fid');
    }
}
