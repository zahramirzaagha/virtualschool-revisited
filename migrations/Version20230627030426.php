<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230627030426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_e362df5acb944f1a');
        $this->addSql('DROP INDEX uniq_e362df5a591cc992');
        $this->addSql('CREATE INDEX IDX_E362DF5A591CC992 ON course_registration (course_id)');
        $this->addSql('CREATE INDEX IDX_E362DF5ACB944F1A ON course_registration (student_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E362DF5A591CC992CB944F1A ON course_registration (course_id, student_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX IDX_E362DF5A591CC992');
        $this->addSql('DROP INDEX IDX_E362DF5ACB944F1A');
        $this->addSql('DROP INDEX UNIQ_E362DF5A591CC992CB944F1A');
        $this->addSql('CREATE UNIQUE INDEX uniq_e362df5acb944f1a ON course_registration (student_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_e362df5a591cc992 ON course_registration (course_id)');
    }
}
