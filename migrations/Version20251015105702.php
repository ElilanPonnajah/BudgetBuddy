<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251015105702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4C9D86650F');
        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4C9777D11E');
        $this->addSql('DROP INDEX IDX_EAA81A4C9777D11E ON transactions');
        $this->addSql('DROP INDEX IDX_EAA81A4C9D86650F ON transactions');
        $this->addSql('ALTER TABLE transactions ADD user_id INT NOT NULL, ADD category_id INT NOT NULL, DROP user_id_id, DROP category_id_id');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4CA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C12469DE2 FOREIGN KEY (category_id) REFERENCES catagories (id)');
        $this->addSql('CREATE INDEX IDX_EAA81A4CA76ED395 ON transactions (user_id)');
        $this->addSql('CREATE INDEX IDX_EAA81A4C12469DE2 ON transactions (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4CA76ED395');
        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4C12469DE2');
        $this->addSql('DROP INDEX IDX_EAA81A4CA76ED395 ON transactions');
        $this->addSql('DROP INDEX IDX_EAA81A4C12469DE2 ON transactions');
        $this->addSql('ALTER TABLE transactions ADD user_id_id INT NOT NULL, ADD category_id_id INT NOT NULL, DROP user_id, DROP category_id');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C9D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C9777D11E FOREIGN KEY (category_id_id) REFERENCES catagories (id)');
        $this->addSql('CREATE INDEX IDX_EAA81A4C9777D11E ON transactions (category_id_id)');
        $this->addSql('CREATE INDEX IDX_EAA81A4C9D86650F ON transactions (user_id_id)');
    }
}
