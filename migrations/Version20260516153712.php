<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Override;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260516153712 extends AbstractMigration
{
    #[Override]
    public function getDescription(): string
    {
        return 'Add required shooter birth year and extend shooter uniqueness with birth year.';
    }

    #[Override]
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE shooter ADD birth_year INT NOT NULL DEFAULT 0');
        $this->addSql('ALTER TABLE shooter MODIFY birth_year INT NOT NULL');
        $this->addSql('ALTER TABLE shooter DROP INDEX uniq_idx, ADD UNIQUE INDEX uniq_idx (last_name, first_name, birth_year)');
    }

    #[Override]
    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE shooter DROP INDEX uniq_idx, ADD UNIQUE INDEX uniq_idx (last_name, first_name)');
        $this->addSql('ALTER TABLE shooter DROP birth_year');
    }
}
