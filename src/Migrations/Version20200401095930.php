<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200401095930 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE bd_author (bd_id INT NOT NULL, author_id INT NOT NULL, INDEX IDX_19CDF3F8894AF46 (bd_id), INDEX IDX_19CDF3F8F675F31B (author_id), PRIMARY KEY(bd_id, author_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bd_author ADD CONSTRAINT FK_19CDF3F8894AF46 FOREIGN KEY (bd_id) REFERENCES bd (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bd_author ADD CONSTRAINT FK_19CDF3F8F675F31B FOREIGN KEY (author_id) REFERENCES author (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bd_author DROP FOREIGN KEY FK_19CDF3F8F675F31B');
        $this->addSql('DROP TABLE bd_author');
        $this->addSql('DROP TABLE author');
    }
}
