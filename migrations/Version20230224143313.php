<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230224143313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ac (id INT AUTO_INCREMENT NOT NULL, numero INT NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, texte VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competence (id INT AUTO_INCREMENT NOT NULL, numero INT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, note INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projet (id INT AUTO_INCREMENT NOT NULL, notes_id INT DEFAULT NULL, commentaires_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, annee INT NOT NULL, competences VARCHAR(255) NOT NULL, ac VARCHAR(255) NOT NULL, acquis VARCHAR(255) NOT NULL, domaine VARCHAR(255) NOT NULL, photo VARCHAR(255) NOT NULL, video VARCHAR(255) DEFAULT NULL, note INT DEFAULT NULL, INDEX IDX_50159CA9FC56F556 (notes_id), INDEX IDX_50159CA917C4B2B0 (commentaires_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, projets_id INT DEFAULT NULL, notes_id INT DEFAULT NULL, commentaires_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1D1C63B3E7927C74 (email), INDEX IDX_1D1C63B3597A6CB7 (projets_id), INDEX IDX_1D1C63B3FC56F556 (notes_id), INDEX IDX_1D1C63B317C4B2B0 (commentaires_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA9FC56F556 FOREIGN KEY (notes_id) REFERENCES note (id)');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA917C4B2B0 FOREIGN KEY (commentaires_id) REFERENCES commentaire (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3597A6CB7 FOREIGN KEY (projets_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3FC56F556 FOREIGN KEY (notes_id) REFERENCES note (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B317C4B2B0 FOREIGN KEY (commentaires_id) REFERENCES commentaire (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA9FC56F556');
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA917C4B2B0');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3597A6CB7');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3FC56F556');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B317C4B2B0');
        $this->addSql('DROP TABLE ac');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE competence');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE projet');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
