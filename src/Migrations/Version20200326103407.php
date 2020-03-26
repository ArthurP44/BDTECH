<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200326103407 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE bd_collection (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bd_author (bd_id INT NOT NULL, author_id INT NOT NULL, INDEX IDX_19CDF3F8894AF46 (bd_id), INDEX IDX_19CDF3F8F675F31B (author_id), PRIMARY KEY(bd_id, author_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bd_author ADD CONSTRAINT FK_19CDF3F8894AF46 FOREIGN KEY (bd_id) REFERENCES bd (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bd_author ADD CONSTRAINT FK_19CDF3F8F675F31B FOREIGN KEY (author_id) REFERENCES author (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE collection');
        $this->addSql('ALTER TABLE bd ADD category_id INT NOT NULL, ADD collection_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bd ADD CONSTRAINT FK_5CCDBE9B12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE bd ADD CONSTRAINT FK_5CCDBE9B514956FD FOREIGN KEY (collection_id) REFERENCES bd_collection (id)');
        $this->addSql('CREATE INDEX IDX_5CCDBE9B12469DE2 ON bd (category_id)');
        $this->addSql('CREATE INDEX IDX_5CCDBE9B514956FD ON bd (collection_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bd DROP FOREIGN KEY FK_5CCDBE9B514956FD');
        $this->addSql('CREATE TABLE collection (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE bd_collection');
        $this->addSql('DROP TABLE bd_author');
        $this->addSql('ALTER TABLE bd DROP FOREIGN KEY FK_5CCDBE9B12469DE2');
        $this->addSql('DROP INDEX IDX_5CCDBE9B12469DE2 ON bd');
        $this->addSql('DROP INDEX IDX_5CCDBE9B514956FD ON bd');
        $this->addSql('ALTER TABLE bd DROP category_id, DROP collection_id');
    }
}
