<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220406095241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE booking_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE contest_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE feed_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE photo_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE users_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE booking (id INT NOT NULL, begin_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, title VARCHAR(255) NOT NULL, duration_in_minutes INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE contest (id INT NOT NULL, title VARCHAR(255) NOT NULL, start_date DATE NOT NULL, duration INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE contest_photo (contest_id INT NOT NULL, photo_id INT NOT NULL, PRIMARY KEY(contest_id, photo_id))');
        $this->addSql('CREATE INDEX IDX_18CAF4301CD0F0DE ON contest_photo (contest_id)');
        $this->addSql('CREATE INDEX IDX_18CAF4307E9E4C8C ON contest_photo (photo_id)');
        $this->addSql('CREATE TABLE feed (id INT NOT NULL, url VARCHAR(255) NOT NULL, event_count INT DEFAULT NULL, slug VARCHAR(255) NOT NULL, marking VARCHAR(32) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_234044AB989D9B62 ON feed (slug)');
        $this->addSql('CREATE TABLE photo (id INT NOT NULL, photographer_id INT NOT NULL, title VARCHAR(255) DEFAULT NULL, camera_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_14B7841853EC1A21 ON photo (photographer_id)');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, birthdate DATE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('ALTER TABLE contest_photo ADD CONSTRAINT FK_18CAF4301CD0F0DE FOREIGN KEY (contest_id) REFERENCES contest (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contest_photo ADD CONSTRAINT FK_18CAF4307E9E4C8C FOREIGN KEY (photo_id) REFERENCES photo (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B7841853EC1A21 FOREIGN KEY (photographer_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE contest_photo DROP CONSTRAINT FK_18CAF4301CD0F0DE');
        $this->addSql('ALTER TABLE contest_photo DROP CONSTRAINT FK_18CAF4307E9E4C8C');
        $this->addSql('ALTER TABLE photo DROP CONSTRAINT FK_14B7841853EC1A21');
        $this->addSql('DROP SEQUENCE booking_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE contest_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE feed_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE photo_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE users_id_seq CASCADE');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE contest');
        $this->addSql('DROP TABLE contest_photo');
        $this->addSql('DROP TABLE feed');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
