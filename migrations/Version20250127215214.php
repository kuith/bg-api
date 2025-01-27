<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250127215214 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE autor (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(100) NOT NULL, nacionalidad VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE juego (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(100) NOT NULL, tipo VARCHAR(10) NOT NULL, descripcion LONGTEXT NOT NULL, min_jugadores INT NOT NULL, max_jugadores INT NOT NULL, precio DOUBLE PRECISION NOT NULL, disp_automa TINYINT(1) NOT NULL, editorial_madre VARCHAR(255) NOT NULL, editorial_local VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_F0EC403D3A909126 (nombre), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE juego_autor (juego_id INT NOT NULL, autor_id INT NOT NULL, INDEX IDX_7245734C13375255 (juego_id), INDEX IDX_7245734C14D45BBE (autor_id), PRIMARY KEY(juego_id, autor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jugador (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(50) NOT NULL, correo VARCHAR(100) NOT NULL, rol VARCHAR(10) NOT NULL, fecha_registro DATE NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_527D6F183A909126 (nombre), UNIQUE INDEX UNIQ_527D6F1877040BC9 (correo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partida (id INT AUTO_INCREMENT NOT NULL, juego_id INT NOT NULL, ganador_id INT NOT NULL, fecha DATETIME NOT NULL, INDEX IDX_A9C1580C13375255 (juego_id), INDEX IDX_A9C1580CA338CEA5 (ganador_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partidas_jugadores (partida_id INT NOT NULL, jugador_id INT NOT NULL, INDEX IDX_D77FE8D4F15A1987 (partida_id), INDEX IDX_D77FE8D4B8A54D43 (jugador_id), PRIMARY KEY(partida_id, jugador_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE juego_autor ADD CONSTRAINT FK_7245734C13375255 FOREIGN KEY (juego_id) REFERENCES juego (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE juego_autor ADD CONSTRAINT FK_7245734C14D45BBE FOREIGN KEY (autor_id) REFERENCES autor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE partida ADD CONSTRAINT FK_A9C1580C13375255 FOREIGN KEY (juego_id) REFERENCES juego (id)');
        $this->addSql('ALTER TABLE partida ADD CONSTRAINT FK_A9C1580CA338CEA5 FOREIGN KEY (ganador_id) REFERENCES jugador (id)');
        $this->addSql('ALTER TABLE partidas_jugadores ADD CONSTRAINT FK_D77FE8D4F15A1987 FOREIGN KEY (partida_id) REFERENCES partida (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE partidas_jugadores ADD CONSTRAINT FK_D77FE8D4B8A54D43 FOREIGN KEY (jugador_id) REFERENCES jugador (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE juego_autor DROP FOREIGN KEY FK_7245734C13375255');
        $this->addSql('ALTER TABLE juego_autor DROP FOREIGN KEY FK_7245734C14D45BBE');
        $this->addSql('ALTER TABLE partida DROP FOREIGN KEY FK_A9C1580C13375255');
        $this->addSql('ALTER TABLE partida DROP FOREIGN KEY FK_A9C1580CA338CEA5');
        $this->addSql('ALTER TABLE partidas_jugadores DROP FOREIGN KEY FK_D77FE8D4F15A1987');
        $this->addSql('ALTER TABLE partidas_jugadores DROP FOREIGN KEY FK_D77FE8D4B8A54D43');
        $this->addSql('DROP TABLE autor');
        $this->addSql('DROP TABLE juego');
        $this->addSql('DROP TABLE juego_autor');
        $this->addSql('DROP TABLE jugador');
        $this->addSql('DROP TABLE partida');
        $this->addSql('DROP TABLE partidas_jugadores');
    }
}
