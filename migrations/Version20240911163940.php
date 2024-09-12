<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240911163940 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE expansion');
        $this->addSql('DROP TABLE juego');
        $this->addSql('DROP TABLE jugado');
        $this->addSql('DROP TABLE partida');
        $this->addSql('DROP TABLE tiene');
        $this->addSql('ALTER TABLE jugador CHANGE nombre nombre VARCHAR(255) NOT NULL, CHANGE nick nick VARCHAR(255) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL, CHANGE rol rol VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE expansion (id INT AUTO_INCREMENT NOT NULL, id_juego INT NOT NULL, nombre VARCHAR(255) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, INDEX id_juego (id_juego), PRIMARY KEY(id)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE juego (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, urimagen VARCHAR(255) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, dimensiones_caja VARCHAR(255) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, precio INT DEFAULT NULL, rango_jugadores VARCHAR(255) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, autores VARCHAR(255) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, productora_madre VARCHAR(255) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, productora_local VARCHAR(255) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE jugado (id INT AUTO_INCREMENT NOT NULL, id_jugador INT NOT NULL, id_partida INT NOT NULL, id_juego INT NOT NULL, INDEX id_juego (id_juego), INDEX id_jugador (id_jugador), INDEX id_partida (id_partida), PRIMARY KEY(id, id_jugador, id_partida)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE partida (id INT AUTO_INCREMENT NOT NULL, lugar VARCHAR(255) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, tiempo_minutos INT DEFAULT NULL, ganador VARCHAR(255) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tiene (id INT AUTO_INCREMENT NOT NULL, id_jugador INT NOT NULL, id_juego INT NOT NULL, INDEX id_jugador (id_jugador), INDEX id_juego (id_juego), PRIMARY KEY(id, id_jugador, id_juego)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('ALTER TABLE jugador CHANGE nombre nombre VARCHAR(255) DEFAULT NULL, CHANGE nick nick VARCHAR(255) DEFAULT NULL, CHANGE password password VARCHAR(255) DEFAULT NULL, CHANGE rol rol VARCHAR(255) DEFAULT NULL');
    }
}
