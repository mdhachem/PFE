<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190517215117 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, plan_id INT DEFAULT NULL, user_id INT DEFAULT NULL, start_date VARCHAR(255) NOT NULL, final_date VARCHAR(255) DEFAULT NULL, guests INT DEFAULT NULL, room_type VARCHAR(255) DEFAULT NULL, menu VARCHAR(255) DEFAULT NULL, INDEX IDX_E00CEDDEE899029B (plan_id), INDEX IDX_E00CEDDEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEE899029B FOREIGN KEY (plan_id) REFERENCES plan (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE booking');
    }
}
