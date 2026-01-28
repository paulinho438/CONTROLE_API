<?php

namespace App\Core\Ports\Driven;

interface SaidaRepositoryInterface extends CrudRepositoryInterface
{
    public function buscarPorRomaneio(string $numeroRomaneio);
}

