<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200321161951 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE panier ADD produits_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2CD11A2CF FOREIGN KEY (produits_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_24CC0DF2CD11A2CF ON panier (produits_id)');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27F77D927C');
        $this->addSql('DROP INDEX IDX_29A5EC27F77D927C ON produit');
        $this->addSql('ALTER TABLE produit DROP panier_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2CD11A2CF');
        $this->addSql('DROP INDEX IDX_24CC0DF2CD11A2CF ON panier');
        $this->addSql('ALTER TABLE panier DROP produits_id');
        $this->addSql('ALTER TABLE produit ADD panier_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27F77D927C ON produit (panier_id)');
    }
}
