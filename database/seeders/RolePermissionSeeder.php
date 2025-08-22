<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
 public function run()
    {
        // 1. Tạo roles cho website tin tức
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Quản trị viên',
                'description' => 'Quản trị toàn bộ hệ thống tin tức',
                'is_system_role' => true,
            ],
            [
                'name' => 'editor',
                'display_name' => 'Biên tập viên',
                'description' => 'Biên tập và xuất bản bài viết',
                'is_system_role' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        // 2. Tạo permissions cho website tin tức
        $permissions = [
            // Article Management
            ['name' => 'articles.view', 'display_name' => 'Xem bài viết', 'module' => 'articles', 'action' => 'view'],
            ['name' => 'articles.create', 'display_name' => 'Tạo bài viết', 'module' => 'articles', 'action' => 'create'],
            ['name' => 'articles.edit', 'display_name' => 'Chỉnh sửa bài viết', 'module' => 'articles', 'action' => 'edit'],
            ['name' => 'articles.delete', 'display_name' => 'Xóa bài viết', 'module' => 'articles', 'action' => 'delete'],
            ['name' => 'articles.publish', 'display_name' => 'Xuất bản bài viết', 'module' => 'articles', 'action' => 'publish'],
            ['name' => 'articles.manage_all', 'display_name' => 'Quản lý tất cả bài viết', 'module' => 'articles', 'action' => 'manage_all'],

            // Category Management
            ['name' => 'categories.view', 'display_name' => 'Xem danh mục', 'module' => 'categories', 'action' => 'view'],
            ['name' => 'categories.create', 'display_name' => 'Tạo danh mục', 'module' => 'categories', 'action' => 'create'],
            ['name' => 'categories.edit', 'display_name' => 'Chỉnh sửa danh mục', 'module' => 'categories', 'action' => 'edit'],
            ['name' => 'categories.delete', 'display_name' => 'Xóa danh mục', 'module' => 'categories', 'action' => 'delete'],

            // Media Management
            ['name' => 'media.view', 'display_name' => 'Xem thư viện media', 'module' => 'media', 'action' => 'view'],
            ['name' => 'media.upload', 'display_name' => 'Upload media', 'module' => 'media', 'action' => 'upload'],
            ['name' => 'media.delete', 'display_name' => 'Xóa media', 'module' => 'media', 'action' => 'delete'],

            // User Management (chỉ admin)
            ['name' => 'users.view', 'display_name' => 'Xem danh sách người dùng', 'module' => 'users', 'action' => 'view'],
            ['name' => 'users.create', 'display_name' => 'Tạo người dùng', 'module' => 'users', 'action' => 'create'],
            ['name' => 'users.edit', 'display_name' => 'Chỉnh sửa người dùng', 'module' => 'users', 'action' => 'edit'],
            ['name' => 'users.delete', 'display_name' => 'Xóa người dùng', 'module' => 'users', 'action' => 'delete'],
            ['name' => 'users.manage', 'display_name' => 'Quản lý người dùng', 'module' => 'users', 'action' => 'manage'],

            // Settings & Logs (chỉ admin)
            ['name' => 'system.view_logs', 'display_name' => 'Xem nhật ký hệ thống', 'module' => 'system', 'action' => 'view_logs'],
            ['name' => 'system.settings', 'display_name' => 'Cài đặt hệ thống', 'module' => 'system', 'action' => 'settings'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // 3. Gán quyền cho roles
        $admin = Role::where('name', 'admin')->first();
        $editor = Role::where('name', 'editor')->first();

        // Admin có tất cả quyền
        $allPermissions = Permission::all();
        $admin->permissions()->attach($allPermissions);

        // Editor chỉ có quyền về bài viết, danh mục và media
        $editorPermissions = Permission::whereIn('module', ['articles','media'])->get();
        $editor->permissions()->attach($editorPermissions);

        // 4. Tạo user admin mặc định
        $adminUser = User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        // Gán role admin
        $adminUser->assignRole('admin');

        // 5. Tạo user editor mẫu
        $editorUser = User::create([
            'username' => 'editor',
            'email' => 'editor@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        // Gán role editor
        $editorUser->assignRole('editor');

        // Log việc tạo users
        ActivityLog::create([
            'user_id' => null,
            'action' => 'system.setup',
            'description' => 'Khởi tạo hệ thống admin và editor',
            'ip_address' => '127.0.0.1',
            'metadata' => [
                'admin_user_id' => $adminUser->id,
                'editor_user_id' => $editorUser->id,
            ],
        ]);
    }
}
