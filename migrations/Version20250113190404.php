<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250113190404 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE juego DROP FOREIGN KEY FK_F0EC403D6E5E864D');
        $this->addSql('DROP INDEX IDX_F0EC403D6E5E864D ON juego');
        $this->addSql('ALTER TABLE juego ADD juego_base VARCHAR(255) DEFAULT NULL, DROP juego_base_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE juego ADD juego_base_id INT NOT NULL, DROP juego_base');
        $this->addSql('ALTER TABLE juego ADD CONSTRAINT FK_F0EC403D6E5E864D FOREIGN KEY (juego_base_id) REFERENCES juego (id)');
        $this->addSql('CREATE INDEX IDX_F0EC403D6E5E864D ON juego (juego_base_id)');
    }
}
