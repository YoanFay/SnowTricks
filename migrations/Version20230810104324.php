<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230810104324 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE edit_tricks ADD trick_id INT NULL');
        $this->addSql('ALTER TABLE edit_tricks ADD CONSTRAINT FK_CF54BD35B281BE2E FOREIGN KEY (trick_id) REFERENCES tricks (id)');
        $this->addSql('CREATE INDEX IDX_CF54BD35B281BE2E ON edit_tricks (trick_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE edit_tricks DROP FOREIGN KEY FK_CF54BD35B281BE2E');
        $this->addSql('DROP INDEX IDX_CF54BD35B281BE2E ON edit_tricks');
        $this->addSql('ALTER TABLE edit_tricks DROP trick_id');
    }
}
