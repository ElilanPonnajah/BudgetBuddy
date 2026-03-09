<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251104091548 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE catagories DROP FOREIGN KEY FK_CEBC627B9D86650F');
        $this->addSql('DROP INDEX IDX_CEBC627B9D86650F ON catagories');
        $this->addSql('ALTER TABLE catagories CHANGE user_id_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE catagories ADD CONSTRAINT FK_CEBC627BA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_CEBC627BA76ED395 ON catagories (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE catagories DROP FOREIGN KEY FK_CEBC627BA76ED395');
        $this->addSql('DROP INDEX IDX_CEBC627BA76ED395 ON catagories');
        $this->addSql('ALTER TABLE catagories CHANGE user_id user_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE catagories ADD CONSTRAINT FK_CEBC627B9D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_CEBC627B9D86650F ON catagories (user_id_id)');
    }
}
