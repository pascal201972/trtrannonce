<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220424045146 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE trt_annonce (id INT AUTO_INCREMENT NOT NULL, profession_id INT DEFAULT NULL, experience_id INT DEFAULT NULL, contrat_id INT DEFAULT NULL, recruteur_id INT DEFAULT NULL, etat_id INT DEFAULT NULL, description LONGTEXT NOT NULL, horaire VARCHAR(50) NOT NULL, salaire_annuel INT NOT NULL, valider TINYINT(1) NOT NULL, complet TINYINT(1) NOT NULL, INDEX IDX_9DB0FA10FDEF8996 (profession_id), INDEX IDX_9DB0FA1046E90E27 (experience_id), INDEX IDX_9DB0FA101823061F (contrat_id), INDEX IDX_9DB0FA10BB0859F1 (recruteur_id), INDEX IDX_9DB0FA10D5E86FF (etat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trt_contrat (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trt_etat_annonce (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE trt_annonce ADD CONSTRAINT FK_9DB0FA10FDEF8996 FOREIGN KEY (profession_id) REFERENCES trt_professions (id)');
        $this->addSql('ALTER TABLE trt_annonce ADD CONSTRAINT FK_9DB0FA1046E90E27 FOREIGN KEY (experience_id) REFERENCES trt_experiences (id)');
        $this->addSql('ALTER TABLE trt_annonce ADD CONSTRAINT FK_9DB0FA101823061F FOREIGN KEY (contrat_id) REFERENCES trt_contrat (id)');
        $this->addSql('ALTER TABLE trt_annonce ADD CONSTRAINT FK_9DB0FA10BB0859F1 FOREIGN KEY (recruteur_id) REFERENCES trt_profilrecruteur (id)');
        $this->addSql('ALTER TABLE trt_annonce ADD CONSTRAINT FK_9DB0FA10D5E86FF FOREIGN KEY (etat_id) REFERENCES trt_etat_annonce (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trt_annonce DROP FOREIGN KEY FK_9DB0FA101823061F');
        $this->addSql('ALTER TABLE trt_annonce DROP FOREIGN KEY FK_9DB0FA10D5E86FF');
        $this->addSql('DROP TABLE trt_annonce');
        $this->addSql('DROP TABLE trt_contrat');
        $this->addSql('DROP TABLE trt_etat_annonce');
    }
}
