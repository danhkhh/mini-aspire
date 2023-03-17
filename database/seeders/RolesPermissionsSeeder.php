<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Guard;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * @var array
     */
    const ALL_PERMISSIONS = [
        'loan.create',
        'loan.update',
        'loan.delete',
        'loan.view',
        'loan.list',

        'repayment.create',
        'repayment.update',
        'repayment.delete',
        'repayment.view',
        'repayment.list',
        'makePayment',
    ];

    /**
     * @var array
     */
    const ADMIN_PERMISSIONS = [
        'loan.create',
        'loan.update',
        'loan.delete',
        'loan.view',
        'loan.list',

        'repayment.create',
        'repayment.update',
        'repayment.delete',
        'repayment.view',
        'repayment.list',
        'makePayment',
    ];

    /**
     * @var array
     */
    const CUSTOMER_PERMISSIONS = [
        'loan.create',
        'loan.view',
        'loan.list',
        'repayment.create',
        'repayment.view',
        'repayment.list',
        'makePayment',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createPermissions();
        $this->createRoleAdmin();
        $this->createRoleCustomer();
    }

    /**
     * Create permissions
     *
     * @return void
     */
    private function createPermissions(): void
    {
        $permissions = collect(self::ALL_PERMISSIONS)->map(
            function ($permission) {
                return ['name' => $permission, 'guard_name' => Guard::getDefaultName(static::class)];
            }
        )->toArray();
        Permission::insert($permissions);
    }

    /**
     * Create admin role and assign permissions
     *
     * @return void
     */
    private function createRoleAdmin(): void
    {
        $role = Role::create(['name' => config('roles.ADMIN')]);
        $role->givePermissionTo(self::ADMIN_PERMISSIONS);
    }

    /**
     * Create customer role and assign permissions
     *
     * @return void
     */
    private function createRoleCustomer(): void
    {
        $role = Role::create(['name' => config('roles.CUSTOMER')]);
        $role->givePermissionTo(self::CUSTOMER_PERMISSIONS);
    }
}
