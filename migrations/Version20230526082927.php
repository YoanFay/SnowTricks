<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230526082927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(45) NOT NULL, mail VARCHAR(45) NOT NULL, password VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tricks DROP FOREIGN KEY FK_E1D902C19D86650F');
        $this->addSql('DROP INDEX IDX_E1D902C19D86650F ON tricks');
        $this->addSql('ALTER TABLE tricks ADD category_id INT NOT NULL, CHANGE user_id_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE tricks ADD CONSTRAINT FK_E1D902C1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tricks ADD CONSTRAINT FK_E1D902C112469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('CREATE INDEX IDX_E1D902C1A76ED395 ON tricks (user_id)');
        $this->addSql('CREATE INDEX IDX_E1D902C112469DE2 ON tricks (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tricks DROP FOREIGN KEY FK_E1D902C1A76ED395');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE tricks DROP FOREIGN KEY FK_E1D902C112469DE2');
        $this->addSql('DROP INDEX IDX_E1D902C1A76ED395 ON tricks');
        $this->addSql('DROP INDEX IDX_E1D902C112469DE2 ON tricks');
        $this->addSql('ALTER TABLE tricks ADD user_id_id INT NOT NULL, DROP user_id, DROP category_id');
        $this->addSql('ALTER TABLE tricks ADD CONSTRAINT FK_E1D902C19D86650F FOREIGN KEY (user_id_id) REFERENCES categories (id)');
        $this->addSql('CREATE INDEX IDX_E1D902C19D86650F ON tricks (user_id_id)');
    }
}
