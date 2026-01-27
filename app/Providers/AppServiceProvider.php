<?php

namespace App\Providers;

use App\Adapter\Repository\ColaboradorRepository;
use App\Adapter\Repository\DashboardRepository;
use App\Adapter\Repository\EntradaRepository;
use App\Adapter\Repository\FornecedorRepository;
use App\Adapter\Repository\GrupoRepository;
use App\Adapter\Repository\MaterialRepository;
use App\Adapter\Repository\NotaFiscalRepository;
use App\Adapter\Repository\PatioRepository;
use App\Adapter\Repository\PermissionGroupRepository;
use App\Adapter\Repository\PermissionItemRepository;
use App\Adapter\Repository\PrevisaoRepository;
use App\Adapter\Repository\SaidaRepository;
use App\Adapter\Repository\UnidadeMedidaRepository;
use App\Adapter\Repository\UserRepository;
use App\Core\Ports\Driven\ColaboradorRepositoryInterface;
use App\Core\Ports\Driven\DashboardRepositoryInterface;
use App\Core\Ports\Driven\EntradaRepositoryInterface;
use App\Core\Ports\Driven\FornecedorRepositoryInterface;
use App\Core\Ports\Driven\GrupoRepositoryInterface;
use App\Core\Ports\Driven\MaterialRepositoryInterface;
use App\Core\Ports\Driven\NotaFiscalRepositoryInterface;
use App\Core\Ports\Driven\PatioRepositoryInterface;
use App\Core\Ports\Driven\PermissionGroupRepositoryInterface;
use App\Core\Ports\Driven\PermissionItemRepositoryInterface;
use App\Core\Ports\Driven\PrevisaoRepositoryInterface;
use App\Core\Ports\Driven\SaidaRepositoryInterface;
use App\Core\Ports\Driven\UnidadeMedidaRepositoryInterface;
use App\Core\Ports\Driven\UserRepositoryInterface;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(GrupoRepositoryInterface::class, GrupoRepository::class);
        $this->app->bind(MaterialRepositoryInterface::class, MaterialRepository::class);
        $this->app->bind(PatioRepositoryInterface::class, PatioRepository::class);
        $this->app->bind(FornecedorRepositoryInterface::class, FornecedorRepository::class);
        $this->app->bind(UnidadeMedidaRepositoryInterface::class, UnidadeMedidaRepository::class);
        $this->app->bind(ColaboradorRepositoryInterface::class, ColaboradorRepository::class);
        $this->app->bind(NotaFiscalRepositoryInterface::class, NotaFiscalRepository::class);
        $this->app->bind(EntradaRepositoryInterface::class, EntradaRepository::class);
        $this->app->bind(SaidaRepositoryInterface::class, SaidaRepository::class);
        $this->app->bind(PrevisaoRepositoryInterface::class, PrevisaoRepository::class);
        $this->app->bind(PermissionGroupRepositoryInterface::class, PermissionGroupRepository::class);
        $this->app->bind(PermissionItemRepositoryInterface::class, PermissionItemRepository::class);
        $this->app->bind(DashboardRepositoryInterface::class, DashboardRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
            Schema::blueprintResolver(function ($table, $callback) {
                return new class($table, $callback) extends Blueprint {
                    public function timestamps($precision = 7)
                    {
                        // Força datetime2 no SQL Server
                        $this->addColumn('datetime2', 'created_at', ['precision' => $precision, 'nullable' => true]);
                        $this->addColumn('datetime2', 'updated_at', ['precision' => $precision, 'nullable' => true]);
                    }
                };
            });

        Schema::defaultStringLength(191);
    }

}
