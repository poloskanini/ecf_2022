<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220805115242 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partenaire CHANGE is_active is_active TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD name VARCHAR(255) NOT NULL, ADD is_active TINYINT(1) DEFAULT NULL, ADD is_newsletter TINYINT(1) DEFAULT NULL, ADD is_planning TINYINT(1) DEFAULT NULL, ADD is_boissons TINYINT(1) DEFAULT NULL, ADD is_sms TINYINT(1) DEFAULT NULL, ADD is_concours TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partenaire CHANGE is_active is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE user DROP name, DROP is_active, DROP is_newsletter, DROP is_planning, DROP is_boissons, DROP is_sms, DROP is_concours');
    }
}
