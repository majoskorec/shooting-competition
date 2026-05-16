<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Override;

final class Version20260516182337 extends AbstractMigration
{
    #[Override]
    public function getDescription(): string
    {
        return 'Add optional predefined rule to competition categories.';
    }

    #[Override]
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE competition_category ADD rule VARCHAR(32) DEFAULT NULL');
        $this->addSql("UPDATE competition_category SET rule = 'women' WHERE LOWER(name) IN ('ženy', 'zeny')");
        $this->addSql("UPDATE competition_category SET rule = 'men_seniors' WHERE LOWER(name) IN ('seniori', 'muzi seniori', 'muži seniori')");
        $this->addSql("UPDATE competition_category SET rule = 'men_veterans' WHERE LOWER(name) IN ('veteráni', 'veterani', 'muzi veterani', 'muži veteráni')");
        $this->addSql("UPDATE competition_category SET rule = 'juniors' WHERE LOWER(name) IN ('juniori')");
    }

    #[Override]
    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE competition_category DROP rule');
    }
}
