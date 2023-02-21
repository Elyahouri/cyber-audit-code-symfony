<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230221104833 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D4ACC9A20');
        $this->addSql('ALTER TABLE annual_sale DROP FOREIGN KEY FK_6959C666979B1AD6');
        $this->addSql('DROP TABLE annual_sale');
        $this->addSql('DROP TABLE card');
        $this->addSql('DROP INDEX IDX_6D28840D4ACC9A20 ON payment');
        $this->addSql('ALTER TABLE payment ADD card_owner VARCHAR(255) NOT NULL, ADD card_numbers VARCHAR(255) NOT NULL, ADD card_expiration_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', ADD card_code VARCHAR(255) NOT NULL, DROP card_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annual_sale (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, year VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, amount INT NOT NULL, INDEX IDX_6959C666979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE card (id INT AUTO_INCREMENT NOT NULL, owner VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, numbers VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, expiration_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', code VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE annual_sale ADD CONSTRAINT FK_6959C666979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE payment ADD card_id INT NOT NULL, DROP card_owner, DROP card_numbers, DROP card_expiration_date, DROP card_code');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D4ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id)');
        $this->addSql('CREATE INDEX IDX_6D28840D4ACC9A20 ON payment (card_id)');
    }
}
