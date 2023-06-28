<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230627041854 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_174ee23f591cc992');
        $this->addSql('CREATE INDEX IDX_174EE23F591CC992 ON course_rate (course_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_174EE23F591CC9923FC1CD0A ON course_rate (course_id, rater_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX IDX_174EE23F591CC992');
        $this->addSql('DROP INDEX UNIQ_174EE23F591CC9923FC1CD0A');
        $this->addSql('CREATE UNIQUE INDEX uniq_174ee23f591cc992 ON course_rate (course_id)');
    }
}
