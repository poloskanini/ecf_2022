<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220901101846 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE partner_permissions (partner_id INT NOT NULL, permissions_id INT NOT NULL, INDEX IDX_649E7A219393F8FE (partner_id), INDEX IDX_649E7A219C3E4F87 (permissions_id), PRIMARY KEY(partner_id, permissions_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permissions (id INT AUTO_INCREMENT NOT NULL, is_planning TINYINT(1) NOT NULL, is_newsletter TINYINT(1) NOT NULL, is_boissons TINYINT(1) NOT NULL, is_sms TINYINT(1) NOT NULL, is_concours TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE partner_permissions ADD CONSTRAINT FK_649E7A219393F8FE FOREIGN KEY (partner_id) REFERENCES partner (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE partner_permissions ADD CONSTRAINT FK_649E7A219C3E4F87 FOREIGN KEY (permissions_id) REFERENCES permissions (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partner_permissions DROP FOREIGN KEY FK_649E7A219C3E4F87');
        $this->addSql('DROP TABLE partner_permissions');
        $this->addSql('DROP TABLE permissions');
    }
}
