<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190317181537 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, governorate_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_2D5B0234B5FFB04E (governorate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, plan_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, date_event DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_3BAE0AA7E899029B (plan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE governorate (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, plan_id INT DEFAULT NULL, photo_name VARCHAR(255) NOT NULL, INDEX IDX_14B78418E899029B (plan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plan (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, category_id INT DEFAULT NULL, city_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, start_day DATETIME NOT NULL, final_day DATETIME NOT NULL, start_time DATETIME NOT NULL, final_time DATETIME NOT NULL, address VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, lat DOUBLE PRECISION DEFAULT NULL, lon DOUBLE PRECISION DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_DD5A5B7DA76ED395 (user_id), INDEX IDX_DD5A5B7D12469DE2 (category_id), INDEX IDX_DD5A5B7D8BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, plan_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_D34A04ADE899029B (plan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service_plan (service_id INT NOT NULL, plan_id INT NOT NULL, INDEX IDX_1319EF91ED5CA9E6 (service_id), INDEX IDX_1319EF91E899029B (plan_id), PRIMARY KEY(service_id, plan_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE settings (id INT AUTO_INCREMENT NOT NULL, logo VARCHAR(255) DEFAULT NULL, updated_at DATETIME NOT NULL, background VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, facebook VARCHAR(255) DEFAULT NULL, twitter VARCHAR(255) DEFAULT NULL, google VARCHAR(255) DEFAULT NULL, pinterest VARCHAR(255) DEFAULT NULL, instagram VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B0234B5FFB04E FOREIGN KEY (governorate_id) REFERENCES governorate (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7E899029B FOREIGN KEY (plan_id) REFERENCES plan (id)');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B78418E899029B FOREIGN KEY (plan_id) REFERENCES plan (id)');
        $this->addSql('ALTER TABLE plan ADD CONSTRAINT FK_DD5A5B7DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE plan ADD CONSTRAINT FK_DD5A5B7D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE plan ADD CONSTRAINT FK_DD5A5B7D8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADE899029B FOREIGN KEY (plan_id) REFERENCES plan (id)');
        $this->addSql('ALTER TABLE service_plan ADD CONSTRAINT FK_1319EF91ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE service_plan ADD CONSTRAINT FK_1319EF91E899029B FOREIGN KEY (plan_id) REFERENCES plan (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE plan DROP FOREIGN KEY FK_DD5A5B7D12469DE2');
        $this->addSql('ALTER TABLE plan DROP FOREIGN KEY FK_DD5A5B7D8BAC62AF');
        $this->addSql('ALTER TABLE city DROP FOREIGN KEY FK_2D5B0234B5FFB04E');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7E899029B');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B78418E899029B');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADE899029B');
        $this->addSql('ALTER TABLE service_plan DROP FOREIGN KEY FK_1319EF91E899029B');
        $this->addSql('ALTER TABLE service_plan DROP FOREIGN KEY FK_1319EF91ED5CA9E6');
        $this->addSql('ALTER TABLE plan DROP FOREIGN KEY FK_DD5A5B7DA76ED395');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE governorate');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE plan');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE service_plan');
        $this->addSql('DROP TABLE settings');
        $this->addSql('DROP TABLE user');
    }
}
