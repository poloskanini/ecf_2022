<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220819103332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE partner (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, is_planning TINYINT(1) NOT NULL, is_newsletter TINYINT(1) NOT NULL, is_boissons TINYINT(1) NOT NULL, is_sms TINYINT(1) NOT NULL, is_concours TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_312B3E16A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE structure (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, partner_id INT NOT NULL, postal_adress VARCHAR(255) NOT NULL, is_planning TINYINT(1) NOT NULL, is_newsletter TINYINT(1) NOT NULL, is_boissons TINYINT(1) NOT NULL, is_sms TINYINT(1) NOT NULL, is_concours TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_6F0137EAA76ED395 (user_id), INDEX IDX_6F0137EA9393F8FE (partner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE partner ADD CONSTRAINT FK_312B3E16A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE structure ADD CONSTRAINT FK_6F0137EAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE structure ADD CONSTRAINT FK_6F0137EA9393F8FE FOREIGN KEY (partner_id) REFERENCES partner (id)');
        $this->addSql('ALTER TABLE user DROP is_newsletter, DROP is_planning, DROP is_boissons, DROP is_sms, DROP is_concours, CHANGE name name VARCHAR(255) NOT NULL, CHANGE is_active is_active TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE structure DROP FOREIGN KEY FK_6F0137EA9393F8FE');
        $this->addSql('DROP TABLE partner');
        $this->addSql('DROP TABLE structure');
        $this->addSql('ALTER TABLE user ADD is_newsletter TINYINT(1) DEFAULT NULL, ADD is_planning TINYINT(1) DEFAULT NULL, ADD is_boissons TINYINT(1) DEFAULT NULL, ADD is_sms TINYINT(1) DEFAULT NULL, ADD is_concours TINYINT(1) DEFAULT NULL, CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE is_active is_active TINYINT(1) DEFAULT NULL');
    }
}
