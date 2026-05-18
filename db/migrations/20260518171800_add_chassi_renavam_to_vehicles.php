<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Migration removida - campos chassi, renavam e placa agora estão
 * diretamente na migration CreateVehiclesTable (20260516193750)
 * 
 * Indices unicos tambem foram movidos para a migration principal
 * 
 * Esta migration fica vazia para nao quebrar o historico do phinxlog
 */
final class AddChassiRenavamToVehicles extends AbstractMigration
{
    public function change(): void
    {
        // Vazia intencionalmente - campos ja estao na migration principal
    }
}
