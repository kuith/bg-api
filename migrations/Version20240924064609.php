<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240924064609 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE expansion (id INT AUTO_INCREMENT NOT NULL, juego_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, INDEX IDX_F0695B7213375255 (juego_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE juego (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, urimagen VARCHAR(255) DEFAULT NULL, dimensiones_caja VARCHAR(255) DEFAULT NULL, precio INT DEFAULT NULL, rango_jugadores VARCHAR(255) NOT NULL, autores VARCHAR(255) DEFAULT NULL, editorial_madre VARCHAR(255) DEFAULT NULL, editorial_local VARCHAR(255) DEFAULT NULL, UNIQUE INDEX Juego (nombre), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jugador (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, nick VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, rol VARCHAR(255) NOT NULL, UNIQUE INDEX Jugador (nombre, email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE expansion ADD CONSTRAINT FK_F0695B7213375255 FOREIGN KEY (juego_id) REFERENCES juego (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE expansion DROP FOREIGN KEY FK_F0695B7213375255');
        $this->addSql('DROP TABLE expansion');
        $this->addSql('DROP TABLE juego');
        $this->addSql('DROP TABLE jugador');
    }
}
