<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\SellerRole;
use App\Models\Seller;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        DB::table('role_permissions')->truncate();
        $permissionGroups = [
            'dashboard' => [
                'dashboard-view', 'dashboard-create', 'dashboard-update', 'dashboard-delete'
            ],
            'user' => [
                'user-view', 'user-create', 'user-update', 'user-delete'
            ],
            'seller' => [
                'seller-view', 'seller-create', 'seller-update', 'seller-delete'
            ],
            'buyer' => [
                'buyer-view', 'buyer-create', 'buyer-update', 'buyer-delete'
            ],
            'category' => [
                'category-view', 'category-create', 'category-update', 'category-delete'
            ],
            'role' => [
                'role-view',   'role-create', 'role-update',  'role-delete',
            ],
            'product' => [
                'product-view',   'product-create', 'product-update',  'product-delete',
            ],
            'customer' => [
                'customer-view', 'customer-create', 'customer-update', 'customer-delete'
            ],
            'order' => [
                'order-view', 'order-create', 'order-update', 'order-delete'
            ],
            'sale' => [
                'sale-view', 'sale-create', 'sale-update', 'sale-delete'
            ],
            'brand' => [
                'brand-view', 'brand-create', 'brand-update', 'brand-delete'
            ],
            'payment' => [
                'payment-view', 'payment-create'
            ],
            'offer' => [
                'offer-view', 'offer-create', 'offer-update', 'offer-delete'
            ],
            'collection' => [
                'collection-view', 'collection-create', 'collection-update', 'collection-delete'
            ],
            'material' => [
                'material-view', 'material-create', 'material-update', 'material-delete'
            ],
            'homePageManager' => [
                'homePageManager-create', 'homePageManager-update'
            ],
            'cache' => [
                'cache-update'
            ],


        ];

        foreach ($permissionGroups as $permissionGroup => $permissions) {
            foreach ($permissions as $permission) {
                DB::table('permissions')->insert([
                    'permission_group' => $permissionGroup,
                    'permission_name' => $permission
                ]);
            }
        }

        $roles = ['SUPER-ADMIN', 'Admin', "Seller"];
        foreach ($roles as $role) {
            DB::table('roles')->insert([
                'role_name' => $role
            ]);
        }

        $permissions = Permission::all();
        // Assign Permissions for 'SUPER-ADMIN' Role 
        foreach ($permissions as $permission) {
            RolePermission::firstOrCreate([
                'role_id' => 1,
                'permission_id' => $permission->id
            ]);
        }
        // Assign Permissions for 'Admin' Role 
        foreach ($permissions as $permission) {
            if ($permission->permission_name === $permission->permission_group . '-view') {
                RolePermission::firstOrCreate([
                    'role_id' => 2,
                    'permission_id' => $permission->id
                ]);
            }
        }

        // Assign Permissions for 'Seller' Role 
        foreach ($permissions as $permission) {
            if ($permission->permission_group === 'product') {
                RolePermission::firstOrCreate([
                    'role_id' => 3,
                    'permission_id' => $permission->id
                ]);
            }
        }
        // Seller permissions 
        $seller_permissions_ids = [33, 35, 37, 41, 45, 47, 55];
        foreach ($seller_permissions_ids as $id) {
            RolePermission::firstOrCreate([
                'role_id' => 3,
                'permission_id' => $id
            ]);
        }




        DB::table('user_roles')->insert([
            'user_id' => 1,
            'role_id' => 1
        ]);
        DB::table('user_roles')->insert([
            'user_id' => 2,
            'role_id' => 1
        ]);
    }
}
