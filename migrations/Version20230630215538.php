<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230630215538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE course (id INT AUTO_INCREMENT NOT NULL, instructor_id INT NOT NULL, title VARCHAR(2048) NOT NULL, INDEX IDX_169E6FB98C4FC193 (instructor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course_rate (id INT AUTO_INCREMENT NOT NULL, course_id INT NOT NULL, rater_id INT NOT NULL, rate INT NOT NULL, INDEX IDX_174EE23F591CC992 (course_id), INDEX IDX_174EE23F3FC1CD0A (rater_id), UNIQUE INDEX UNIQ_174EE23F591CC9923FC1CD0A (course_id, rater_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course_registration (id INT AUTO_INCREMENT NOT NULL, course_id INT NOT NULL, student_id INT NOT NULL, grade DOUBLE PRECISION DEFAULT NULL, INDEX IDX_E362DF5A591CC992 (course_id), INDEX IDX_E362DF5ACB944F1A (student_id), UNIQUE INDEX UNIQ_E362DF5A591CC992CB944F1A (course_id, student_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(1024) NOT NULL, email VARCHAR(180) NOT NULL, school VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB98C4FC193 FOREIGN KEY (instructor_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE course_rate ADD CONSTRAINT FK_174EE23F591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE course_rate ADD CONSTRAINT FK_174EE23F3FC1CD0A FOREIGN KEY (rater_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE course_registration ADD CONSTRAINT FK_E362DF5A591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE course_registration ADD CONSTRAINT FK_E362DF5ACB944F1A FOREIGN KEY (student_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649727ACA70 FOREIGN KEY (parent_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB98C4FC193');
        $this->addSql('ALTER TABLE course_rate DROP FOREIGN KEY FK_174EE23F591CC992');
        $this->addSql('ALTER TABLE course_rate DROP FOREIGN KEY FK_174EE23F3FC1CD0A');
        $this->addSql('ALTER TABLE course_registration DROP FOREIGN KEY FK_E362DF5A591CC992');
        $this->addSql('ALTER TABLE course_registration DROP FOREIGN KEY FK_E362DF5ACB944F1A');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649727ACA70');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE course_rate');
        $this->addSql('DROP TABLE course_registration');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
