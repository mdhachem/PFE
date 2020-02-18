<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190326080042 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE plan_service (plan_id INT NOT NULL, service_id INT NOT NULL, INDEX IDX_5F22D2DAE899029B (plan_id), INDEX IDX_5F22D2DAED5CA9E6 (service_id), PRIMARY KEY(plan_id, service_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE plan_service ADD CONSTRAINT FK_5F22D2DAE899029B FOREIGN KEY (plan_id) REFERENCES plan (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plan_service ADD CONSTRAINT FK_5F22D2DAED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE service_plan');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE service_plan (service_id INT NOT NULL, plan_id INT NOT NULL, INDEX IDX_1319EF91E899029B (plan_id), INDEX IDX_1319EF91ED5CA9E6 (service_id), PRIMARY KEY(service_id, plan_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE service_plan ADD CONSTRAINT FK_1319EF91E899029B FOREIGN KEY (plan_id) REFERENCES plan (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE service_plan ADD CONSTRAINT FK_1319EF91ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE plan_service');
    }
}
