<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191207173159 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE booking_order (id INT AUTO_INCREMENT NOT NULL, customer_id INT UNSIGNED NOT NULL, order_date DATETIME NOT NULL, expected_date DATE NOT NULL, part_time_code SMALLINT NOT NULL, total_amount INT DEFAULT NULL, booking_ref VARCHAR(255) NOT NULL, validated_at DATETIME DEFAULT NULL, ext_payment_intent_ref VARCHAR(255) DEFAULT NULL, ext_payment_ref VARCHAR(255) DEFAULT NULL, ext_payment_status VARCHAR(255) DEFAULT NULL, settled_at DATETIME DEFAULT NULL, cancelled_at DATETIME DEFAULT NULL, INDEX IDX_64556E2D9395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id INT UNSIGNED AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, ref_payment_customer VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE visitor (id INT AUTO_INCREMENT NOT NULL, booking_order_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, birth_date DATETIME NOT NULL, country VARCHAR(2) NOT NULL, discounted TINYINT(1) NOT NULL, cost INT DEFAULT NULL, created_at DATETIME NOT NULL, confirmed_at DATETIME DEFAULT NULL, ticket_ref VARCHAR(255) DEFAULT NULL, cancelled TINYINT(1) NOT NULL, INDEX IDX_CAE5E19FB6ABF3B5 (booking_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking_order ADD CONSTRAINT FK_64556E2D9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE visitor ADD CONSTRAINT FK_CAE5E19FB6ABF3B5 FOREIGN KEY (booking_order_id) REFERENCES booking_order (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE visitor DROP FOREIGN KEY FK_CAE5E19FB6ABF3B5');
        $this->addSql('ALTER TABLE booking_order DROP FOREIGN KEY FK_64556E2D9395C3F3');
        $this->addSql('DROP TABLE booking_order');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE visitor');
    }
}
