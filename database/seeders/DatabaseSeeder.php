<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\SubscriptionPlan;
use App\Models\CompanySubscription;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Outlet;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\MenuItemOption;
use App\Models\MenuItemOptionValue;
use App\Models\Table;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Ingredient;
use App\Models\InventoryItem;
use App\Models\Recipe;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeSchedule;
use App\Models\PayrollRun;
use App\Models\PaySlip;
use App\Models\Reservation;
use App\Models\Expense;
use App\Models\Affiliate;
use App\Models\AffiliatePayoutRun;
// use App\Models\AffiliateReferral; // Ini akan dibuat saat ada company subscription
// use App\Models\AffiliateRecurringCommission; // Ini akan dibuat saat ada company subscription

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- 1. Roles ---
        $this->command->info('Seeding Roles...');
        $rolesData = [
            ['id' => (string) Str::uuid(), 'name' => 'Super Admin', 'description' => 'Administrator Restomax, memiliki akses penuh platform.'],
            ['id' => (string) Str::uuid(), 'name' => 'Company Owner', 'description' => 'Pemilik bisnis F&B yang berlangganan Restomax.'],
            ['id' => (string) Str::uuid(), 'name' => 'Manager', 'description' => 'Manajer operasional di outlet tertentu.'],
            ['id' => (string) Str::uuid(), 'name' => 'Cashier', 'description' => 'Bertanggung jawab atas transaksi penjualan.'],
            ['id' => (string) Str::uuid(), 'name' => 'Chef', 'description' => 'Bertanggung jawab atas persiapan makanan di dapur.'],
            ['id' => (string) Str::uuid(), 'name' => 'Waiter', 'description' => 'Melayani pelanggan dan mencatat pesanan.'],
            ['id' => (string) Str::uuid(), 'name' => 'Barista', 'description' => 'Menyiapkan minuman di area bar.'],
            ['id' => (string) Str::uuid(), 'name' => 'Warehouse Staff', 'description' => 'Mengelola inventori dan stok bahan baku.'],
        ];
        foreach ($rolesData as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
        $this->command->info('Roles seeded.');

        // --- 2. Permissions ---
        $this->command->info('Seeding Permissions...');
        $permissionsData = [
            // Company Management
            ['id' => (string) Str::uuid(), 'name' => 'manage_company_profile', 'description' => 'Mengelola profil perusahaan'],
            ['id' => (string) Str::uuid(), 'name' => 'manage_outlets', 'description' => 'Menambah/mengedit outlet'],
            ['id' => (string) Str::uuid(), 'name' => 'manage_subscriptions', 'description' => 'Mengelola paket langganan'],

            // User & Role Management
            ['id' => (string) Str::uuid(), 'name' => 'manage_users', 'description' => 'Menambah/mengedit pengguna sistem'],
            ['id' => (string) Str::uuid(), 'name' => 'assign_roles', 'description' => 'Memberikan peran kepada pengguna'],

            // POS & Order Management
            ['id' => (string) Str::uuid(), 'name' => 'process_pos_transactions', 'description' => 'Memproses transaksi di POS'],
            ['id' => (string) Str::uuid(), 'name' => 'view_pos_history', 'description' => 'Melihat riwayat transaksi POS'],
            ['id' => (string) Str::uuid(), 'name' => 'manage_menu_items', 'description' => 'Mengelola menu dan kategori'],
            ['id' => (string) Str::uuid(), 'name' => 'apply_discounts_promotions', 'description' => 'Menerapkan diskon dan promosi'],
            ['id' => (string) Str::uuid(), 'name' => 'void_refund_transactions', 'description' => 'Membatalkan/mengembalikan transaksi'],
            ['id' => (string) Str::uuid(), 'name' => 'manage_tables', 'description' => 'Mengelola meja dan layout'],

            // Inventory & Kitchen Management
            ['id' => (string) Str::uuid(), 'name' => 'manage_ingredients', 'description' => 'Mengelola bahan baku'],
            ['id' => (string) Str::uuid(), 'name' => 'manage_inventory_stock', 'description' => 'Mengelola stok inventori'],
            ['id' => (string) Str::uuid(), 'name' => 'manage_recipes', 'description' => 'Mengelola resep makanan/minuman'],
            ['id' => (string) Str::uuid(), 'name' => 'access_kds', 'description' => 'Mengakses Kitchen Display System'],
            ['id' => (string) Str::uuid(), 'name' => 'update_kds_status', 'description' => 'Mengubah status pesanan di KDS'],

            // HRM & Payroll
            ['id' => (string) Str::uuid(), 'name' => 'manage_employee_attendance', 'description' => 'Mengelola absensi karyawan'],
            ['id' => (string) Str::uuid(), 'name' => 'manage_employee_schedules', 'description' => 'Mengelola jadwal karyawan'],
            ['id' => (string) Str::uuid(), 'name' => 'process_payroll', 'description' => 'Memproses gaji karyawan'],
            ['id' => (string) Str::uuid(), 'name' => 'view_employee_payslips', 'description' => 'Melihat slip gaji karyawan'],

            // Reporting & Analytics
            ['id' => (string) Str::uuid(), 'name' => 'view_sales_reports', 'description' => 'Melihat laporan penjualan'],
            ['id' => (string) Str::uuid(), 'name' => 'view_inventory_reports', 'description' => 'Melihat laporan inventori'],
            ['id' => (string) Str::uuid(), 'name' => 'view_payroll_reports', 'description' => 'Melihat laporan gaji'],
            ['id' => (string) Str::uuid(), 'name' => 'view_financial_reports', 'description' => 'Melihat laporan keuangan dasar'],

            // Reservations
            ['id' => (string) Str::uuid(), 'name' => 'manage_reservations', 'description' => 'Mengelola reservasi pelanggan'],

            // Customers & Marketing
            ['id' => (string) Str::uuid(), 'name' => 'manage_customers_crm', 'description' => 'Mengelola data pelanggan dan CRM'],
            ['id' => (string) Str::uuid(), 'name' => 'manage_loyalty_programs', 'description' => 'Mengelola program loyalitas'],
            ['id' => (string) Str::uuid(), 'name' => 'send_marketing_campaigns', 'description' => 'Mengirim kampanye pemasaran'],

            // Restomax Internal / Super Admin specific
            ['id' => (string) Str::uuid(), 'name' => 'super_admin_access', 'description' => 'Akses penuh sebagai Super Admin Restomax'],
            ['id' => (string) Str::uuid(), 'name' => 'manage_affiliates', 'description' => 'Mengelola program afiliasi Restomax'],
            ['id' => (string) Str::uuid(), 'name' => 'process_affiliate_payouts', 'description' => 'Memproses pembayaran komisi afiliasi'],
        ];
        foreach ($permissionsData as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']], $permission);
        }
        $this->command->info('Permissions seeded.');

        // --- 3. Role-Permissions (Hubungkan Role dengan Permission) ---
        $this->command->info('Attaching Permissions to Roles...');
        $roles = Role::all()->keyBy('name');
        $permissions = Permission::all()->keyBy('name');

        // Super Admin gets all permissions
        $superAdmin = $roles['Super Admin'];
        $superAdmin->permissions()->sync($permissions->pluck('id'));

        // Company Owner gets most management permissions
        $companyOwnerPermissions = [
            'manage_company_profile', 'manage_outlets', 'manage_subscriptions',
            'manage_users', 'assign_roles',
            'process_pos_transactions', 'view_pos_history', 'manage_menu_items', 'apply_discounts_promotions', 'void_refund_transactions', 'manage_tables',
            'manage_ingredients', 'manage_inventory_stock', 'manage_recipes', 'access_kds', 'update_kds_status',
            'manage_employee_attendance', 'manage_employee_schedules', 'process_payroll', 'view_employee_payslips',
            'view_sales_reports', 'view_inventory_reports', 'view_payroll_reports', 'view_financial_reports',
            'manage_reservations', 'manage_customers_crm', 'manage_loyalty_programs', 'send_marketing_campaigns',
        ];
        $companyOwner = $roles['Company Owner'];
        $companyOwner->permissions()->sync($permissions->whereIn('name', $companyOwnerPermissions)->pluck('id'));

        // Manager gets operational and reporting permissions
        $managerPermissions = [
            'view_pos_history', 'manage_menu_items', 'apply_discounts_promotions', 'manage_tables',
            'manage_inventory_stock', 'manage_recipes', 'access_kds', 'update_kds_status',
            'manage_employee_attendance', 'manage_employee_schedules', 'view_employee_payslips',
            'view_sales_reports', 'view_inventory_reports',
            'manage_reservations', 'manage_customers_crm', 'manage_loyalty_programs',
        ];
        $manager = $roles['Manager'];
        $manager->permissions()->sync($permissions->whereIn('name', $managerPermissions)->pluck('id'));

        // Cashier gets POS specific permissions
        $cashierPermissions = [
            'process_pos_transactions', 'view_pos_history', 'apply_discounts_promotions',
        ];
        $cashier = $roles['Cashier'];
        $cashier->permissions()->sync($permissions->whereIn('name', $cashierPermissions)->pluck('id'));

        // Chef gets KDS and recipe related permissions
        $chefPermissions = [
            'access_kds', 'update_kds_status', 'manage_recipes',
        ];
        $chef = $roles['Chef'];
        $chef->permissions()->sync($permissions->whereIn('name', $chefPermissions)->pluck('id'));

        // Waiter gets order taking and table management permissions
        $waiterPermissions = [
            'process_pos_transactions', 'manage_tables', 'manage_reservations',
        ];
        $waiter = $roles['Waiter'];
        $waiter->permissions()->sync($permissions->whereIn('name', $waiterPermissions)->pluck('id'));

        // Barista gets KDS access for bar station
        $baristaPermissions = [
            'access_kds', 'update_kds_status',
        ];
        $barista = $roles['Barista'];
        $barista->permissions()->sync($permissions->whereIn('name', $baristaPermissions)->pluck('id'));

        // Warehouse Staff gets inventory specific permissions
        $warehouseStaffPermissions = [
            'manage_ingredients', 'manage_inventory_stock', 'view_inventory_reports',
        ];
        $warehouseStaff = $roles['Warehouse Staff'];
        $warehouseStaff->permissions()->sync($permissions->whereIn('name', $warehouseStaffPermissions)->pluck('id'));
        $this->command->info('Permissions attached to Roles.');


        // --- 4. Subscription Plans ---
        $this->command->info('Seeding Subscription Plans...');
        $planIdMicro = (string) Str::uuid();
        $planIdCafe = (string) Str::uuid();
        $planIdResto = (string) Str::uuid();

        $plansData = [
            [
                'id' => $planIdMicro,
                'name' => 'Paket Mikro',
                'description' => 'Ideal untuk burjo dan warung kecil. Fitur dasar POS & laporan.',
                'base_price_monthly' => 50000.00,
                'base_price_yearly' => 500000.00,
                'features_included' => json_encode([
                    'pos_basic' => true, 'menu_management' => true, 'sales_reports_daily' => true,
                    'qr_order_simple' => true, 'customer_data_basic' => true,
                ]),
                'is_active' => true,
            ],
            [
                'id' => $planIdCafe,
                'name' => 'Paket Cafe/UMKM',
                'description' => 'Solusi lengkap untuk kafe dan UMKM restoran. Termasuk inventori & absensi.',
                'base_price_monthly' => 150000.00,
                'base_price_yearly' => 1500000.00,
                'features_included' => json_encode([
                    'pos_basic' => true, 'menu_management' => true, 'sales_reports_daily' => true,
                    'qr_order_simple' => true, 'customer_data_basic' => true,
                    'inventory_basic' => true, 'payroll_basic' => true, 'kds_basic' => true,
                    'table_management_basic' => true, 'employee_attendance' => true,
                ]),
                'is_active' => true,
            ],
            [
                'id' => $planIdResto,
                'name' => 'Paket Restoran/Menengah',
                'description' => 'Fitur komprehensif untuk restoran menengah. Termasuk HRM & reservasi penuh.',
                'base_price_monthly' => 350000.00,
                'base_price_yearly' => 3500000.00,
                'features_included' => json_encode([
                    'pos_basic' => true, 'menu_management' => true, 'sales_reports_daily' => true,
                    'qr_order_simple' => true, 'customer_data_basic' => true,
                    'inventory_basic' => true, 'payroll_basic' => true, 'kds_basic' => true,
                    'table_management_basic' => true, 'employee_attendance' => true,
                    'pos_advanced' => true, 'inventory_advanced' => true, 'payroll_advanced' => true,
                    'reservation_basic' => true, 'kds_advanced' => true,
                    'multi_outlet_management' => true, 'advanced_reports' => true,
                    'crm_advanced' => true, 'marketing_campaigns' => true,
                ]),
                'is_active' => true,
            ],
        ];
        foreach ($plansData as $planData) {
            SubscriptionPlan::firstOrCreate(['name' => $planData['name']], $planData);
        }
        $this->command->info('Subscription Plans seeded.');

        // --- 5. Companies (Contoh) ---
        $this->command->info('Seeding Companies...');
        $companyId1 = (string) Str::uuid();
        $companyId2 = (string) Str::uuid();

        // Data dummy untuk subscription
        $subEndDate1 = now()->addYears(1);
        $subEndDate2 = now()->addMonths(6);

        // Pastikan SubscriptionPlan tersedia
        $planCafe = SubscriptionPlan::where('name', 'Paket Cafe/UMKM')->first();
        $planResto = SubscriptionPlan::where('name', 'Paket Restoran/Menengah')->first();

        $company1 = Company::firstOrCreate(
            ['name' => 'Kopi Senja'],
            [
                'id' => $companyId1,
                'business_type' => 'Cafe',
                'scale' => 'UMKM_Kecil',
                'address' => 'Jl. Pahlawan No. 123, Semarang',
                'phone' => '081234567890',
                'email' => 'kopisenja@example.com',
                'operating_hours' => json_encode(['monday' => '08:00-22:00', 'tuesday' => '08:00-22:00', 'sunday' => '09:00-21:00']),
                'is_active' => true,
            ]
        );

        $company2 = Company::firstOrCreate(
            ['name' => 'Restoran Seafood Jaya'],
            [
                'id' => $companyId2,
                'business_type' => 'Restaurant',
                'scale' => 'Menengah',
                'address' => 'Jl. Pemuda No. 45, Semarang',
                'phone' => '082123456789',
                'email' => 'seafoodjaya@example.com',
                'operating_hours' => json_encode(['monday' => '11:00-23:00', 'tuesday' => '11:00-23:00', 'sunday' => '11:00-23:00']),
                'is_active' => true,
            ]
        );
        $this->command->info('Companies seeded.');

        // --- 6. Company Subscriptions (Contoh) ---
        $this->command->info('Seeding Company Subscriptions...');
        $sub1 = CompanySubscription::firstOrCreate(
            ['company_id' => $company1->id, 'subscription_plan_id' => $planCafe->id],
            [
                'id' => (string) Str::uuid(),
                'start_date' => now()->subMonths(2),
                'end_date' => $subEndDate1,
                'price_locked_monthly' => $planCafe->base_price_monthly,
                'features_locked' => $planCafe->features_included,
                'status' => 'Active',
                'last_billed_at' => now()->subMonths(1),
                'next_billing_at' => now()->addDays(28),
            ]
        );
        // Update current_subscription_id di tabel companies
        $company1->current_subscription_id = $sub1->id;
        $company1->save();

        $sub2 = CompanySubscription::firstOrCreate(
            ['company_id' => $company2->id, 'subscription_plan_id' => $planResto->id],
            [
                'id' => (string) Str::uuid(),
                'start_date' => now()->subMonths(1),
                'end_date' => $subEndDate2,
                'price_locked_monthly' => $planResto->base_price_monthly,
                'features_locked' => $planResto->features_included,
                'status' => 'Active',
                'last_billed_at' => now()->subDays(15),
                'next_billing_at' => now()->addDays(15),
            ]
        );
        $company2->current_subscription_id = $sub2->id;
        $company2->save();
        $this->command->info('Company Subscriptions seeded.');

        // --- 7. Users ---
        $this->command->info('Seeding Users...');
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        $ownerRole = Role::where('name', 'Company Owner')->first();
        $managerRole = Role::where('name', 'Manager')->first();
        $cashierRole = Role::where('name', 'Cashier')->first();
        $chefRole = Role::where('name', 'Chef')->first();

        // Restomax Super Admin
        $restomaxAdmin = User::firstOrCreate(
            ['email' => 'admin@restomax.com'],
            [
                'id' => (string) Str::uuid(),
                'company_id' => null, // Super Admin tidak terikat ke perusahaan tertentu
                'name' => 'Restomax Admin',
                'full_name' => 'Restomax Admin',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        $restomaxAdmin->roles()->syncWithoutDetaching([
            ['role_id' => $superAdminRole->id, 'company_id' => null] // Relasi untuk super admin
        ]);


        // Company Owner 1
        $owner1 = User::firstOrCreate(
            ['email' => 'budi@kopisenja.com'],
            [
                'id' => (string) Str::uuid(),
                'company_id' => $company1->id,
                'name' => 'Budi Santoso',
                'full_name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'phone' => '081234567891',
                'is_active' => true,
            ]
        );
        $owner1->roles()->syncWithoutDetaching([
            ['role_id' => $ownerRole->id, 'company_id' => $company1->id]
        ]);

        // Manager 1 (Kopi Senja)
        $manager1 = User::firstOrCreate(
            ['email' => 'dinda@kopisenja.com'],
            [
                'id' => (string) Str::uuid(),
                'company_id' => $company1->id,
                'name' => 'Dinda Ayu',
                'full_name' => 'Dinda Ayu',
                'password' => Hash::make('password'),
                'phone' => '081234567892',
                'is_active' => true,
            ]
        );
        $manager1->roles()->syncWithoutDetaching([
            ['role_id' => $managerRole->id, 'company_id' => $company1->id]
        ]);

        // Cashier 1 (Kopi Senja)
        $cashier1 = User::firstOrCreate(
            ['email' => 'adi@kopisenja.com'],
            [
                'id' => (string) Str::uuid(),
                'company_id' => $company1->id,
                'name' => 'Adi Nugroho',
                'full_name' => 'Adi Nugroho',
                'password' => Hash::make('password'),
                'phone' => '081234567893',
                'is_active' => true,
            ]
        );
        $cashier1->roles()->syncWithoutDetaching([
            ['role_id' => $cashierRole->id, 'company_id' => $company1->id]
        ]);

        // Company Owner 2
        $owner2 = User::firstOrCreate(
            ['email' => 'citra@seafoodjaya.com'],
            [
                'id' => (string) Str::uuid(),
                'company_id' => $company2->id,
                'name' => 'Citra Dewi',
                'full_name' => 'Citra Dewi',
                'password' => Hash::make('password'),
                'phone' => '082123456781',
                'is_active' => true,
            ]
        );
        $owner2->roles()->syncWithoutDetaching([
            ['role_id' => $ownerRole->id, 'company_id' => $company2->id]
        ]);

        // Chef 1 (Restoran Seafood Jaya)
        $chef1 = User::firstOrCreate(
            ['email' => 'galih@seafoodjaya.com'],
            [
                'id' => (string) Str::uuid(),
                'company_id' => $company2->id,
                'name' => 'Galih Purnomo',
                'full_name' => 'Galih Purnomo',
                'password' => Hash::make('password'),
                'phone' => '082123456782',
                'is_active' => true,
            ]
        );
        $chef1->roles()->syncWithoutDetaching([
            ['role_id' => $chefRole->id, 'company_id' => $company2->id]
        ]);
        $this->command->info('Users seeded.');

        // --- 8. User Roles (Pivot Table, sudah ditangani di atas melalui $user->roles()->syncWithoutDetaching) ---
        $this->command->info('User roles synchronized during user seeding.');

        // --- 9. Outlets ---
        $this->command->info('Seeding Outlets...');
        $outletId1 = (string) Str::uuid(); // Kopi Senja - Pusat
        $outletId2 = (string) Str::uuid(); // Restoran Seafood Jaya - Utama

        $outlet1 = Outlet::firstOrCreate(
            ['company_id' => $company1->id, 'name' => 'Kopi Senja Pusat'],
            [
                'id' => $outletId1,
                'address' => 'Jl. Pahlawan No. 123, Semarang',
                'phone' => '081234567890',
                'email' => 'pusat@kopisenja.com',
                'is_active' => true,
            ]
        );

        $outlet2 = Outlet::firstOrCreate(
            ['company_id' => $company2->id, 'name' => 'Seafood Jaya Cabang Utama'],
            [
                'id' => $outletId2,
                'address' => 'Jl. Pemuda No. 45, Semarang',
                'phone' => '082123456789',
                'email' => 'utama@seafoodjaya.com',
                'is_active' => true,
            ]
        );
        $this->command->info('Outlets seeded.');

        // --- 10. Categories ---
        $this->command->info('Seeding Categories...');
        $categoryId1 = (string) Str::uuid(); // Kopi Senja - Minuman Kopi
        $categoryId2 = (string) Str::uuid(); // Kopi Senja - Makanan Ringan
        $categoryId3 = (string) Str::uuid(); // Seafood Jaya - Makanan Laut
        $categoryId4 = (string) Str::uuid(); // Seafood Jaya - Minuman

        Category::firstOrCreate(['company_id' => $company1->id, 'name' => 'Minuman Kopi'], [
            'id' => $categoryId1, 'outlet_id' => $outlet1->id, 'description' => 'Berbagai jenis kopi', 'display_order' => 1, 'is_active' => true,
        ]);
        Category::firstOrCreate(['company_id' => $company1->id, 'name' => 'Makanan Ringan'], [
            'id' => $categoryId2, 'outlet_id' => $outlet1->id, 'description' => 'Camilan dan pastry', 'display_order' => 2, 'is_active' => true,
        ]);
        Category::firstOrCreate(['company_id' => $company2->id, 'name' => 'Makanan Laut'], [
            'id' => $categoryId3, 'outlet_id' => $outlet2->id, 'description' => 'Olahan seafood segar', 'display_order' => 1, 'is_active' => true,
        ]);
        Category::firstOrCreate(['company_id' => $company2->id, 'name' => 'Minuman', 'outlet_id' => $outlet2->id], [
            'id' => $categoryId4, 'description' => 'Minuman segar dan jus', 'display_order' => 2, 'is_active' => true,
        ]);
        $this->command->info('Categories seeded.');

        // --- 11. Menu Items ---
        $this->command->info('Seeding Menu Items...');
        $menuItem1 = MenuItem::firstOrCreate(['company_id' => $company1->id, 'name' => 'Espresso'], [
            'id' => (string) Str::uuid(), 'outlet_id' => $outlet1->id, 'category_id' => $categoryId1,
            'description' => 'Kopi hitam pekat', 'price' => 25000.00, 'is_available' => true, 'preparation_time_minutes' => 3,
        ]);
        $menuItem2 = MenuItem::firstOrCreate(['company_id' => $company1->id, 'name' => 'Croissant'], [
            'id' => (string) Str::uuid(), 'outlet_id' => $outlet1->id, 'category_id' => $categoryId2,
            'description' => 'Pastry renyah', 'price' => 18000.00, 'is_available' => true, 'preparation_time_minutes' => 2,
        ]);
        $menuItem3 = MenuItem::firstOrCreate(['company_id' => $company2->id, 'name' => 'Udang Bakar'], [
            'id' => (string) Str::uuid(), 'outlet_id' => $outlet2->id, 'category_id' => $categoryId3,
            'description' => 'Udang segar dibakar dengan bumbu rahasia', 'price' => 75000.00, 'is_available' => true, 'preparation_time_minutes' => 15,
        ]);
        $this->command->info('Menu Items seeded.');

        // --- 12. Menu Item Options ---
        $this->command->info('Seeding Menu Item Options...');
        $optionLevelPedas = MenuItemOption::firstOrCreate(['company_id' => $company2->id, 'name' => 'Level Pedas'], [
            'id' => (string) Str::uuid(), 'type' => 'single_select', 'is_required' => false,
        ]);
        $optionAddOn = MenuItemOption::firstOrCreate(['company_id' => $company1->id, 'name' => 'Add-on Kopi'], [
            'id' => (string) Str::uuid(), 'type' => 'multi_select', 'is_required' => false,
        ]);
        $this->command->info('Menu Item Options seeded.');

        // --- 13. Menu Item Option Values ---
        $this->command->info('Seeding Menu Item Option Values...');
        $valueTidakPedas = MenuItemOptionValue::firstOrCreate(['menu_item_option_id' => $optionLevelPedas->id, 'value' => 'Tidak Pedas'], [
            'id' => (string) Str::uuid(), 'price_adjustment' => 0.00,
        ]);
        $valueSedang = MenuItemOptionValue::firstOrCreate(['menu_item_option_id' => $optionLevelPedas->id, 'value' => 'Sedang'], [
            'id' => (string) Str::uuid(), 'price_adjustment' => 0.00,
        ]);
        $valueSusuOat = MenuItemOptionValue::firstOrCreate(['menu_item_option_id' => $optionAddOn->id, 'value' => 'Susu Oat'], [
            'id' => (string) Str::uuid(), 'price_adjustment' => 5000.00,
        ]);
        $this->command->info('Menu Item Option Values seeded.');

        // --- 14. Menu Item Option Associations ---
        $this->command->info('Associating Menu Items with Options...');
        // Udang Bakar bisa punya Level Pedas
        $menuItem3->options()->syncWithoutDetaching([$optionLevelPedas->id]);
        // Espresso bisa punya Add-on Kopi
        $menuItem1->options()->syncWithoutDetaching([$optionAddOn->id]);
        $this->command->info('Menu Item Option Associations seeded.');

        // --- 15. Tables ---
        $this->command->info('Seeding Tables...');
        $table1 = Table::firstOrCreate(['outlet_id' => $outlet1->id, 'table_number' => 'A1'], [
            'id' => (string) Str::uuid(), 'capacity' => 2, 'qr_code_url' => 'http://restomax.com/qr/kopisenja/A1', 'is_active' => true,
        ]);
        $table2 = Table::firstOrCreate(['outlet_id' => $outlet1->id, 'table_number' => 'B2'], [
            'id' => (string) Str::uuid(), 'capacity' => 4, 'qr_code_url' => 'http://restomax.com/qr/kopisenja/B2', 'is_active' => true,
        ]);
        $table3 = Table::firstOrCreate(['outlet_id' => $outlet2->id, 'table_number' => 'Meja Laut 1'], [
            'id' => (string) Str::uuid(), 'capacity' => 6, 'qr_code_url' => 'http://restomax.com/qr/seafoodjaya/ML1', 'is_active' => true,
        ]);
        $this->command->info('Tables seeded.');

        // --- 16. Customers ---
        $this->command->info('Seeding Customers...');
        $customer1 = Customer::firstOrCreate(['company_id' => $company1->id, 'email' => 'pelanggan1@gmail.com'], [
            'id' => (string) Str::uuid(), 'full_name' => 'Ani Wijaya', 'phone' => '081112223333', 'loyalty_points' => 150,
        ]);
        $customer2 = Customer::firstOrCreate(['company_id' => $company2->id, 'phone' => '082233445566'], [
            'id' => (string) Str::uuid(), 'full_name' => 'Bambang Susilo', 'email' => 'bambang@gmail.com', 'loyalty_points' => 300,
        ]);
        $this->command->info('Customers seeded.');


        // --- 17. Orders ---
        $this->command->info('Seeding Orders...');
        $order1 = Order::firstOrCreate(['company_id' => $company1->id, 'order_time' => now()->subDays(5)], [
            'id' => (string) Str::uuid(), 'outlet_id' => $outlet1->id, 'customer_id' => $customer1->id,
            'user_id' => $cashier1->id, 'table_id' => $table1->id, 'order_type' => 'Dine-in',
            'status' => 'Completed', 'total_amount' => 43000.00, 'payment_status' => 'Paid', 'order_time' => now()->subDays(5),
        ]);
        $order2 = Order::firstOrCreate(['company_id' => $company2->id, 'order_time' => now()->subDays(2)], [
            'id' => (string) Str::uuid(), 'outlet_id' => $outlet2->id, 'customer_id' => $customer2->id,
            'user_id' => $chef1->id, // Assuming chef can also take orders in some cases or just for demo
            'table_id' => $table3->id, 'order_type' => 'Dine-in',
            'status' => 'Completed', 'total_amount' => 75000.00, 'payment_status' => 'Paid', 'order_time' => now()->subDays(2),
        ]);
        $this->command->info('Orders seeded.');

        // --- 18. Order Items ---
        $this->command->info('Seeding Order Items...');
        $orderItem1 = OrderItem::firstOrCreate(['order_id' => $order1->id, 'menu_item_id' => $menuItem1->id], [
            'id' => (string) Str::uuid(), 'quantity' => 1, 'price_per_unit' => 25000.00, 'total_price' => 25000.00, 'kds_status' => 'Served',
        ]);
        OrderItem::firstOrCreate(['order_id' => $order1->id, 'menu_item_id' => $menuItem2->id], [
            'id' => (string) Str::uuid(), 'quantity' => 1, 'price_per_unit' => 18000.00, 'total_price' => 18000.00, 'kds_status' => 'Served',
        ]);
        OrderItem::firstOrCreate(['order_id' => $order2->id, 'menu_item_id' => $menuItem3->id], [
            'id' => (string) Str::uuid(), 'quantity' => 1, 'price_per_unit' => 75000.00, 'total_price' => 75000.00, 'kds_status' => 'Served',
        ]);
        $this->command->info('Order Items seeded.');

        // --- 19. Order Item Options ---
        $this->command->info('Seeding Order Item Options...');
        OrderItemOption::firstOrCreate(['order_item_id' => $orderItem1->id, 'menu_item_option_value_id' => $valueSusuOat->id], [
            'id' => (string) Str::uuid(), 'price_adjustment_applied' => 5000.00,
        ]);
        $this->command->info('Order Item Options seeded.');


        // --- 20. Payments ---
        $this->command->info('Seeding Payments...');
        Payment::firstOrCreate(['order_id' => $order1->id, 'payment_method' => 'QRIS'], [
            'id' => (string) Str::uuid(), 'amount' => 43000.00, 'status' => 'Success', 'payment_time' => now()->subDays(5)->addMinutes(10),
        ]);
        Payment::firstOrCreate(['order_id' => $order2->id, 'payment_method' => 'Cash'], [
            'id' => (string) Str::uuid(), 'amount' => 75000.00, 'status' => 'Success', 'payment_time' => now()->subDays(2)->addMinutes(20),
        ]);
        $this->command->info('Payments seeded.');

        // --- 21. Ingredients ---
        $this->command->info('Seeding Ingredients...');
        $ingKopi = Ingredient::firstOrCreate(['company_id' => $company1->id, 'name' => 'Biji Kopi Arabika'], [
            'id' => (string) Str::uuid(), 'unit_of_measure' => 'gram', 'default_cost_per_unit' => 100.00,
        ]);
        $ingSusu = Ingredient::firstOrCreate(['company_id' => $company1->id, 'name' => 'Susu Cair'], [
            'id' => (string) Str::uuid(), 'unit_of_measure' => 'ml', 'default_cost_per_unit' => 15.00,
        ]);
        $ingUdang = Ingredient::firstOrCreate(['company_id' => $company2->id, 'name' => 'Udang Segar'], [
            'id' => (string) Str::uuid(), 'unit_of_measure' => 'gram', 'default_cost_per_unit' => 500.00,
        ]);
        $this->command->info('Ingredients seeded.');

        // --- 22. Inventory Items ---
        $this->command->info('Seeding Inventory Items...');
        InventoryItem::firstOrCreate(['company_id' => $company1->id, 'outlet_id' => $outlet1->id, 'ingredient_id' => $ingKopi->id], [
            'id' => (string) Str::uuid(), 'current_stock' => 5000.00, 'minimum_stock_level' => 1000.00, 'last_restocked_at' => now()->subDays(7),
        ]);
        InventoryItem::firstOrCreate(['company_id' => $company1->id, 'outlet_id' => $outlet1->id, 'ingredient_id' => $ingSusu->id], [
            'id' => (string) Str::uuid(), 'current_stock' => 10000.00, 'minimum_stock_level' => 2000.00, 'last_restocked_at' => now()->subDays(3),
        ]);
        InventoryItem::firstOrCreate(['company_id' => $company2->id, 'outlet_id' => $outlet2->id, 'ingredient_id' => $ingUdang->id], [
            'id' => (string) Str::uuid(), 'current_stock' => 20000.00, 'minimum_stock_level' => 5000.00, 'last_restocked_at' => now()->subDays(1),
        ]);
        $this->command->info('Inventory Items seeded.');

        // --- 23. Recipes ---
        $this->command->info('Seeding Recipes...');
        $recipe1 = Recipe::firstOrCreate(['company_id' => $company1->id, 'menu_item_id' => $menuItem1->id], [
            'id' => (string) Str::uuid(), 'preparation_instructions' => 'Giling kopi, seduh, tambahkan susu.', 'estimated_prep_time_minutes' => 5,
        ]);
        $recipe2 = Recipe::firstOrCreate(['company_id' => $company2->id, 'menu_item_id' => $menuItem3->id], [
            'id' => (string) Str::uuid(), 'preparation_instructions' => 'Bersihkan udang, lumuri bumbu, bakar hingga matang.', 'estimated_prep_time_minutes' => 12,
        ]);
        $this->command->info('Recipes seeded.');

        // --- 24. Recipe Ingredients ---
        $this->command->info('Seeding Recipe Ingredients...');
        $recipe1->ingredients()->syncWithoutDetaching([
            $ingKopi->id => ['quantity_required' => 15], // 15 gram kopi
            $ingSusu->id => ['quantity_required' => 150], // 150 ml susu
        ]);
        $recipe2->ingredients()->syncWithoutDetaching([
            $ingUdang->id => ['quantity_required' => 200], // 200 gram udang
        ]);
        $this->command->info('Recipe Ingredients seeded.');

        // --- 25. Inventory Movements (Contoh) ---
        $this->command->info('Seeding Inventory Movements...');
        InventoryItem::create([
            'id' => (string) Str::uuid(),
            'company_id' => $company1->id,
            'outlet_id' => $outlet1->id,
            'ingredient_id' => $ingKopi->id,
            'current_stock' => 10000.00, // Misal ini pembelian baru
            'minimum_stock_level' => 1000.00,
            'last_restocked_at' => now(),
        ]);
        $this->command->info('Inventory Movements seeded.');

        // --- 26. Employee Attendances ---
        $this->command->info('Seeding Employee Attendances...');
        EmployeeAttendance::firstOrCreate(['company_id' => $company1->id, 'user_id' => $cashier1->id, 'check_in_time' => now()->subHours(8)], [
            'id' => (string) Str::uuid(), 'outlet_id' => $outlet1->id, 'check_out_time' => now(), 'status' => 'On_Time',
        ]);
        EmployeeAttendance::firstOrCreate(['company_id' => $company2->id, 'user_id' => $chef1->id, 'check_in_time' => now()->subHours(7)], [
            'id' => (string) Str::uuid(), 'outlet_id' => $outlet2->id, 'check_out_time' => now(), 'status' => 'On_Time',
        ]);
        $this->command->info('Employee Attendances seeded.');

        // --- 27. Employee Schedules ---
        $this->command->info('Seeding Employee Schedules...');
        EmployeeSchedule::firstOrCreate(['company_id' => $company1->id, 'user_id' => $cashier1->id, 'schedule_date' => now()->toDateString()], [
            'id' => (string) Str::uuid(), 'outlet_id' => $outlet1->id, 'start_time' => '08:00:00', 'end_time' => '17:00:00', 'shift_type' => 'Pagi',
        ]);
        $this->command->info('Employee Schedules seeded.');

        // --- 28. Payroll Runs ---
        $this->command->info('Seeding Payroll Runs...');
        $payrollRun1 = PayrollRun::firstOrCreate(['company_id' => $company1->id, 'payroll_start_date' => now()->subMonth()->startOfMonth()], [
            'id' => (string) Str::uuid(), 'payroll_end_date' => now()->subMonth()->endOfMonth(), 'status' => 'Paid',
            'processed_by_user_id' => $owner1->id, 'total_payout_amount' => 5000000.00,
        ]);
        $this->command->info('Payroll Runs seeded.');

        // --- 29. Pay Slips ---
        $this->command->info('Seeding Pay Slips...');
        PaySlip::firstOrCreate(['payroll_run_id' => $payrollRun1->id, 'user_id' => $cashier1->id], [
            'id' => (string) Str::uuid(), 'base_salary' => 3000000.00, 'net_pay' => 2800000.00, 'slip_generated_at' => now(),
            'hours_worked' => 160, 'allowances_json' => json_encode(['makan' => 300000]), 'deductions_json' => json_encode(['bpjs' => 200000]),
        ]);
        $this->command->info('Pay Slips seeded.');

        // --- 30. Reservations ---
        $this->command->info('Seeding Reservations...');
        Reservation::firstOrCreate(['company_id' => $company1->id, 'reservation_time' => now()->addDays(2)->setHour(19)], [
            'id' => (string) Str::uuid(), 'outlet_id' => $outlet1->id, 'customer_id' => $customer1->id,
            'table_id' => $table2->id, 'end_time' => now()->addDays(2)->setHour(21), 'number_of_guests' => 4,
            'status' => 'Confirmed', 'notes' => 'Ulang tahun teman.', 'confirmation_token' => Str::random(32),
        ]);
        $this->command->info('Reservations seeded.');

        // --- 31. Expenses ---
        $this->command->info('Seeding Expenses...');
        Expense::firstOrCreate(['company_id' => $company1->id, 'description' => 'Pembayaran listrik bulan ini'], [
            'id' => (string) Str::uuid(), 'outlet_id' => $outlet1->id, 'amount' => 500000.00,
            'expense_date' => now()->subDays(3), 'category' => 'Utilities', 'payment_method' => 'Bank Transfer',
        ]);
        $this->command->info('Expenses seeded.');

        // --- 32. Affiliates (Restomax Internal) ---
        $this->command->info('Seeding Affiliates...');
        $affiliate1 = Affiliate::firstOrCreate(['email' => 'affiliate.marketing@example.com'], [
            'id' => (string) Str::uuid(),
            'user_id' => null, // Ini bukan user company, tapi affiliate murni
            'full_name' => 'Affiliate Marketer A',
            'phone' => '089876543210',
            'bank_account_number' => '1234567890',
            'bank_name' => 'Bank ABC',
            'referral_code' => 'AFFILIATERESTOMAX1',
            'unique_link_token' => Str::random(20),
            'commission_rate' => 0.15, // 15%
            'is_active' => true,
        ]);
        $this->command->info('Affiliates seeded.');

        // --- 33. Affiliate Payout Runs (Restomax Internal) ---
        $this->command->info('Seeding Affiliate Payout Runs...');
        $payoutRunAffiliate1 = AffiliatePayoutRun::firstOrCreate(['payout_date' => now()->endOfMonth()], [
            'id' => (string) Str::uuid(),
            'total_amount' => 0.00, // Akan dihitung nanti
            'status' => 'Pending',
            'processed_by_user_id' => $restomaxAdmin->id,
            'notes' => 'Payout untuk bulan ' . now()->format('F Y'),
        ]);
        $this->command->info('Affiliate Payout Runs seeded.');

        // --- 34. Affiliate Referrals (Restomax Internal) ---
        $this->command->info('Seeding Affiliate Referrals...');
        // Misal company1 direferensikan oleh affiliate1
        AffiliateReferral::firstOrCreate(['affiliate_id' => $affiliate1->id, 'referred_company_id' => $company1->id], [
            'id' => (string) Str::uuid(),
            'referral_date' => now()->subMonths(3),
            'commission_percentage_applied' => $affiliate1->commission_rate,
            'calculated_commission_amount' => $company1->currentSubscription->price_locked_monthly * $affiliate1->commission_rate,
            'payout_status' => 'Pending',
            // 'payout_run_id' => $payoutRunAffiliate1->id, // Bisa langsung set atau di proses terpisah
        ]);
        $this->command->info('Affiliate Referrals seeded.');

        // --- 35. Affiliate Recurring Commissions (Restomax Internal) ---
        $this->command->info('Seeding Affiliate Recurring Commissions...');
        // Contoh komisi berulang untuk company1 dari affiliate1
        AffiliateRecurringCommission::firstOrCreate([
            'affiliate_id' => $affiliate1->id,
            'referred_company_id' => $company1->id,
            'subscription_id' => $company1->currentSubscription->id,
            'billing_period_start' => now()->subMonths(1)->startOfMonth(),
        ], [
            'id' => (string) Str::uuid(),
            'commission_amount' => $company1->currentSubscription->price_locked_monthly * $affiliate1->commission_rate,
            'billing_period_end' => now()->subMonths(1)->endOfMonth(),
            'payout_status' => 'Pending',
            // 'payout_run_id' => $payoutRunAffiliate1->id,
        ]);
        $this->command->info('Affiliate Recurring Commissions seeded.');

        $this->command->info('All Restomax data seeded successfully!');
    }
}