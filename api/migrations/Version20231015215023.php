<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231015215023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE bike_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE booking_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE franchise_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE notification_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE payment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE proposition_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE publication_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE question_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE schedule_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE shop_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE type_category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE type_question_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE vacation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE bike (id INT NOT NULL, shop_id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, brand VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4CBC37804D16C4DD ON bike (shop_id)');
        $this->addSql('CREATE INDEX IDX_4CBC3780B03A8386 ON bike (created_by_id)');
        $this->addSql('CREATE INDEX IDX_4CBC3780896DBBDE ON bike (updated_by_id)');
        $this->addSql('COMMENT ON COLUMN bike.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN bike.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE bike_proposition (bike_id INT NOT NULL, proposition_id INT NOT NULL, PRIMARY KEY(bike_id, proposition_id))');
        $this->addSql('CREATE INDEX IDX_548ADB76D5A4816F ON bike_proposition (bike_id)');
        $this->addSql('CREATE INDEX IDX_548ADB76DB96F9E ON bike_proposition (proposition_id)');
        $this->addSql('CREATE TABLE booking (id INT NOT NULL, user_id INT NOT NULL, bike_id INT DEFAULT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, rating DOUBLE PRECISION NOT NULL, status VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E00CEDDEA76ED395 ON booking (user_id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDED5A4816F ON booking (bike_id)');
        $this->addSql('COMMENT ON COLUMN booking.start_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN booking.end_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN booking.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN booking.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE category (id INT NOT NULL, type_category_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_64C19C1595F4A73 ON category (type_category_id)');
        $this->addSql('CREATE INDEX IDX_64C19C1B03A8386 ON category (created_by_id)');
        $this->addSql('CREATE INDEX IDX_64C19C1896DBBDE ON category (updated_by_id)');
        $this->addSql('COMMENT ON COLUMN category.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN category.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE category_question (category_id INT NOT NULL, question_id INT NOT NULL, PRIMARY KEY(category_id, question_id))');
        $this->addSql('CREATE INDEX IDX_18DCD3512469DE2 ON category_question (category_id)');
        $this->addSql('CREATE INDEX IDX_18DCD351E27F6BF ON category_question (question_id)');
        $this->addSql('CREATE TABLE comment (id INT NOT NULL, post_id INT NOT NULL, author_id INT NOT NULL, content VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9474526C4B89032C ON comment (post_id)');
        $this->addSql('CREATE INDEX IDX_9474526CF675F31B ON comment (author_id)');
        $this->addSql('COMMENT ON COLUMN comment.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE franchise (id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, is_active BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_66F6CE2AB03A8386 ON franchise (created_by_id)');
        $this->addSql('CREATE INDEX IDX_66F6CE2A896DBBDE ON franchise (updated_by_id)');
        $this->addSql('COMMENT ON COLUMN franchise.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN franchise.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE franchise_user (franchise_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(franchise_id, user_id))');
        $this->addSql('CREATE INDEX IDX_4FBBCB81523CAB89 ON franchise_user (franchise_id)');
        $this->addSql('CREATE INDEX IDX_4FBBCB81A76ED395 ON franchise_user (user_id)');
        $this->addSql('CREATE TABLE notification (id INT NOT NULL, notification_type VARCHAR(255) NOT NULL, sender VARCHAR(255) NOT NULL, sent_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN notification.sent_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN notification.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN notification.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE notification_user (notification_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(notification_id, user_id))');
        $this->addSql('CREATE INDEX IDX_35AF9D73EF1A9D84 ON notification_user (notification_id)');
        $this->addSql('CREATE INDEX IDX_35AF9D73A76ED395 ON notification_user (user_id)');
        $this->addSql('CREATE TABLE payment (id INT NOT NULL, booking_id INT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, commission INT NOT NULL, stripe_id VARCHAR(255) NOT NULL, status VARCHAR(255) DEFAULT NULL, payment_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6D28840D3301C60 ON payment (booking_id)');
        $this->addSql('COMMENT ON COLUMN payment.payment_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN payment.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN payment.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE payment_shop (payment_id INT NOT NULL, shop_id INT NOT NULL, PRIMARY KEY(payment_id, shop_id))');
        $this->addSql('CREATE INDEX IDX_8DE9DBD64C3A3BB ON payment_shop (payment_id)');
        $this->addSql('CREATE INDEX IDX_8DE9DBD64D16C4DD ON payment_shop (shop_id)');
        $this->addSql('CREATE TABLE payment_user (payment_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(payment_id, user_id))');
        $this->addSql('CREATE INDEX IDX_AC10413D4C3A3BB ON payment_user (payment_id)');
        $this->addSql('CREATE INDEX IDX_AC10413DA76ED395 ON payment_user (user_id)');
        $this->addSql('CREATE TABLE product (id INT NOT NULL, name VARCHAR(255) NOT NULL, price INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, documentation_url VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN product.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE product_user (product_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(product_id, user_id))');
        $this->addSql('CREATE INDEX IDX_7BF4E84584665A ON product_user (product_id)');
        $this->addSql('CREATE INDEX IDX_7BF4E8A76ED395 ON product_user (user_id)');
        $this->addSql('CREATE TABLE proposition (id INT NOT NULL, question_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C7CDC3531E27F6BF ON proposition (question_id)');
        $this->addSql('CREATE INDEX IDX_C7CDC353B03A8386 ON proposition (created_by_id)');
        $this->addSql('CREATE INDEX IDX_C7CDC353896DBBDE ON proposition (updated_by_id)');
        $this->addSql('COMMENT ON COLUMN proposition.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN proposition.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE publication (id INT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, resume TEXT NOT NULL, content TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AF3C6779F675F31B ON publication (author_id)');
        $this->addSql('COMMENT ON COLUMN publication.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE question (id INT NOT NULL, type_question_id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B6F7494E553E212E ON question (type_question_id)');
        $this->addSql('CREATE INDEX IDX_B6F7494EB03A8386 ON question (created_by_id)');
        $this->addSql('CREATE INDEX IDX_B6F7494E896DBBDE ON question (updated_by_id)');
        $this->addSql('COMMENT ON COLUMN question.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN question.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE request (id INT NOT NULL, franchise_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, request_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3B978F9F523CAB89 ON request (franchise_id)');
        $this->addSql('CREATE INDEX IDX_3B978F9FB03A8386 ON request (created_by_id)');
        $this->addSql('CREATE INDEX IDX_3B978F9F896DBBDE ON request (updated_by_id)');
        $this->addSql('COMMENT ON COLUMN request.request_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN request.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN request.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE schedule (id INT NOT NULL, dow INT NOT NULL, start_time TIME(0) WITHOUT TIME ZONE NOT NULL, end_time TIME(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN schedule.start_time IS \'(DC2Type:time_immutable)\'');
        $this->addSql('COMMENT ON COLUMN schedule.end_time IS \'(DC2Type:time_immutable)\'');
        $this->addSql('COMMENT ON COLUMN schedule.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN schedule.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE schedule_shop (schedule_id INT NOT NULL, shop_id INT NOT NULL, PRIMARY KEY(schedule_id, shop_id))');
        $this->addSql('CREATE INDEX IDX_4C226CF9A40BC2D5 ON schedule_shop (schedule_id)');
        $this->addSql('CREATE INDEX IDX_4C226CF94D16C4DD ON schedule_shop (shop_id)');
        $this->addSql('CREATE TABLE shop (id INT NOT NULL, franchise_id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, is_opened BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AC6A4CA2523CAB89 ON shop (franchise_id)');
        $this->addSql('CREATE INDEX IDX_AC6A4CA2B03A8386 ON shop (created_by_id)');
        $this->addSql('CREATE INDEX IDX_AC6A4CA2896DBBDE ON shop (updated_by_id)');
        $this->addSql('COMMENT ON COLUMN shop.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN shop.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE type_category (id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CBB33E3DB03A8386 ON type_category (created_by_id)');
        $this->addSql('CREATE INDEX IDX_CBB33E3D896DBBDE ON type_category (updated_by_id)');
        $this->addSql('COMMENT ON COLUMN type_category.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN type_category.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE type_question (id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, placeholder VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7B086EB2B03A8386 ON type_question (created_by_id)');
        $this->addSql('CREATE INDEX IDX_7B086EB2896DBBDE ON type_question (updated_by_id)');
        $this->addSql('COMMENT ON COLUMN type_question.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN type_question.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, roles JSON NOT NULL, lastname VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, locale VARCHAR(255) DEFAULT NULL, status BOOLEAN NOT NULL, verification_key VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE vacation (id INT NOT NULL, shop_id INT DEFAULT NULL, user_id INT DEFAULT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E3DADF754D16C4DD ON vacation (shop_id)');
        $this->addSql('CREATE INDEX IDX_E3DADF75A76ED395 ON vacation (user_id)');
        $this->addSql('COMMENT ON COLUMN vacation.start_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN vacation.end_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN vacation.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN vacation.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE bike ADD CONSTRAINT FK_4CBC37804D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE bike ADD CONSTRAINT FK_4CBC3780B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE bike ADD CONSTRAINT FK_4CBC3780896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE bike_proposition ADD CONSTRAINT FK_548ADB76D5A4816F FOREIGN KEY (bike_id) REFERENCES bike (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE bike_proposition ADD CONSTRAINT FK_548ADB76DB96F9E FOREIGN KEY (proposition_id) REFERENCES proposition (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDED5A4816F FOREIGN KEY (bike_id) REFERENCES bike (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1595F4A73 FOREIGN KEY (type_category_id) REFERENCES type_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE category_question ADD CONSTRAINT FK_18DCD3512469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE category_question ADD CONSTRAINT FK_18DCD351E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4B89032C FOREIGN KEY (post_id) REFERENCES publication (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE franchise ADD CONSTRAINT FK_66F6CE2AB03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE franchise ADD CONSTRAINT FK_66F6CE2A896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE franchise_user ADD CONSTRAINT FK_4FBBCB81523CAB89 FOREIGN KEY (franchise_id) REFERENCES franchise (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE franchise_user ADD CONSTRAINT FK_4FBBCB81A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification_user ADD CONSTRAINT FK_35AF9D73EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification_user ADD CONSTRAINT FK_35AF9D73A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D3301C60 FOREIGN KEY (booking_id) REFERENCES booking (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment_shop ADD CONSTRAINT FK_8DE9DBD64C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment_shop ADD CONSTRAINT FK_8DE9DBD64D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment_user ADD CONSTRAINT FK_AC10413D4C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment_user ADD CONSTRAINT FK_AC10413DA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_user ADD CONSTRAINT FK_7BF4E84584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_user ADD CONSTRAINT FK_7BF4E8A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE proposition ADD CONSTRAINT FK_C7CDC3531E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE proposition ADD CONSTRAINT FK_C7CDC353B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE proposition ADD CONSTRAINT FK_C7CDC353896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E553E212E FOREIGN KEY (type_question_id) REFERENCES type_question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EB03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9F523CAB89 FOREIGN KEY (franchise_id) REFERENCES franchise (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9FB03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9F896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE schedule_shop ADD CONSTRAINT FK_4C226CF9A40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE schedule_shop ADD CONSTRAINT FK_4C226CF94D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shop ADD CONSTRAINT FK_AC6A4CA2523CAB89 FOREIGN KEY (franchise_id) REFERENCES franchise (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shop ADD CONSTRAINT FK_AC6A4CA2B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shop ADD CONSTRAINT FK_AC6A4CA2896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE type_category ADD CONSTRAINT FK_CBB33E3DB03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE type_category ADD CONSTRAINT FK_CBB33E3D896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE type_question ADD CONSTRAINT FK_7B086EB2B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE type_question ADD CONSTRAINT FK_7B086EB2896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vacation ADD CONSTRAINT FK_E3DADF754D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vacation ADD CONSTRAINT FK_E3DADF75A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE bike_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE booking_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE comment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE franchise_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE notification_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE payment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE proposition_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE publication_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE question_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE request_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE schedule_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE shop_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE type_category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE type_question_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE vacation_id_seq CASCADE');
        $this->addSql('ALTER TABLE bike DROP CONSTRAINT FK_4CBC37804D16C4DD');
        $this->addSql('ALTER TABLE bike DROP CONSTRAINT FK_4CBC3780B03A8386');
        $this->addSql('ALTER TABLE bike DROP CONSTRAINT FK_4CBC3780896DBBDE');
        $this->addSql('ALTER TABLE bike_proposition DROP CONSTRAINT FK_548ADB76D5A4816F');
        $this->addSql('ALTER TABLE bike_proposition DROP CONSTRAINT FK_548ADB76DB96F9E');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDEA76ED395');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDED5A4816F');
        $this->addSql('ALTER TABLE category DROP CONSTRAINT FK_64C19C1595F4A73');
        $this->addSql('ALTER TABLE category DROP CONSTRAINT FK_64C19C1B03A8386');
        $this->addSql('ALTER TABLE category DROP CONSTRAINT FK_64C19C1896DBBDE');
        $this->addSql('ALTER TABLE category_question DROP CONSTRAINT FK_18DCD3512469DE2');
        $this->addSql('ALTER TABLE category_question DROP CONSTRAINT FK_18DCD351E27F6BF');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C4B89032C');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CF675F31B');
        $this->addSql('ALTER TABLE franchise DROP CONSTRAINT FK_66F6CE2AB03A8386');
        $this->addSql('ALTER TABLE franchise DROP CONSTRAINT FK_66F6CE2A896DBBDE');
        $this->addSql('ALTER TABLE franchise_user DROP CONSTRAINT FK_4FBBCB81523CAB89');
        $this->addSql('ALTER TABLE franchise_user DROP CONSTRAINT FK_4FBBCB81A76ED395');
        $this->addSql('ALTER TABLE notification_user DROP CONSTRAINT FK_35AF9D73EF1A9D84');
        $this->addSql('ALTER TABLE notification_user DROP CONSTRAINT FK_35AF9D73A76ED395');
        $this->addSql('ALTER TABLE payment DROP CONSTRAINT FK_6D28840D3301C60');
        $this->addSql('ALTER TABLE payment_shop DROP CONSTRAINT FK_8DE9DBD64C3A3BB');
        $this->addSql('ALTER TABLE payment_shop DROP CONSTRAINT FK_8DE9DBD64D16C4DD');
        $this->addSql('ALTER TABLE payment_user DROP CONSTRAINT FK_AC10413D4C3A3BB');
        $this->addSql('ALTER TABLE payment_user DROP CONSTRAINT FK_AC10413DA76ED395');
        $this->addSql('ALTER TABLE product_user DROP CONSTRAINT FK_7BF4E84584665A');
        $this->addSql('ALTER TABLE product_user DROP CONSTRAINT FK_7BF4E8A76ED395');
        $this->addSql('ALTER TABLE proposition DROP CONSTRAINT FK_C7CDC3531E27F6BF');
        $this->addSql('ALTER TABLE proposition DROP CONSTRAINT FK_C7CDC353B03A8386');
        $this->addSql('ALTER TABLE proposition DROP CONSTRAINT FK_C7CDC353896DBBDE');
        $this->addSql('ALTER TABLE publication DROP CONSTRAINT FK_AF3C6779F675F31B');
        $this->addSql('ALTER TABLE question DROP CONSTRAINT FK_B6F7494E553E212E');
        $this->addSql('ALTER TABLE question DROP CONSTRAINT FK_B6F7494EB03A8386');
        $this->addSql('ALTER TABLE question DROP CONSTRAINT FK_B6F7494E896DBBDE');
        $this->addSql('ALTER TABLE request DROP CONSTRAINT FK_3B978F9F523CAB89');
        $this->addSql('ALTER TABLE request DROP CONSTRAINT FK_3B978F9FB03A8386');
        $this->addSql('ALTER TABLE request DROP CONSTRAINT FK_3B978F9F896DBBDE');
        $this->addSql('ALTER TABLE schedule_shop DROP CONSTRAINT FK_4C226CF9A40BC2D5');
        $this->addSql('ALTER TABLE schedule_shop DROP CONSTRAINT FK_4C226CF94D16C4DD');
        $this->addSql('ALTER TABLE shop DROP CONSTRAINT FK_AC6A4CA2523CAB89');
        $this->addSql('ALTER TABLE shop DROP CONSTRAINT FK_AC6A4CA2B03A8386');
        $this->addSql('ALTER TABLE shop DROP CONSTRAINT FK_AC6A4CA2896DBBDE');
        $this->addSql('ALTER TABLE type_category DROP CONSTRAINT FK_CBB33E3DB03A8386');
        $this->addSql('ALTER TABLE type_category DROP CONSTRAINT FK_CBB33E3D896DBBDE');
        $this->addSql('ALTER TABLE type_question DROP CONSTRAINT FK_7B086EB2B03A8386');
        $this->addSql('ALTER TABLE type_question DROP CONSTRAINT FK_7B086EB2896DBBDE');
        $this->addSql('ALTER TABLE vacation DROP CONSTRAINT FK_E3DADF754D16C4DD');
        $this->addSql('ALTER TABLE vacation DROP CONSTRAINT FK_E3DADF75A76ED395');
        $this->addSql('DROP TABLE bike');
        $this->addSql('DROP TABLE bike_proposition');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_question');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE franchise');
        $this->addSql('DROP TABLE franchise_user');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE notification_user');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE payment_shop');
        $this->addSql('DROP TABLE payment_user');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_user');
        $this->addSql('DROP TABLE proposition');
        $this->addSql('DROP TABLE publication');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE request');
        $this->addSql('DROP TABLE schedule');
        $this->addSql('DROP TABLE schedule_shop');
        $this->addSql('DROP TABLE shop');
        $this->addSql('DROP TABLE type_category');
        $this->addSql('DROP TABLE type_question');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE vacation');
    }
}
