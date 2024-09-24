<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240924193647 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE expansion ADD juego_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE expansion ADD CONSTRAINT FK_F0695B7213375255 FOREIGN KEY (juego_id) REFERENCES juego (id)');
        $this->addSql('CREATE INDEX IDX_F0695B7213375255 ON expansion (juego_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE expansion DROP FOREIGN KEY FK_F0695B7213375255');
        $this->addSql('DROP INDEX IDX_F0695B7213375255 ON expansion');
        $this->addSql('ALTER TABLE expansion DROP juego_id');
    }
}
