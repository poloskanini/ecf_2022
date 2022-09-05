<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220904214502 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE structure_permissions (structure_id INT NOT NULL, permissions_id INT NOT NULL, INDEX IDX_BCBBEECC2534008B (structure_id), INDEX IDX_BCBBEECC9C3E4F87 (permissions_id), PRIMARY KEY(structure_id, permissions_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE structure_permissions ADD CONSTRAINT FK_BCBBEECC2534008B FOREIGN KEY (structure_id) REFERENCES structure (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE structure_permissions ADD CONSTRAINT FK_BCBBEECC9C3E4F87 FOREIGN KEY (permissions_id) REFERENCES permissions (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE structure_permissions');
    }
}
