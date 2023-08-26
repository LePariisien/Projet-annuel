<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230822194523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE clients (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, company_name VARCHAR(255) NOT NULL, siret_number VARCHAR(255) NOT NULL, company_address VARCHAR(255) NOT NULL, stockage_space VARCHAR(255) NOT NULL, inscription_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_connection DATETIME NOT NULL, status VARCHAR(255) NOT NULL, last_account_update DATETIME NOT NULL, UNIQUE INDEX UNIQ_C82E74E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE files (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, file_name VARCHAR(255) NOT NULL, height VARCHAR(50) NOT NULL, format VARCHAR(50) NOT NULL, upload_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', description VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, last_modification_date DATETIME NOT NULL, INDEX IDX_635405919EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invoice (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, invoice_address_id INT NOT NULL, invoice_number INT NOT NULL, invoice_date DATETIME NOT NULL, expiring_date DATETIME NOT NULL, total_amount VARCHAR(50) NOT NULL, total_without_taxes VARCHAR(255) NOT NULL, taxe_amount VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9065174419EB6921 (client_id), INDEX IDX_90651744C6BDFEB (invoice_address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE files ADD CONSTRAINT FK_635405919EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_9065174419EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744C6BDFEB FOREIGN KEY (invoice_address_id) REFERENCES clients (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE files DROP FOREIGN KEY FK_635405919EB6921');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_9065174419EB6921');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_90651744C6BDFEB');
        $this->addSql('DROP TABLE clients');
        $this->addSql('DROP TABLE files');
        $this->addSql('DROP TABLE invoice');
    }
}
