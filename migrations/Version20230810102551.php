<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230810102551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE edit_tricks (id INT AUTO_INCREMENT NOT NULL, old_category_id INT NOT NULL, new_category_id INT NOT NULL, updated_by_id INT NOT NULL, old_name VARCHAR(20) NOT NULL, new_name VARCHAR(20) NOT NULL, old_description VARCHAR(255) NOT NULL, new_description VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_CF54BD359E29C226 (old_category_id), INDEX IDX_CF54BD3580492C9A (new_category_id), INDEX IDX_CF54BD35896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE edit_tricks ADD CONSTRAINT FK_CF54BD359E29C226 FOREIGN KEY (old_category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE edit_tricks ADD CONSTRAINT FK_CF54BD3580492C9A FOREIGN KEY (new_category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE edit_tricks ADD CONSTRAINT FK_CF54BD35896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tricks DROP updated_at');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE edit_tricks DROP FOREIGN KEY FK_CF54BD359E29C226');
        $this->addSql('ALTER TABLE edit_tricks DROP FOREIGN KEY FK_CF54BD3580492C9A');
        $this->addSql('ALTER TABLE edit_tricks DROP FOREIGN KEY FK_CF54BD35896DBBDE');
        $this->addSql('DROP TABLE edit_tricks');
        $this->addSql('ALTER TABLE tricks ADD updated_at DATETIME DEFAULT NULL');
    }
}
