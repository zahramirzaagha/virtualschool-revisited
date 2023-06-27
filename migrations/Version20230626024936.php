<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230626024936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE course_registration_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE course_registration (id INT NOT NULL, course_id INT NOT NULL, student_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E362DF5A591CC992 ON course_registration (course_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E362DF5ACB944F1A ON course_registration (student_id)');
        $this->addSql('ALTER TABLE course_registration ADD CONSTRAINT FK_E362DF5A591CC992 FOREIGN KEY (course_id) REFERENCES course (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE course_registration ADD CONSTRAINT FK_E362DF5ACB944F1A FOREIGN KEY (student_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE course_registration_id_seq CASCADE');
        $this->addSql('ALTER TABLE course_registration DROP CONSTRAINT FK_E362DF5A591CC992');
        $this->addSql('ALTER TABLE course_registration DROP CONSTRAINT FK_E362DF5ACB944F1A');
        $this->addSql('DROP TABLE course_registration');
    }
}
