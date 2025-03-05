<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250304174427 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE autor_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE juego_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE jugador_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE partida_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE autor (id INT NOT NULL, nombre VARCHAR(100) NOT NULL, nacionalidad VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE juego (id INT NOT NULL, juego_base_id INT DEFAULT NULL, nombre VARCHAR(100) NOT NULL, base_expansion VARCHAR(255) NOT NULL, tipo TEXT NOT NULL, anio_publicacion INT NOT NULL, descripcion TEXT NOT NULL, min_jugadores INT NOT NULL, max_jugadores INT NOT NULL, precio DOUBLE PRECISION NOT NULL, disp_automa BOOLEAN NOT NULL, editorial_madre VARCHAR(255) NOT NULL, editorial_local VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F0EC403D3A909126 ON juego (nombre)');
        $this->addSql('CREATE INDEX IDX_F0EC403D6E5E864D ON juego (juego_base_id)');
        $this->addSql('CREATE TABLE juego_autor (juego_id INT NOT NULL, autor_id INT NOT NULL, PRIMARY KEY(juego_id, autor_id))');
        $this->addSql('CREATE INDEX IDX_7245734C13375255 ON juego_autor (juego_id)');
        $this->addSql('CREATE INDEX IDX_7245734C14D45BBE ON juego_autor (autor_id)');
        $this->addSql('CREATE TABLE jugador (id INT NOT NULL, nombre VARCHAR(50) NOT NULL, correo VARCHAR(100) NOT NULL, rol VARCHAR(10) NOT NULL, fecha_registro DATE NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_527D6F183A909126 ON jugador (nombre)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_527D6F1877040BC9 ON jugador (correo)');
        $this->addSql('CREATE TABLE partida (id INT NOT NULL, juego_id INT NOT NULL, fecha TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A9C1580C13375255 ON partida (juego_id)');
        $this->addSql('CREATE TABLE partida_jugador (partida_id INT NOT NULL, jugador_id INT NOT NULL, PRIMARY KEY(partida_id, jugador_id))');
        $this->addSql('CREATE INDEX IDX_C6230C0CF15A1987 ON partida_jugador (partida_id)');
        $this->addSql('CREATE INDEX IDX_C6230C0CB8A54D43 ON partida_jugador (jugador_id)');
        $this->addSql('CREATE TABLE partidas_jugadores (partida_id INT NOT NULL, jugador_id INT NOT NULL, PRIMARY KEY(partida_id, jugador_id))');
        $this->addSql('CREATE INDEX IDX_D77FE8D4F15A1987 ON partidas_jugadores (partida_id)');
        $this->addSql('CREATE INDEX IDX_D77FE8D4B8A54D43 ON partidas_jugadores (jugador_id)');
        $this->addSql('ALTER TABLE juego ADD CONSTRAINT FK_F0EC403D6E5E864D FOREIGN KEY (juego_base_id) REFERENCES juego (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE juego_autor ADD CONSTRAINT FK_7245734C13375255 FOREIGN KEY (juego_id) REFERENCES juego (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE juego_autor ADD CONSTRAINT FK_7245734C14D45BBE FOREIGN KEY (autor_id) REFERENCES autor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE partida ADD CONSTRAINT FK_A9C1580C13375255 FOREIGN KEY (juego_id) REFERENCES juego (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE partida_jugador ADD CONSTRAINT FK_C6230C0CF15A1987 FOREIGN KEY (partida_id) REFERENCES partida (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE partida_jugador ADD CONSTRAINT FK_C6230C0CB8A54D43 FOREIGN KEY (jugador_id) REFERENCES jugador (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE partidas_jugadores ADD CONSTRAINT FK_D77FE8D4F15A1987 FOREIGN KEY (partida_id) REFERENCES partida (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE partidas_jugadores ADD CONSTRAINT FK_D77FE8D4B8A54D43 FOREIGN KEY (jugador_id) REFERENCES jugador (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE autor_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE juego_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE jugador_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE partida_id_seq CASCADE');
        $this->addSql('ALTER TABLE juego DROP CONSTRAINT FK_F0EC403D6E5E864D');
        $this->addSql('ALTER TABLE juego_autor DROP CONSTRAINT FK_7245734C13375255');
        $this->addSql('ALTER TABLE juego_autor DROP CONSTRAINT FK_7245734C14D45BBE');
        $this->addSql('ALTER TABLE partida DROP CONSTRAINT FK_A9C1580C13375255');
        $this->addSql('ALTER TABLE partida_jugador DROP CONSTRAINT FK_C6230C0CF15A1987');
        $this->addSql('ALTER TABLE partida_jugador DROP CONSTRAINT FK_C6230C0CB8A54D43');
        $this->addSql('ALTER TABLE partidas_jugadores DROP CONSTRAINT FK_D77FE8D4F15A1987');
        $this->addSql('ALTER TABLE partidas_jugadores DROP CONSTRAINT FK_D77FE8D4B8A54D43');
        $this->addSql('DROP TABLE autor');
        $this->addSql('DROP TABLE juego');
        $this->addSql('DROP TABLE juego_autor');
        $this->addSql('DROP TABLE jugador');
        $this->addSql('DROP TABLE partida');
        $this->addSql('DROP TABLE partida_jugador');
        $this->addSql('DROP TABLE partidas_jugadores');
    }
}
