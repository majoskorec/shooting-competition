<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Override;

final class Version20260516175717 extends AbstractMigration
{
    #[Override]
    public function getDescription(): string
    {
        return 'Add required shooter gender and extend shooter uniqueness with gender.';
    }

    #[Override]
    public function up(Schema $schema): void
    {
        $this->addSql("ALTER TABLE shooter ADD gender VARCHAR(8) NOT NULL DEFAULT 'muz'");
        $this->addSql('ALTER TABLE shooter CHANGE gender gender VARCHAR(8) NOT NULL');
        $this->addSql('ALTER TABLE shooter DROP INDEX uniq_idx, ADD UNIQUE INDEX uniq_idx (last_name, first_name, birth_year, gender)');
    }

    #[Override]
    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE shooter DROP INDEX uniq_idx, ADD UNIQUE INDEX uniq_idx (last_name, first_name, birth_year)');
        $this->addSql('ALTER TABLE shooter DROP gender');
    }
}
