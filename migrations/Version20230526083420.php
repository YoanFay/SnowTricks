<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230526083420 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, tricks_id INT NOT NULL, user_id INT NOT NULL, message VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_67F068BC3B153154 (tricks_id), INDEX IDX_67F068BCA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, tricks_id INT NOT NULL, name VARCHAR(45) NOT NULL, type VARCHAR(10) NOT NULL, INDEX IDX_E01FBE6A3B153154 (tricks_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video (id INT AUTO_INCREMENT NOT NULL, tricks_id INT NOT NULL, link VARCHAR(255) NOT NULL, INDEX IDX_7CC7DA2C3B153154 (tricks_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC3B153154 FOREIGN KEY (tricks_id) REFERENCES tricks (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A3B153154 FOREIGN KEY (tricks_id) REFERENCES tricks (id)');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2C3B153154 FOREIGN KEY (tricks_id) REFERENCES tricks (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC3B153154');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCA76ED395');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A3B153154');
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2C3B153154');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE video');
    }
}
