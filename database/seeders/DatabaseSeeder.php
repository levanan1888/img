<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\SystemSetting;
use App\Models\User;
use App\Models\ConversionLog;
use App\Models\BlogCategory;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Clear database tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        Role::truncate();
        SystemSetting::truncate();

        if (Schema::hasTable('posts')) {
            Post::truncate();
        }

        if (Schema::hasTable('blog_categories')) {
            BlogCategory::truncate();
        }

        if (Schema::hasTable('conversion_logs')) {
            ConversionLog::truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Seed Roles
        $adminRole = Role::create([
            'name' => 'Admin',
            'description' => 'Toàn quyền quản trị hệ thống.',
            'permissions' => [] // Admin bypasses checks
        ]);

        $managerRole = Role::create([
            'name' => 'Manager',
            'description' => 'Xem báo cáo, quản lý nhật ký chuyển đổi và người dùng.',
            'permissions' => [
                'users' => ['read', 'update'],
                'conversions' => ['read', 'delete'],
                'settings' => ['read']
            ]
        ]);

        $staffRole = Role::create([
            'name' => 'Staff',
            'description' => 'Xem báo cáo và nhật ký chuyển đổi.',
            'permissions' => [
                'users' => ['read'],
                'conversions' => ['read']
            ]
        ]);

        // 3. Seed Admin, Manager, Staff users
        $adminUser = User::create([
            'name' => 'Quản trị viên',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'),
            'role_id' => $adminRole->id,
            'status' => 'active'
        ]);

        User::create([
            'name' => 'Quản lý viên',
            'email' => 'manager@example.com',
            'password' => bcrypt('admin123'),
            'role_id' => $managerRole->id,
            'status' => 'active'
        ]);

        User::create([
            'name' => 'Nhân viên hỗ trợ',
            'email' => 'staff@example.com',
            'password' => bcrypt('admin123'),
            'role_id' => $staffRole->id,
            'status' => 'active'
        ]);

        // 4. Seed 15 mock customers (role_id = null)
        $names = ['Nguyễn Văn A', 'Trần Thị B', 'Lê Hoàng C', 'Phạm Minh D', 'Vũ Hồng E', 'Hoàng Anh F', 'Đỗ Gia G', 'Bùi Quỳnh H', 'Phan Bảo I', 'Đặng Huy J', 'Lý Tường K', 'Tạ Duy L', 'Đoàn Hùng M', 'Mai Linh N', 'Ngô Sơn O'];
        foreach ($names as $index => $name) {
            User::create([
                'name' => $name,
                'email' => strtolower(str_replace(' ', '', $name)) . rand(10, 99) . '@example.com',
                'password' => bcrypt('password123'),
                'role_id' => null,
                'status' => $index === 4 ? 'blocked' : 'active'
            ]);
        }

        // 5. Seed Conversion Logs over the last 30 days
        $srcFormats = ['jpg', 'jpeg', 'png', 'webp', 'bmp'];
        $targetFormats = ['png', 'webp', 'jpg'];
        $statuses = ['success', 'success', 'success', 'success', 'success', 'failed'];
        $errorMessages = [
            'Dữ liệu ảnh không hợp lệ.',
            'Kích thước ảnh vượt quá 5MB.',
            'Không hỗ trợ định dạng ảnh nguồn.',
            'Quá trình chuyển đổi bị timeout.'
        ];

        for ($dayOffset = 30; $dayOffset >= 0; $dayOffset--) {
            // Generate 3 to 10 logs per day
            $logsCount = rand(3, 10);
            for ($j = 0; $j < $logsCount; $j++) {
                $date = Carbon::now()->subDays($dayOffset)->subHours(rand(1, 23))->subMinutes(rand(1, 59));
                $src = collect($srcFormats)->random();
                $tgt = collect($targetFormats)->random();
                
                // Prevent source == target in mock data
                if ($src === $tgt) {
                    $tgt = $src === 'png' ? 'jpg' : 'png';
                }

                $status = collect($statuses)->random();
                $err = $status === 'failed' ? collect($errorMessages)->random() : null;

                ConversionLog::create([
                    'file_name' => 'anh_mau_' . rand(100, 999) . '.' . $src,
                    'file_size' => rand(10240, 5242880), // 10KB to 5MB
                    'source_format' => $src,
                    'target_format' => $tgt,
                    'status' => $status,
                    'execution_time' => rand(50, 1800) / 1000, // 0.05 to 1.8 seconds
                    'error_message' => $err,
                    'ip_address' => '192.168.1.' . rand(2, 254),
                    'created_at' => $date,
                    'updated_at' => $date
                ]);
            }
        }

        // 6. Seed System Settings
        SystemSetting::setValue('site_name', 'Trình chuyển đổi ảnh');
        SystemSetting::setValue('site_email', 'contact@chuyenanh.com');
        SystemSetting::setValue('site_logo', 'logo.png');
        SystemSetting::setValue('theme_mode', 'dark');
        SystemSetting::setValue('default_language', 'vi');
        SystemSetting::setValue('currency_code', 'VND');

        // 7. Seed Blog Categories and Posts
        $guideCategory = BlogCategory::create([
            'name' => 'Hướng dẫn chuyển đổi ảnh',
            'slug' => 'huong-dan-chuyen-doi-anh',
            'description' => 'Các bài viết hướng dẫn chuyển đổi định dạng ảnh nhanh, đúng chuẩn và giữ chất lượng tốt.',
        ]);

        $optimizationCategory = BlogCategory::create([
            'name' => 'Tối ưu hình ảnh',
            'slug' => 'toi-uu-hinh-anh',
            'description' => 'Kiến thức tối ưu dung lượng, chất lượng và trải nghiệm tải ảnh trên website.',
        ]);

        $seoCategory = BlogCategory::create([
            'name' => 'SEO hình ảnh',
            'slug' => 'seo-hinh-anh',
            'description' => 'Mẹo đặt tên file, alt text và tối ưu ảnh để hỗ trợ SEO hiệu quả hơn.',
        ]);

        $publishedAt = Carbon::now()->subDays(6);

        $posts = [
            [
                'category_id' => $guideCategory->id,
                'title' => 'Cách chuyển JPG sang PNG online mà vẫn giữ chất lượng',
                'slug' => 'cach-chuyen-jpg-sang-png-online-ma-van-giu-chat-luong',
                'summary' => 'Hướng dẫn chuyển ảnh JPG sang PNG trực tuyến, phù hợp khi bạn cần giữ chi tiết hình ảnh và nền trong suốt.',
                'content' => "JPG phù hợp với ảnh chụp vì dung lượng nhẹ, còn PNG phù hợp hơn khi bạn cần giữ độ sắc nét, biểu tượng, ảnh sản phẩm hoặc hình có nền trong suốt.\n\nĐể chuyển JPG sang PNG hiệu quả, hãy chọn ảnh gốc rõ nét, tránh nén lại quá nhiều lần và kiểm tra kích thước file sau khi tải xuống.\n\nCông cụ chuyển đổi ảnh online giúp bạn xử lý nhanh mà không cần cài phần mềm. Sau khi chuyển đổi, nên mở lại file PNG để kiểm tra màu sắc, độ nét và vùng nền.",
                'seo_title' => 'Cách chuyển JPG sang PNG online giữ chất lượng',
                'seo_description' => 'Hướng dẫn chuyển JPG sang PNG online nhanh, dễ thực hiện và giữ chất lượng ảnh tốt cho thiết kế, website hoặc lưu trữ.',
                'seo_keywords' => 'chuyển JPG sang PNG, convert JPG to PNG, đổi định dạng ảnh',
            ],
            [
                'category_id' => $guideCategory->id,
                'title' => 'Khi nào nên dùng PNG, JPG, WEBP hoặc BMP?',
                'slug' => 'khi-nao-nen-dung-png-jpg-webp-hoac-bmp',
                'summary' => 'Mỗi định dạng ảnh có ưu điểm riêng. Chọn đúng định dạng giúp ảnh đẹp hơn, nhẹ hơn và phù hợp mục đích sử dụng.',
                'content' => "PNG phù hợp với ảnh cần độ sắc nét, logo, icon hoặc ảnh có nền trong suốt. JPG phù hợp với ảnh chụp và ảnh cần dung lượng nhẹ.\n\nWEBP là lựa chọn hiện đại cho website vì thường có dung lượng thấp nhưng chất lượng vẫn tốt. BMP ít dùng trên web vì file thường rất nặng, nhưng vẫn hữu ích trong một số hệ thống cũ.\n\nNếu mục tiêu là tốc độ tải trang, hãy cân nhắc WEBP. Nếu mục tiêu là chỉnh sửa hoặc giữ chi tiết, PNG thường là lựa chọn an toàn hơn.",
                'seo_title' => 'So sánh PNG, JPG, WEBP và BMP',
                'seo_description' => 'Tìm hiểu khi nào nên dùng PNG, JPG, WEBP hoặc BMP để tối ưu chất lượng ảnh, dung lượng và trải nghiệm website.',
                'seo_keywords' => 'PNG JPG WEBP BMP, định dạng ảnh, tối ưu ảnh',
            ],
            [
                'category_id' => $optimizationCategory->id,
                'title' => '5 mẹo giảm dung lượng ảnh trước khi upload lên website',
                'slug' => '5-meo-giam-dung-luong-anh-truoc-khi-upload-len-website',
                'summary' => 'Ảnh nhẹ giúp website tải nhanh hơn. Dưới đây là các mẹo đơn giản để giảm dung lượng mà vẫn giữ chất lượng ổn.',
                'content' => "Dung lượng ảnh ảnh hưởng trực tiếp đến tốc độ tải trang. Trước khi upload, bạn nên kiểm tra kích thước hiển thị thực tế và resize ảnh về đúng nhu cầu.\n\nHãy chọn định dạng phù hợp, nén ảnh ở mức vừa phải, xóa metadata không cần thiết và dùng WEBP cho các trình duyệt hiện đại.\n\nKhông nên nén quá mạnh vì ảnh bị bệt màu hoặc mất chi tiết sẽ làm trải nghiệm người dùng kém chuyên nghiệp.",
                'seo_title' => '5 mẹo giảm dung lượng ảnh cho website',
                'seo_description' => 'Các mẹo giảm dung lượng ảnh trước khi upload lên website, giúp trang tải nhanh hơn mà vẫn giữ chất lượng hiển thị tốt.',
                'seo_keywords' => 'giảm dung lượng ảnh, nén ảnh, tối ưu ảnh website',
            ],
            [
                'category_id' => $seoCategory->id,
                'title' => 'Tối ưu tên file và alt text ảnh để hỗ trợ SEO',
                'slug' => 'toi-uu-ten-file-va-alt-text-anh-de-ho-tro-seo',
                'summary' => 'Tên file và alt text rõ nghĩa giúp công cụ tìm kiếm hiểu nội dung ảnh tốt hơn và cải thiện khả năng hiển thị.',
                'content' => "Tên file ảnh nên mô tả đúng nội dung, viết thường, không dấu và dùng dấu gạch ngang để phân tách từ. Ví dụ: chuyen-jpg-sang-png.png.\n\nAlt text nên ngắn gọn, tự nhiên và phản ánh đúng nội dung ảnh. Tránh nhồi nhét từ khóa vì điều đó không tốt cho trải nghiệm người dùng.\n\nKhi ảnh phục vụ nội dung bài viết, hãy đặt ảnh gần đoạn văn liên quan để công cụ tìm kiếm có thêm ngữ cảnh.",
                'seo_title' => 'Tối ưu tên file và alt text ảnh cho SEO',
                'seo_description' => 'Hướng dẫn tối ưu tên file và alt text ảnh để hỗ trợ SEO hình ảnh, tăng khả năng hiển thị và cải thiện trải nghiệm người dùng.',
                'seo_keywords' => 'SEO hình ảnh, alt text ảnh, tên file ảnh chuẩn SEO',
            ],
        ];

        foreach ($posts as $index => $post) {
            Post::create([
                ...$post,
                'image_path' => null,
                'status' => 'published',
                'author_id' => $adminUser->id,
                'created_at' => $publishedAt->copy()->addDays($index),
                'updated_at' => $publishedAt->copy()->addDays($index),
            ]);
        }
    }
}
