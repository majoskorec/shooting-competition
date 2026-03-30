<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Override;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260330095912 extends AbstractMigration
{
    #[Override]
    public function getDescription(): string
    {
        return '';
    }

    #[Override]
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE competition (
              id INT AUTO_INCREMENT NOT NULL,
              name VARCHAR(255) NOT NULL,
              competition_start DATETIME NOT NULL,
              description LONGTEXT DEFAULT NULL,
              location VARCHAR(255) DEFAULT NULL,
              organizer VARCHAR(255) DEFAULT NULL,
              status VARCHAR(255) NOT NULL,
              target_configuration_snapshot JSON NOT NULL,
              team_member_count INT NOT NULL,
              shooters_in_round INT NOT NULL,
              main_category_name VARCHAR(255) NOT NULL,
              competition_type_id INT NOT NULL,
              INDEX IDX_B50A2CB1DAF94C3D (competition_type_id),
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE competition_category (
              id INT AUTO_INCREMENT NOT NULL,
              name VARCHAR(255) NOT NULL,
              competition_id INT NOT NULL,
              INDEX IDX_373D22EE7B39D312 (competition_id),
              UNIQUE INDEX uniq_idx (competition_id, name),
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE competition_category_competitor (
              competition_category_id INT NOT NULL,
              competitor_id INT NOT NULL,
              INDEX IDX_ED03ED7F3AE7329C (competition_category_id),
              INDEX IDX_ED03ED7F78A5D405 (competitor_id),
              PRIMARY KEY (
                competition_category_id, competitor_id
              )
            ) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE competition_team (
              id INT AUTO_INCREMENT NOT NULL,
              name VARCHAR(255) NOT NULL,
              competition_id INT NOT NULL,
              INDEX IDX_CAA3380D7B39D312 (competition_id),
              UNIQUE INDEX uniq_idx (competition_id, name),
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE competition_type (
              id INT AUTO_INCREMENT NOT NULL,
              name VARCHAR(255) NOT NULL,
              description LONGTEXT DEFAULT NULL,
              UNIQUE INDEX uniq_idx (name),
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE competition_type_target (
              id INT AUTO_INCREMENT NOT NULL,
              display_order INT NOT NULL,
              shot_count INT NOT NULL,
              tie_break_priority INT NOT NULL,
              competition_type_id INT NOT NULL,
              target_definition_id INT NOT NULL,
              INDEX IDX_4D19EBBADAF94C3D (competition_type_id),
              INDEX IDX_4D19EBBACD929AA0 (target_definition_id),
              UNIQUE INDEX uniq_idx (
                competition_type_id, target_definition_id,
                display_order
              ),
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE competitor (
              id INT AUTO_INCREMENT NOT NULL,
              start_number INT DEFAULT NULL,
              shared_weapon_code VARCHAR(128) DEFAULT NULL,
              status VARCHAR(255) NOT NULL,
              competition_id INT NOT NULL,
              shooter_id INT NOT NULL,
              competition_team_id INT DEFAULT NULL,
              INDEX IDX_E0D53BAA7B39D312 (competition_id),
              INDEX IDX_E0D53BAAF42D3895 (shooter_id),
              INDEX IDX_E0D53BAA36D91800 (competition_team_id),
              UNIQUE INDEX uniq_idx (competition_id, shooter_id),
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE jury_entry (
              id INT AUTO_INCREMENT NOT NULL,
              points INT NOT NULL,
              description VARCHAR(255) DEFAULT NULL,
              competitor_id INT NOT NULL,
              category_id INT DEFAULT NULL,
              INDEX IDX_1825A90F78A5D405 (competitor_id),
              INDEX IDX_1825A90F12469DE2 (category_id),
              UNIQUE INDEX uniq_idx (competitor_id, category_id),
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE shooter (
              id INT AUTO_INCREMENT NOT NULL,
              first_name VARCHAR(255) NOT NULL,
              last_name VARCHAR(255) NOT NULL,
              club VARCHAR(255) DEFAULT NULL,
              email VARCHAR(255) DEFAULT NULL,
              UNIQUE INDEX uniq_idx (last_name, first_name),
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE target_definition (
              id INT AUTO_INCREMENT NOT NULL,
              name VARCHAR(255) NOT NULL,
              short_name VARCHAR(32) NOT NULL,
              points_schema JSON NOT NULL,
              UNIQUE INDEX uniq_idx (name),
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE target_result (
              id INT AUTO_INCREMENT NOT NULL,
              target_name VARCHAR(255) NOT NULL,
              hit_breakdown JSON NOT NULL,
              subtotal INT NOT NULL,
              competitor_id INT NOT NULL,
              INDEX IDX_1A6026A378A5D405 (competitor_id),
              UNIQUE INDEX uniq_idx (competitor_id, target_name),
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `user` (
              id INT AUTO_INCREMENT NOT NULL,
              email VARCHAR(180) NOT NULL,
              roles JSON NOT NULL,
              password VARCHAR(255) NOT NULL,
              full_name VARCHAR(255) NOT NULL,
              UNIQUE INDEX uniq_user_email (email),
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (
              id BIGINT AUTO_INCREMENT NOT NULL,
              body LONGTEXT NOT NULL,
              headers LONGTEXT NOT NULL,
              queue_name VARCHAR(190) NOT NULL,
              created_at DATETIME NOT NULL,
              available_at DATETIME NOT NULL,
              delivered_at DATETIME DEFAULT NULL,
              INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (
                queue_name, available_at, delivered_at,
                id
              ),
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE sessions (
              sess_id VARBINARY(128) NOT NULL,
              sess_data LONGBLOB NOT NULL,
              sess_lifetime INT UNSIGNED NOT NULL,
              sess_time INT UNSIGNED NOT NULL,
              INDEX sess_lifetime_idx (sess_lifetime),
              PRIMARY KEY (sess_id)
            ) DEFAULT CHARACTER SET utf8mb4 ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              competition
            ADD
              CONSTRAINT FK_B50A2CB1DAF94C3D FOREIGN KEY (competition_type_id) REFERENCES competition_type (id) ON DELETE RESTRICT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              competition_category
            ADD
              CONSTRAINT FK_373D22EE7B39D312 FOREIGN KEY (competition_id) REFERENCES competition (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              competition_category_competitor
            ADD
              CONSTRAINT FK_ED03ED7F3AE7329C FOREIGN KEY (competition_category_id) REFERENCES competition_category (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              competition_category_competitor
            ADD
              CONSTRAINT FK_ED03ED7F78A5D405 FOREIGN KEY (competitor_id) REFERENCES competitor (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              competition_team
            ADD
              CONSTRAINT FK_CAA3380D7B39D312 FOREIGN KEY (competition_id) REFERENCES competition (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              competition_type_target
            ADD
              CONSTRAINT FK_4D19EBBADAF94C3D FOREIGN KEY (competition_type_id) REFERENCES competition_type (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              competition_type_target
            ADD
              CONSTRAINT FK_4D19EBBACD929AA0 FOREIGN KEY (target_definition_id) REFERENCES target_definition (id) ON DELETE RESTRICT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              competitor
            ADD
              CONSTRAINT FK_E0D53BAA7B39D312 FOREIGN KEY (competition_id) REFERENCES competition (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              competitor
            ADD
              CONSTRAINT FK_E0D53BAAF42D3895 FOREIGN KEY (shooter_id) REFERENCES shooter (id) ON DELETE RESTRICT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              competitor
            ADD
              CONSTRAINT FK_E0D53BAA36D91800 FOREIGN KEY (competition_team_id) REFERENCES competition_team (id) ON DELETE
            SET
              NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              jury_entry
            ADD
              CONSTRAINT FK_1825A90F78A5D405 FOREIGN KEY (competitor_id) REFERENCES competitor (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              jury_entry
            ADD
              CONSTRAINT FK_1825A90F12469DE2 FOREIGN KEY (category_id) REFERENCES competition_category (id) ON DELETE
            SET
              NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              target_result
            ADD
              CONSTRAINT FK_1A6026A378A5D405 FOREIGN KEY (competitor_id) REFERENCES competitor (id) ON DELETE CASCADE
        SQL);
    }

    #[Override]
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE competition DROP FOREIGN KEY FK_B50A2CB1DAF94C3D');
        $this->addSql('ALTER TABLE competition_category DROP FOREIGN KEY FK_373D22EE7B39D312');
        $this->addSql('ALTER TABLE competition_category_competitor DROP FOREIGN KEY FK_ED03ED7F3AE7329C');
        $this->addSql('ALTER TABLE competition_category_competitor DROP FOREIGN KEY FK_ED03ED7F78A5D405');
        $this->addSql('ALTER TABLE competition_team DROP FOREIGN KEY FK_CAA3380D7B39D312');
        $this->addSql('ALTER TABLE competition_type_target DROP FOREIGN KEY FK_4D19EBBADAF94C3D');
        $this->addSql('ALTER TABLE competition_type_target DROP FOREIGN KEY FK_4D19EBBACD929AA0');
        $this->addSql('ALTER TABLE competitor DROP FOREIGN KEY FK_E0D53BAA7B39D312');
        $this->addSql('ALTER TABLE competitor DROP FOREIGN KEY FK_E0D53BAAF42D3895');
        $this->addSql('ALTER TABLE competitor DROP FOREIGN KEY FK_E0D53BAA36D91800');
        $this->addSql('ALTER TABLE jury_entry DROP FOREIGN KEY FK_1825A90F78A5D405');
        $this->addSql('ALTER TABLE jury_entry DROP FOREIGN KEY FK_1825A90F12469DE2');
        $this->addSql('ALTER TABLE target_result DROP FOREIGN KEY FK_1A6026A378A5D405');
        $this->addSql('DROP TABLE competition');
        $this->addSql('DROP TABLE competition_category');
        $this->addSql('DROP TABLE competition_category_competitor');
        $this->addSql('DROP TABLE competition_team');
        $this->addSql('DROP TABLE competition_type');
        $this->addSql('DROP TABLE competition_type_target');
        $this->addSql('DROP TABLE competitor');
        $this->addSql('DROP TABLE jury_entry');
        $this->addSql('DROP TABLE shooter');
        $this->addSql('DROP TABLE target_definition');
        $this->addSql('DROP TABLE target_result');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('DROP TABLE sessions');
    }
}
