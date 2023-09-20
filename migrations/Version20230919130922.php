<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230919130922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Supprimer le champ icone de la table partenaires
        $this->addSql('ALTER TABLE partenaires DROP icone');
    
        // Supprimer le champ icone de la table reseaux
        $this->addSql('ALTER TABLE reseaux DROP icone');
    }
    
}
