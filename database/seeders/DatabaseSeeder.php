<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate tables (optional, useful for fresh seeding)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // Disable FK checks temporarily

        // List tables in reverse creation order for truncation
        $tables = [
            'affiliate_recurring_commissions',
            'affiliate_referrals',
            'affiliate_payout_runs',
            'affiliates',
            'expenses',
            'reservations',
            'pay_slips',
            'payroll_runs',
            'employee_schedules',
            'employee_attendances',
            'inventory_movements',
            'recipe_ingredients',
            'recipes',
            'inventory_items',
            'ingredients',
            'payments',
            'order_item_options',
            'order_items',
            'orders',
            'customers',
            'tables',
            'menu_item_option_associations',
            'menu_item_option_values',
            'menu_items',
            'categories',
            'outlets',
            'user_roles',
            'role_permissions',
            'users',
            'companies',
            'company_subscriptions',
            'permissions',
            'roles',
            'subscription_plans',
        ];

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // Re-enable FK checks

        // --- 1. Subscription Plans ---
        $subscriptionPlanId1 = Str::uuid();
        $subscriptionPlanId2 = Str::uuid();

        DB::table('subscription_plans')->insert([
            [
                'id' => $subscriptionPlanId1,
                'name' => 'Basic Plan',
                'description' => 'Starter plan for small businesses.',
                'base_price_monthly' => 99000.00,
                'base_price_yearly' => 990000.00,
                'features_included' => json_encode(['POS', 'Basic Reporting', '1 Outlet']),
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => $subscriptionPlanId2,
                'name' => 'Premium Plan',
                'description' => 'Advanced features for growing businesses.',
                'base_price_monthly' => 299000.00,
                'base_price_yearly' => 2990000.00,
                'features_included' => json_encode(['POS', 'Advanced Reporting', 'Multiple Outlets', 'Inventory Management', 'Payroll']),
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
        $this->command->info('Subscription Plans seeded!');

        // --- 2. Companies (Initially without current_subscription_id for circular dependency) ---
        $companyId1 = Str::uuid();
        $companyId2 = Str::uuid();

        DB::table('companies')->insert([
            [
                'id' => $companyId1,
                'name' => 'Geprek Juara',
                'business_type' => 'Restaurant',
                'scale' => 'UMKM_Kecil',
                'address' => 'Jl. Merdeka No. 123, Bandung',
                'phone' => '081234567890',
                'email' => 'info@geprekjuara.com',
                'logo_url' => 'https://example.com/logo_geprek.png',
                'operating_hours' => json_encode(['Mon-Sun' => '09:00-22:00']),
                'current_subscription_id' => null, // Set to null initially
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => $companyId2,
                'name' => 'Kopi Santuy',
                'business_type' => 'Cafe',
                'scale' => 'UMKM_Mikro',
                'address' => 'Jl. Damai No. 45, Jakarta',
                'phone' => '087654321098',
                'email' => 'hello@kopisantuy.com',
                'logo_url' => 'https://example.com/logo_kopi.png',
                'operating_hours' => json_encode(['Mon-Fri' => '08:00-20:00', 'Sat-Sun' => '10:00-18:00']),
                'current_subscription_id' => null, // Set to null initially
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
        $this->command->info('Companies seeded (initial)!');

        // --- 3. Company Subscriptions ---
        $subscriptionId1 = Str::uuid();
        $subscriptionId2 = Str::uuid();

        DB::table('company_subscriptions')->insert([
            [
                'id' => $subscriptionId1,
                'company_id' => $companyId1,
                'subscription_plan_id' => $subscriptionPlanId1,
                'start_date' => Carbon::now()->subMonths(3)->toDateString(),
                'end_date' => Carbon::now()->addMonths(9)->toDateString(),
                'price_locked_monthly' => 99000.00,
                'features_locked' => json_encode(['POS', 'Basic Reporting', '1 Outlet']),
                'status' => 'Active',
                'last_billed_at' => Carbon::now()->subMonth(),
                'next_billing_at' => Carbon::now()->addMonth(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => $subscriptionId2,
                'company_id' => $companyId2,
                'subscription_plan_id' => $subscriptionPlanId2,
                'start_date' => Carbon::now()->subMonths(1)->toDateString(),
                'end_date' => Carbon::now()->addMonths(11)->toDateString(),
                'price_locked_monthly' => 299000.00,
                'features_locked' => json_encode(['POS', 'Advanced Reporting', 'Multiple Outlets', 'Inventory Management', 'Payroll']),
                'status' => 'Active',
                'last_billed_at' => Carbon::now()->subMonth(),
                'next_billing_at' => Carbon::now()->addMonth(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
        $this->command->info('Company Subscriptions seeded!');

        // --- 4. Update Companies with current_subscription_id ---
        DB::table('companies')->where('id', $companyId1)->update(['current_subscription_id' => $subscriptionId1, 'updated_at' => Carbon::now()]);
        DB::table('companies')->where('id', $companyId2)->update(['current_subscription_id' => $subscriptionId2, 'updated_at' => Carbon::now()]);
        $this->command->info('Companies updated with current_subscription_id!');


        // --- 5. Users ---
        $userId1 = Str::uuid(); // Admin Geprek Juara
        $userId2 = Str::uuid(); // Kasir Geprek Juara
        $userId3 = Str::uuid(); // Owner Kopi Santuy
        $userId4 = Str::uuid(); // Restomax Super Admin

        DB::table('users')->insert([
            [
                'id' => $userId1,
                'company_id' => $companyId1,
                'email' => 'owner@geprekjuara.com',
                'password' => Hash::make('password'),
                'full_name' => 'Budi Setiawan',
                'phone' => '08111222333',
                'profile_picture_url' => null,
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => $userId2,
                'company_id' => $companyId1,
                'email' => 'kasir@geprekjuara.com',
                'password' => Hash::make('password'),
                'full_name' => 'Citra Dewi',
                'phone' => '082233445566',
                'profile_picture_url' => null,
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => $userId3,
                'company_id' => $companyId2,
                'email' => 'owner@kopisantuy.com',
                'password' => Hash::make('password'),
                'full_name' => 'Ani Suryani',
                'phone' => '085566778899',
                'profile_picture_url' => null,
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => $userId4,
                'company_id' => null, // Super Admin
                'email' => 'admin@restomax.com',
                'password' => Hash::make('admin123'),
                'full_name' => 'Restomax Admin',
                'phone' => '080011223344',
                'profile_picture_url' => null,
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
        $this->command->info('Users seeded!');

        // --- 6. Roles ---
        $roleOwner = Str::uuid();
        $roleManager = Str::uuid();
        $roleCashier = Str::uuid();
        $roleChef = Str::uuid();
        $roleWaiter = Str::uuid();
        $roleSuperAdmin = Str::uuid();

        DB::table('roles')->insert([
            ['id' => $roleSuperAdmin, 'name' => 'Super Admin', 'description' => 'Full access to Restomax system', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => $roleOwner, 'name' => 'Company Owner', 'description' => 'Owner of a specific company', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => $roleManager, 'name' => 'Manager', 'description' => 'Manages outlet operations', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => $roleCashier, 'name' => 'Cashier', 'description' => 'Handles transactions and orders', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => $roleChef, 'name' => 'Chef', 'description' => 'Prepares food items', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => $roleWaiter, 'name' => 'Waiter', 'description' => 'Takes orders and serves food', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
        $this->command->info('Roles seeded!');

        // --- 7. Permissions ---
        $permViewSales = Str::uuid();
        $permCreateMenu = Str::uuid();
        $permManageEmployees = Str::uuid();
        $permProcessPayroll = Str::uuid();

        DB::table('permissions')->insert([
            ['id' => $permViewSales, 'name' => 'view_sales_report', 'description' => 'Permission to view sales reports', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => $permCreateMenu, 'name' => 'create_menu_item', 'description' => 'Permission to create new menu items', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => $permManageEmployees, 'name' => 'manage_employees_payroll', 'description' => 'Permission to manage employee data and payroll', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            // BARIS YANG DIPERBAIKI: Menambahkan 'description'
            ['id' => $permProcessPayroll, 'name' => 'process_payroll', 'description' => 'Permission to process payroll runs', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
        $this->command->info('Permissions seeded!');

        // --- 8. Role Permissions ---
        DB::table('role_permissions')->insert([
            ['role_id' => $roleSuperAdmin, 'permission_id' => $permViewSales],
            ['role_id' => $roleSuperAdmin, 'permission_id' => $permCreateMenu],
            ['role_id' => $roleSuperAdmin, 'permission_id' => $permManageEmployees],
            ['role_id' => $roleSuperAdmin, 'permission_id' => $permProcessPayroll],
            ['role_id' => $roleOwner, 'permission_id' => $permViewSales],
            ['role_id' => $roleOwner, 'permission_id' => $permCreateMenu],
            ['role_id' => $roleOwner, 'permission_id' => $permManageEmployees],
            ['role_id' => $roleManager, 'permission_id' => $permViewSales],
            ['role_id' => $roleManager, 'permission_id' => $permCreateMenu],
            ['role_id' => $roleCashier, 'permission_id' => $permViewSales], // Cashier can view their own sales
        ]);
        $this->command->info('Role Permissions seeded!');

        // --- 9. User Roles ---
        DB::table('user_roles')->insert([
            ['user_id' => $userId1, 'role_id' => $roleOwner, 'company_id' => $companyId1],
            ['user_id' => $userId2, 'role_id' => $roleCashier, 'company_id' => $companyId1],
            ['user_id' => $userId3, 'role_id' => $roleOwner, 'company_id' => $companyId2],
            ['user_id' => $userId4, 'role_id' => $roleSuperAdmin, 'company_id' => $companyId1], // Super admin can manage any company
            ['user_id' => $userId4, 'role_id' => $roleSuperAdmin, 'company_id' => $companyId2], // Super admin can manage any company
        ]);
        $this->command->info('User Roles seeded!');

        // --- 10. Outlets ---
        $outletId1 = Str::uuid(); // Geprek Juara Pusat
        $outletId2 = Str::uuid(); // Kopi Santuy Cabang A

        DB::table('outlets')->insert([
            [
                'id' => $outletId1,
                'company_id' => $companyId1,
                'name' => 'Geprek Juara Pusat',
                'address' => 'Jl. Merdeka No. 123, Bandung',
                'phone' => '081234567890',
                'email' => 'pusat@geprekjuara.com',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => $outletId2,
                'company_id' => $companyId2,
                'name' => 'Kopi Santuy Cabang A',
                'address' => 'Jl. Damai No. 45, Jakarta',
                'phone' => '087654321098',
                'email' => 'cabangA@kopisantuy.com',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
        $this->command->info('Outlets seeded!');

        // --- 11. Categories ---
        $categoryId1 = Str::uuid(); // Makanan Geprek
        $categoryId2 = Str::uuid(); // Minuman Kopi
        $categoryId3 = Str::uuid(); // Cemilan

        DB::table('categories')->insert([
            [
                'id' => $categoryId1,
                'company_id' => $companyId1,
                'outlet_id' => null, // Applies to all outlets of Geprek Juara
                'name' => 'Ayam Geprek',
                'description' => 'Berbagai olahan ayam geprek pedas',
                'display_order' => 1,
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => $categoryId2,
                'company_id' => $companyId2,
                'outlet_id' => $outletId2, // Specific to this outlet
                'name' => 'Kopi Susu',
                'description' => 'Varian kopi susu kekinian',
                'display_order' => 1,
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => $categoryId3,
                'company_id' => $companyId2,
                'outlet_id' => null, // Applies to all outlets of Kopi Santuy
                'name' => 'Snacks',
                'description' => 'Cemilan pendamping kopi',
                'display_order' => 2,
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
        $this->command->info('Categories seeded!');

        // --- 12. Menu Items ---
        $menuItemId1 = Str::uuid(); // Ayam Geprek Original
        $menuItemId2 = Str::uuid(); // Es Kopi Susu Santuy
        $menuItemId3 = Str::uuid(); // French Fries

        DB::table('menu_items')->insert([
            [
                'id' => $menuItemId1,
                'company_id' => $companyId1,
                'outlet_id' => $outletId1,
                'category_id' => $categoryId1,
                'name' => 'Ayam Geprek Original',
                'description' => 'Ayam goreng crispy dengan sambal bawang pedas',
                'price' => 20000.00,
                'image_url' => 'https://example.com/geprek_ori.png',
                'is_available' => true,
                'preparation_time_minutes' => 10,
                'sku' => 'GPK001',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => $menuItemId2,
                'company_id' => $companyId2,
                'outlet_id' => $outletId2,
                'category_id' => $categoryId2,
                'name' => 'Es Kopi Susu Santuy',
                'description' => 'Kopi susu gula aren signature',
                'price' => 18000.00,
                'image_url' => 'https://example.com/kopi_santuy.png',
                'is_available' => true,
                'preparation_time_minutes' => 5,
                'sku' => 'KPS001',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => $menuItemId3,
                'company_id' => $companyId2,
                'outlet_id' => $outletId2,
                'category_id' => $categoryId3,
                'name' => 'French Fries',
                'description' => 'Kentang goreng renyah',
                'price' => 15000.00,
                'image_url' => 'https://example.com/french_fries.png',
                'is_available' => true,
                'preparation_time_minutes' => 7,
                'sku' => 'SNK001',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
        $this->command->info('Menu Items seeded!');

        // --- 13. Menu Item Options & Values ---
        $optionLevelPedas = Str::uuid();
        $optionUkuranKopi = Str::uuid();

        DB::table('menu_item_options')->insert([
            [
                'id' => $optionLevelPedas,
                'company_id' => $companyId1,
                'name' => 'Level Pedas',
                'type' => 'single_select',
                'is_required' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => $optionUkuranKopi,
                'company_id' => $companyId2,
                'name' => 'Ukuran',
                'type' => 'single_select',
                'is_required' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        $valuePedas1 = Str::uuid();
        $valuePedas2 = Str::uuid();
        $valuePedas3 = Str::uuid();
        $valueUkuranKecil = Str::uuid();
        $valueUkuranNormal = Str::uuid();
        $valueUkuranBesar = Str::uuid();

        DB::table('menu_item_option_values')->insert([
            ['id' => $valuePedas1, 'menu_item_option_id' => $optionLevelPedas, 'value' => 'Tidak Pedas', 'price_adjustment' => 0.00, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => $valuePedas2, 'menu_item_option_id' => $optionLevelPedas, 'value' => 'Pedas Sedang', 'price_adjustment' => 1000.00, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => $valuePedas3, 'menu_item_option_id' => $optionLevelPedas, 'value' => 'Pedas Banget', 'price_adjustment' => 2000.00, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => $valueUkuranKecil, 'menu_item_option_id' => $optionUkuranKopi, 'value' => 'Kecil', 'price_adjustment' => -2000.00, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => $valueUkuranNormal, 'menu_item_option_id' => $optionUkuranKopi, 'value' => 'Normal', 'price_adjustment' => 0.00, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => $valueUkuranBesar, 'menu_item_option_id' => $optionUkuranKopi, 'value' => 'Besar', 'price_adjustment' => 3000.00, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
        $this->command->info('Menu Item Options and Values seeded!');

        // --- 14. Menu Item Option Associations ---
        DB::table('menu_item_option_associations')->insert([
            ['menu_item_id' => $menuItemId1, 'menu_item_option_id' => $optionLevelPedas], // Ayam Geprek dengan Level Pedas
            ['menu_item_id' => $menuItemId2, 'menu_item_option_id' => $optionUkuranKopi],  // Es Kopi Susu dengan Ukuran
        ]);
        $this->command->info('Menu Item Option Associations seeded!');

        // --- 15. Tables ---
        $tableId1 = Str::uuid(); // Meja A1 Geprek Juara
        $tableId2 = Str::uuid(); // Meja B1 Kopi Santuy

        DB::table('tables')->insert([
            [
                'id' => $tableId1,
                'outlet_id' => $outletId1,
                'table_number' => 'A1',
                'capacity' => 4,
                'qr_code_url' => 'https://example.com/qr/geprek_a1',
                'layout_x' => 10,
                'layout_y' => 20,
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => $tableId2,
                'outlet_id' => $outletId2,
                'table_number' => 'B1',
                'capacity' => 2,
                'qr_code_url' => 'https://example.com/qr/kopi_b1',
                'layout_x' => 5,
                'layout_y' => 10,
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
        $this->command->info('Tables seeded!');

        // --- 16. Customers ---
        $customerId1 = Str::uuid();
        $customerId2 = Str::uuid();

        DB::table('customers')->insert([
            [
                'id' => $customerId1,
                'company_id' => $companyId1,
                'full_name' => 'Andi Susanto',
                'email' => 'andi@example.com',
                'phone' => '081212341234',
                'loyalty_points' => 150,
                'member_tier' => 'Silver',
                'birthday' => '1990-05-15',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => $customerId2,
                'company_id' => $companyId2,
                'full_name' => 'Dewi Lestari',
                'email' => 'dewi@example.com',
                'phone' => '087856785678',
                'loyalty_points' => 200,
                'member_tier' => 'Gold',
                'birthday' => '1988-11-20',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
        $this->command->info('Customers seeded!');

        // --- 17. Orders ---
        $orderId1 = Str::uuid(); // Dine-in Geprek Juara
        $orderId2 = Str::uuid(); // Takeaway Kopi Santuy

        DB::table('orders')->insert([
            [
                'id' => $orderId1,
                'company_id' => $companyId1,
                'outlet_id' => $outletId1,
                'customer_id' => $customerId1,
                'user_id' => $userId2, // Cashier
                'table_id' => $tableId1,
                'order_type' => 'Dine-in',
                'status' => 'Completed',
                'total_amount' => 45000.00,
                'discount_amount' => 0.00,
                'tax_amount' => 4500.00,
                'service_charge_amount' => 0.00,
                'payment_status' => 'Paid',
                'notes' => 'Tambahan sambal',
                'order_time' => Carbon::now()->subHours(2),
                'delivery_address' => null,
                'delivery_driver_id' => null,
                'created_at' => Carbon::now()->subHours(2),
                'updated_at' => Carbon::now()->subHours(1),
            ],
            [
                'id' => $orderId2,
                'company_id' => $companyId2,
                'outlet_id' => $outletId2,
                'customer_id' => $customerId2,
                'user_id' => $userId3, // Owner acting as cashier
                'table_id' => null,
                'order_type' => 'Takeaway',
                'status' => 'Completed',
                'total_amount' => 33000.00,
                'discount_amount' => 0.00,
                'tax_amount' => 3300.00,
                'service_charge_amount' => 0.00,
                'payment_status' => 'Paid',
                'notes' => 'Tanpa sedotan plastik',
                'order_time' => Carbon::now()->subHours(1),
                'delivery_address' => null,
                'delivery_driver_id' => null,
                'created_at' => Carbon::now()->subHours(1),
                'updated_at' => Carbon::now()->subMinutes(30),
            ],
        ]);
        $this->command->info('Orders seeded!');

        // --- 18. Order Items ---
        $orderItemId1 = Str::uuid();
        $orderItemId2 = Str::uuid();
        $orderItemId3 = Str::uuid();

        DB::table('order_items')->insert([
            [
                'id' => $orderItemId1,
                'order_id' => $orderId1,
                'menu_item_id' => $menuItemId1, // Ayam Geprek Original
                'quantity' => 2,
                'price_per_unit' => 20000.00,
                'total_price' => 40000.00,
                'item_notes' => 'Pedas sedang satu, tidak pedas satu',
                'kds_status' => 'Served',
                'created_at' => Carbon::now()->subHours(2),
                'updated_at' => Carbon::now()->subHours(1),
            ],
            [
                'id' => $orderItemId2,
                'order_id' => $orderId2,
                'menu_item_id' => $menuItemId2, // Es Kopi Susu Santuy
                'quantity' => 1,
                'price_per_unit' => 18000.00,
                'total_price' => 18000.00,
                'item_notes' => null,
                'kds_status' => 'Served',
                'created_at' => Carbon::now()->subHours(1),
                'updated_at' => Carbon::now()->subMinutes(30),
            ],
            [
                'id' => $orderItemId3,
                'order_id' => $orderId2,
                'menu_item_id' => $menuItemId3, // French Fries
                'quantity' => 1,
                'price_per_unit' => 15000.00,
                'total_price' => 15000.00,
                'item_notes' => null,
                'kds_status' => 'Served',
                'created_at' => Carbon::now()->subHours(1),
                'updated_at' => Carbon::now()->subMinutes(30),
            ],
        ]);
        $this->command->info('Order Items seeded!');

        // --- 19. Order Item Options ---
        $orderItemOptionId1 = Str::uuid();
        $orderItemOptionId2 = Str::uuid();

        DB::table('order_item_options')->insert([
            [
                'id' => $orderItemOptionId1,
                'order_item_id' => $orderItemId1,
                'menu_item_option_value_id' => $valuePedas2, // Pedas Sedang
                'price_adjustment_applied' => 1000.00,
                'created_at' => Carbon::now()->subHours(2),
                'updated_at' => Carbon::now()->subHours(2),
            ],
            [
                'id' => $orderItemOptionId2,
                'order_item_id' => $orderItemId2,
                'menu_item_option_value_id' => $valueUkuranNormal, // Normal
                'price_adjustment_applied' => 0.00,
                'created_at' => Carbon::now()->subHours(1),
                'updated_at' => Carbon::now()->subHours(1),
            ],
        ]);
        $this->command->info('Order Item Options seeded!');

        // --- 20. Payments ---
        $paymentId1 = Str::uuid();
        $paymentId2 = Str::uuid();

        DB::table('payments')->insert([
            [
                'id' => $paymentId1,
                'order_id' => $orderId1,
                'payment_method' => 'QRIS',
                'amount' => 49500.00, // Total + Tax
                'transaction_id' => 'TRX0012345',
                'status' => 'Success',
                'payment_time' => Carbon::now()->subHours(1)->addMinutes(10),
                'created_at' => Carbon::now()->subHours(1)->addMinutes(10),
                'updated_at' => Carbon::now()->subHours(1)->addMinutes(10),
            ],
            [
                'id' => $paymentId2,
                'order_id' => $orderId2,
                'payment_method' => 'Cash',
                'amount' => 36300.00, // Total + Tax
                'transaction_id' => null,
                'status' => 'Success',
                'payment_time' => Carbon::now()->subMinutes(25),
                'created_at' => Carbon::now()->subMinutes(25),
                'updated_at' => Carbon::now()->subMinutes(25),
            ],
        ]);
        $this->command->info('Payments seeded!');

        // --- 21. Ingredients ---
        $ingredientId1 = Str::uuid(); // Ayam
        $ingredientId2 = Str::uuid(); // Beras
        $ingredientId3 = Str::uuid(); // Biji Kopi
        $ingredientId4 = Str::uuid(); // Kentang

        DB::table('ingredients')->insert([
            [
                'id' => $ingredientId1,
                'company_id' => $companyId1,
                'name' => 'Ayam Fillet',
                'unit_of_measure' => 'kg',
                'default_cost_per_unit' => 30000.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => $ingredientId2,
                'company_id' => $companyId1,
                'name' => 'Beras',
                'unit_of_measure' => 'kg',
                'default_cost_per_unit' => 12000.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => $ingredientId3,
                'company_id' => $companyId2,
                'name' => 'Biji Kopi Arabica',
                'unit_of_measure' => 'gram',
                'default_cost_per_unit' => 100.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => $ingredientId4,
                'company_id' => $companyId2,
                'name' => 'Kentang French Fries',
                'unit_of_measure' => 'kg',
                'default_cost_per_unit' => 18000.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
        $this->command->info('Ingredients seeded!');

        // --- 22. Inventory Items ---
        $inventoryItemId1 = Str::uuid(); // Ayam Geprek Juara Pusat
        $inventoryItemId2 = Str::uuid(); // Beras Geprek Juara Pusat
        $inventoryItemId3 = Str::uuid(); // Biji Kopi Kopi Santuy Cabang A

        DB::table('inventory_items')->insert([
            [
                'id' => $inventoryItemId1,
                'company_id' => $companyId1,
                'outlet_id' => $outletId1,
                'ingredient_id' => $ingredientId1,
                'current_stock' => 50.00,
                'minimum_stock_level' => 10.00,
                'last_restocked_at' => Carbon::now()->subDays(3),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => $inventoryItemId2,
                'company_id' => $companyId1,
                'outlet_id' => $outletId1,
                'ingredient_id' => $ingredientId2,
                'current_stock' => 100.00,
                'minimum_stock_level' => 20.00,
                'last_restocked_at' => Carbon::now()->subDays(5),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => $inventoryItemId3,
                'company_id' => $companyId2,
                'outlet_id' => $outletId2,
                'ingredient_id' => $ingredientId3,
                'current_stock' => 5000.00, // 5 kg
                'minimum_stock_level' => 1000.00,
                'last_restocked_at' => Carbon::now()->subDays(2),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
        $this->command->info('Inventory Items seeded!');

        // --- 23. Recipes ---
        $recipeId1 = Str::uuid(); // Resep Ayam Geprek
        $recipeId2 = Str::uuid(); // Resep Es Kopi Susu

        DB::table('recipes')->insert([
            [
                'id' => $recipeId1,
                'company_id' => $companyId1,
                'menu_item_id' => $menuItemId1,
                'preparation_instructions' => 'Goreng ayam hingga crispy, lalu geprek dengan sambal bawang.',
                'estimated_prep_time_minutes' => 8,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => $recipeId2,
                'company_id' => $companyId2,
                'menu_item_id' => $menuItemId2,
                'preparation_instructions' => 'Campurkan espresso, susu, dan gula aren. Aduk rata.',
                'estimated_prep_time_minutes' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
        $this->command->info('Recipes seeded!');

        // --- 24. Recipe Ingredients ---
        DB::table('recipe_ingredients')->insert([
            ['recipe_id' => $recipeId1, 'ingredient_id' => $ingredientId1, 'quantity_required' => 0.2], // 200 gram ayam
            ['recipe_id' => $recipeId1, 'ingredient_id' => $ingredientId2, 'quantity_required' => 0.15], // 150 gram beras
            ['recipe_id' => $recipeId2, 'ingredient_id' => $ingredientId3, 'quantity_required' => 20.0], // 20 gram biji kopi
        ]);
        $this->command->info('Recipe Ingredients seeded!');

        // --- 25. Inventory Movements ---
        $movementId1 = Str::uuid();
        $movementId2 = Str::uuid();

        DB::table('inventory_movements')->insert([
            [
                'id' => $movementId1,
                'company_id' => $companyId1,
                'ingredient_id' => $ingredientId1,
                'outlet_id_from' => null, // Purchase doesn't have 'from' outlet
                'outlet_id_to' => $outletId1,
                'quantity' => 10.00,
                'type' => 'Purchase',
                'reference_id' => 'PO2024001',
                'notes' => 'Pembelian mingguan',
                'movement_at' => Carbon::now()->subDays(7),
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(7),
            ],
            [
                'id' => $movementId2,
                'company_id' => $companyId2,
                'ingredient_id' => $ingredientId3,
                'outlet_id_from' => $outletId2,
                'outlet_id_to' => null, // Sale consumption doesn't have 'to' outlet
                'quantity' => 0.02, // 20 gram for one coffee
                'type' => 'Sale_Consumption',
                'reference_id' => $orderId2,
                'notes' => 'Konsumsi untuk pesanan kopi',
                'movement_at' => Carbon::now()->subHours(1),
                'created_at' => Carbon::now()->subHours(1),
                'updated_at' => Carbon::now()->subHours(1),
            ],
        ]);
        $this->command->info('Inventory Movements seeded!');

        // --- 26. Employee Attendances ---
        $attendanceId1 = Str::uuid();
        $attendanceId2 = Str::uuid();

        DB::table('employee_attendances')->insert([
            [
                'id' => $attendanceId1,
                'company_id' => $companyId1,
                'user_id' => $userId2, // Cashier
                'outlet_id' => $outletId1,
                'check_in_time' => Carbon::parse('2025-06-17 08:55:00'),
                'check_out_time' => Carbon::parse('2025-06-17 17:05:00'),
                'status' => 'On_Time',
                'location_coordinates' => '{-6.9175,107.6191}',
                'created_at' => Carbon::parse('2025-06-17 08:55:00'),
                'updated_at' => Carbon::parse('2025-06-17 17:05:00'),
            ],
            [
                'id' => $attendanceId2,
                'company_id' => $companyId2,
                'user_id' => $userId3, // Owner/Cashier
                'outlet_id' => $outletId2,
                'check_in_time' => Carbon::parse('2025-06-17 09:10:00'), // Late
                'check_out_time' => Carbon::parse('2025-06-17 18:00:00'),
                'status' => 'Late',
                'location_coordinates' => '{-6.2088,106.8456}',
                'created_at' => Carbon::parse('2025-06-17 09:10:00'),
                'updated_at' => Carbon::parse('2025-06-17 18:00:00'),
            ],
        ]);
        $this->command->info('Employee Attendances seeded!');

        // --- 27. Employee Schedules ---
        $scheduleId1 = Str::uuid();
        $scheduleId2 = Str::uuid();

        DB::table('employee_schedules')->insert([
            [
                'id' => $scheduleId1,
                'company_id' => $companyId1,
                'user_id' => $userId2,
                'outlet_id' => $outletId1,
                'schedule_date' => '2025-06-17',
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'shift_type' => 'Pagi',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => $scheduleId2,
                'company_id' => $companyId2,
                'user_id' => $userId3,
                'outlet_id' => $outletId2,
                'schedule_date' => '2025-06-17',
                'start_time' => '08:00:00',
                'end_time' => '18:00:00',
                'shift_type' => 'Siang',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
        $this->command->info('Employee Schedules seeded!');

        // --- 28. Payroll Runs ---
        $payrollRunId1 = Str::uuid();

        DB::table('payroll_runs')->insert([
            [
                'id' => $payrollRunId1,
                'company_id' => $companyId1,
                'payroll_start_date' => '2025-05-01',
                'payroll_end_date' => '2025-05-31',
                'status' => 'Processed',
                'processed_by_user_id' => $userId1, // Company Owner
                'total_payout_amount' => 5000000.00,
                'notes' => 'Payroll bulan Mei 2025',
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(10),
            ],
        ]);
        $this->command->info('Payroll Runs seeded!');

        // --- 29. Pay Slips ---
        $paySlipId1 = Str::uuid();

        DB::table('pay_slips')->insert([
            [
                'id' => $paySlipId1,
                'payroll_run_id' => $payrollRunId1,
                'user_id' => $userId2, // Cashier
                'base_salary' => 3000000.00,
                'hourly_wage' => null,
                'hours_worked' => null,
                'overtime_hours' => 5.00,
                'overtime_pay' => 150000.00,
                'allowances_json' => json_encode(['transport' => 100000, 'meal' => 100000]),
                'deductions_json' => json_encode(['bpjs' => 50000]),
                'net_pay' => 3100000.00,
                'slip_generated_at' => Carbon::now()->subDays(9),
                'created_at' => Carbon::now()->subDays(9),
                'updated_at' => Carbon::now()->subDays(9),
            ],
        ]);
        $this->command->info('Pay Slips seeded!');

        // --- 30. Reservations ---
        $reservationId1 = Str::uuid();

        DB::table('reservations')->insert([
            [
                'id' => $reservationId1,
                'company_id' => $companyId1,
                'outlet_id' => $outletId1,
                'customer_id' => $customerId1,
                'table_id' => $tableId1,
                'reservation_time' => Carbon::now()->addDays(2)->setHour(19)->setMinute(0)->setSecond(0),
                'end_time' => Carbon::now()->addDays(2)->setHour(21)->setMinute(0)->setSecond(0),
                'number_of_guests' => 3,
                'status' => 'Confirmed',
                'notes' => 'Ulang tahun',
                'deposit_amount' => 50000.00,
                'confirmation_token' => Str::random(16),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
        $this->command->info('Reservations seeded!');

        // --- 31. Expenses ---
        $expenseId1 = Str::uuid();

        DB::table('expenses')->insert([
            [
                'id' => $expenseId1,
                'company_id' => $companyId1,
                'outlet_id' => $outletId1,
                'description' => 'Pembayaran listrik bulan Juni',
                'amount' => 1500000.00,
                'expense_date' => '2025-06-05',
                'category' => 'Utilities',
                'payment_method' => 'Bank Transfer',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
        $this->command->info('Expenses seeded!');

        // --- 32. Affiliates ---
        $affiliateId1 = Str::uuid();

        DB::table('affiliates')->insert([
            [
                'id' => $affiliateId1,
                'user_id' => $userId4, // Restomax Super Admin juga bisa jadi affiliate
                'full_name' => 'Affiliate Marketing',
                'email' => 'affiliate@example.com',
                'phone' => '089988776655',
                'bank_account_number' => '1234567890',
                'bank_name' => 'Bank ABC',
                'referral_code' => 'RESTOMAX123',
                'unique_link_token' => Str::random(32),
                'commission_rate' => 0.15, // 15%
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
        $this->command->info('Affiliates seeded!');

        // --- 33. Affiliate Payout Runs (Initially empty, will be updated) ---
        $payoutRunIdAffiliate = Str::uuid();
        DB::table('affiliate_payout_runs')->insert([
            [
                'id' => $payoutRunIdAffiliate,
                'payout_date' => Carbon::now()->toDateString(),
                'total_amount' => 0.00, // Will be updated
                'status' => 'Pending',
                'processed_by_user_id' => $userId4,
                'notes' => 'Initial payout run',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
        $this->command->info('Affiliate Payout Runs seeded!');


        // --- 34. Affiliate Referrals ---
        $referralId1 = Str::uuid();

        DB::table('affiliate_referrals')->insert([
            [
                'id' => $referralId1,
                'affiliate_id' => $affiliateId1,
                'referred_company_id' => $companyId1,
                'referral_date' => Carbon::now()->subMonths(4),
                'commission_percentage_applied' => 0.10, // Example: different rate for initial referral
                'calculated_commission_amount' => 100000.00,
                'payout_status' => 'Paid',
                'payout_run_id' => $payoutRunIdAffiliate, // Link to the payout run
                'created_at' => Carbon::now()->subMonths(4),
                'updated_at' => Carbon::now()->subMonths(4),
            ],
        ]);
        $this->command->info('Affiliate Referrals seeded!');

        // --- 35. Affiliate Recurring Commissions ---
        $recurringCommissionId1 = Str::uuid();

        DB::table('affiliate_recurring_commissions')->insert([
            [
                'id' => $recurringCommissionId1,
                'affiliate_id' => $affiliateId1,
                'referred_company_id' => $companyId1,
                'subscription_id' => $subscriptionId1,
                'commission_amount' => 9900.00, // 10% dari 99,000
                'billing_period_start' => Carbon::now()->subMonth()->startOfMonth()->toDateString(),
                'billing_period_end' => Carbon::now()->subMonth()->endOfMonth()->toDateString(),
                'payout_status' => 'Pending', // Still pending until updated below
                'payout_run_id' => null,
                'created_at' => Carbon::now()->subMonth(),
                'updated_at' => Carbon::now()->subMonth(),
            ],
        ]);
        $this->command->info('Affiliate Recurring Commissions seeded!');

        // --- Update Affiliate Payout Run with total amount & status for existing commissions ---
        $totalPayoutForRun = DB::table('affiliate_referrals')
            ->where('payout_run_id', $payoutRunIdAffiliate)
            ->sum('calculated_commission_amount');

        // You might have other commissions that should be part of this run.
        // For example, if recurringCommissionId1 was to be paid in this run:
        $totalPayoutForRun += 9900.00; // Add the recurring commission manually for this example

        DB::table('affiliate_payout_runs')
            ->where('id', $payoutRunIdAffiliate)
            ->update([
                'total_amount' => $totalPayoutForRun,
                'status' => 'Completed',
                'updated_at' => Carbon::now()
            ]);

        // Update recurring commission with payout run ID
        DB::table('affiliate_recurring_commissions')
            ->where('id', $recurringCommissionId1)
            ->update([
                'payout_run_id' => $payoutRunIdAffiliate,
                'payout_status' => 'Paid',
                'updated_at' => Carbon::now()
            ]);

        $this->command->info('Updated Affiliate Payouts with total amounts and linked commissions!');
        $this->command->info('All tables seeded successfully!');
    }
}