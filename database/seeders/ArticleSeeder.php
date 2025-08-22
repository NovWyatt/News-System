<?php
namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $users = User::all();

        if ($users->count() < 2) {
            $this->command->error('Cần ít nhất 2 users. Chạy NewsAdminSeeder trước.');
            return;
        }

        // Tạo 30 bài viết mẫu
        for ($i = 1; $i <= 30; $i++) {
            $title   = "Bài viết tin tức số {$i}";
            $content = $this->generateSampleContent($i);

            Article::create([
                'title'          => $title,
                'slug'           => Str::slug($title),
                'content'        => $content,
                'featured_image' => $i <= 10 ? "https://picsum.photos/800/600?random={$i}" : null,
                'status'         => $this->getRandomStatus($i),
                'is_featured'    => $i <= 5, // 5 bài đầu là featured
                'author_id'      => $users->random()->id,
                'published_at'   => $this->getPublishedAt($i),
            ]);
        }

        $this->command->info('Đã tạo 30 bài viết mẫu.');
    }

    private function generateSampleContent($index)
    {
        $topics = [
            'công nghệ', 'kinh tế', 'thể thao', 'giải trí', 'xã hội',
            'khoa học', 'du lịch', 'ẩm thực', 'thời trang', 'sức khỏe',
        ];

        $topic = $topics[($index - 1) % count($topics)];

        return "
            <p>Đây là nội dung chi tiết của bài viết số {$index} về chủ đề {$topic}. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>

            <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>

            <h3>Phân tích chi tiết</h3>
            <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.</p>

            <p>Totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit.</p>

            <h3>Kết luận</h3>
            <p>Sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt, neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit.</p>
        ";
    }

    private function getRandomStatus($index)
    {
        if ($index <= 20) {
            return 'published';
        } elseif ($index <= 25) {
            return 'draft';
        } else {
            return 'archived';
        }
    }

    private function getPublishedAt($index)
    {
        if ($index <= 20) {
            // Published articles have publish dates
            return now()->subDays(rand(1, 60));
        }

        // Draft and archived articles don't have publish dates
        return null;
    }
}
