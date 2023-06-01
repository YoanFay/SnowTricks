<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230526151616 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rights (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, level INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD rights_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B196EE6E FOREIGN KEY (rights_id) REFERENCES rights (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649B196EE6E ON user (rights_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649B196EE6E');
        $this->addSql('DROP TABLE rights');
        $this->addSql('DROP INDEX IDX_8D93D649B196EE6E ON user');
        $this->addSql('ALTER TABLE user DROP rights_id');
    }
}
