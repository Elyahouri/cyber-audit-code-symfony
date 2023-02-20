<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230220122631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contribution ADD company_id INT NOT NULL');
        $this->addSql('ALTER TABLE contribution ADD CONSTRAINT FK_EA351E15979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_EA351E15979B1AD6 ON contribution (company_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contribution DROP FOREIGN KEY FK_EA351E15979B1AD6');
        $this->addSql('DROP INDEX IDX_EA351E15979B1AD6 ON contribution');
        $this->addSql('ALTER TABLE contribution DROP company_id');
    }
}
