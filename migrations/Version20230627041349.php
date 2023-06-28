<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230627041349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE course_rate_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE course_rate (id INT NOT NULL, course_id INT NOT NULL, rater_id INT NOT NULL, rate INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_174EE23F591CC992 ON course_rate (course_id)');
        $this->addSql('CREATE INDEX IDX_174EE23F3FC1CD0A ON course_rate (rater_id)');
        $this->addSql('ALTER TABLE course_rate ADD CONSTRAINT FK_174EE23F591CC992 FOREIGN KEY (course_id) REFERENCES course (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE course_rate ADD CONSTRAINT FK_174EE23F3FC1CD0A FOREIGN KEY (rater_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE course_rate_id_seq CASCADE');
        $this->addSql('ALTER TABLE course_rate DROP CONSTRAINT FK_174EE23F591CC992');
        $this->addSql('ALTER TABLE course_rate DROP CONSTRAINT FK_174EE23F3FC1CD0A');
        $this->addSql('DROP TABLE course_rate');
    }
}
