<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Community;
use App\Models\Page;
use App\Models\Post;
use App\Models\Comment;
use Database\Factories\UserFactory;
use App\Http\Controllers\Api\FriendController;

class MainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function ge()
    {


        $language = [
            'Dart',
            'Php',
            'Java',
            'Cpp',
            'Python',
            'JavaScript',
            'TypeScript',
            'SQL',
            'Kotlin',
            'Swift',
            'Rust',
            'MatLab',
            'Scala',
        ];
        $randomIndex = array_rand($language);
        return  $randomLanguage = $language[$randomIndex];
    }
    public function ge2()
    {


        $section = [
            'Frontend',
            'Backend',
            'MobileDevelopment',
            'FullStack',
            'DBAnalysis',
            'DevOps',
            'GamesDevelopment',
            'SoftwareAnalysis',
            'QualityAssurance',
            'CloudArchitecture',
            'MachineLearning',
            'DeepLearning',
            'Robotics',
            'NaturalLanguageProcessing',
            'ExpertSystems',
            'FuzzyLogic',
            'ComputerVision',
            'DataScientist',
            'ArtificialGeneralIntelligence',
            'NaturalNetwork',
            'NetworkSecurity',
            'CloudSecurity',
            'EndPointSecurity',
            'MobileSecurity',
            'IoTSecurity',
            'ApplicationSecurity',
            'ZeroTrust',
            'NetworkAdministrators',
            'NetworkEngineer',
            'NetworkAnalyst',
            'SystemsAdministrators',
            'NetworkTechnician',
        ];


        $randomIndex2 = array_rand($section);
        return   $randomSection = $section[$randomIndex2];
    }
    public function ge3()
    {



        $framework = [
            'Flutter',
            'AngularJs',
            'ReactJs',
            'Laravel',
            'ReactNative',
            'Net',
            'jQuery',
            'Asp',
            'Django',
            'Xamarin',
            'BootStrap',
        ];


        $randomIndex3 = array_rand($framework);
        return  $randomFramework = $framework[$randomIndex3];
    }
    public function ge4()
    {
        $specialty = ['AI', 'Software', 'Cyber Security', 'Network'];
        $randomIndex4 = array_rand($specialty);
        return  $randomSpecialty = $specialty[$randomIndex4];
    }

    public function run(): void
    {
        $this->call(CommunitySeeder::class);

        //User::factory()->count(10)->create();

        $all = User::pluck('id')->toArray();

        $user = User::all();

        $specialty = ['AI', 'Software', 'Cyber Security', 'Network'];
        $randomIndex4 = array_rand($specialty);
        // $randomSpecialty = $specialty[$randomIndex4];
        $type = [
            'Road map',
            'Job Opportunities',
            'Story',
            'Regular',
            'Question',
            'Advise',
            'CV',
            'Accepted Challenge',
            'Challenge'
        ];
        $randomIndex5 = array_rand($type);
        $randomType = $type[$randomIndex5];

        $randomLanguage1 = $this->ge();

        $randomFramework1 =  $this->ge3();
        $randomSection1 =  $this->ge2();
        $randomspecialty1 =  $this->ge4();

        $communities_names = array_merge(

            explode(',', $randomspecialty1),
            explode(',', $randomSection1),
            explode(',', $randomFramework1),
            explode(',', $randomLanguage1)
        );
        $communities_names = array_map(fn ($element) => $element . ' Space', $communities_names);
        $communities = Community::whereIn('name', $communities_names)->get();
        $community_id = $communities->pluck('id')->toArray();



        for ($i1 = 0; $i1 < count($all); $i1++) {

            $user[$i1]->communities()->attach($community_id);
            for ($i = 0; $i < 4; $i++) {
                $id = $user[$i1]->id;

            $post=    $user[$i1]->locationPosts()->create([
                    'title' => fake()->title(),
                    'content' => fake()->text(),
                    'type' => $randomType,
                    'likes_counts' => 0,
                    'dislikes_counts' => 0,
                    'reports_number' => 0,
                    'user_id' => $id
                ]);
            }

            $user[$i1]->specialty()->create([

                'specialty' => $randomspecialty1,
                'language' => $randomLanguage1,
                'framework' => $randomFramework1,
                'section' => $randomSection1,
                'user_id' => $id
            ]);
            $pag =  $user[$i1]->pages()->create([

                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'type' => 'Famous',
                'bio' => fake()->text(),
                'follower_counts' => 0,
                'admin_id' => $id
            ]);
            $page = Page::find($pag->id);
            $page->posts()->create([

                'title' => fake()->title(),
                'content' => fake()->text(),
                'type' => $randomType,
                'likes_counts' => 0,
                'dislikes_counts' => 0,
                'reports_number' => 0,
                'user_id' => $id
            ]);
            $pages =  Page::all();
            $posts =  Post::all();
            $comments=Comment::all();
            foreach($pages as $pa)
            {
                $user[$i1]->memberPages()->attach($pa->id);

                $page = Page::find($pa->id);
                $page->update(['follower_counts' => $page->follower_counts + 1]);
            }
            foreach($posts as $po)
            {
                $likes_on_this = Post::where('id', $po->id)->value('likes_counts');

                $po->reactions()->create([
                    'user_id' => $user[$i1]->id,
                    'type' => 'like'
                ]);
                $po->update(['likes_counts' =>$likes_on_this + 1]);

              $comment=  $user[$i1]->comments()->create([
                    'content' => fake()->text(),
                    'post_id' => $po->id,
                ]);


                    $comment->reactions()->create([
                        'user_id' => $user[$i1]->id,
                        'type' => 'dislikes'
                    ]);
                    $dislikes_on_this = Comment::where('id', $comment->id)->value('dislikes_counts');

                    $comment->update(['dislikes_counts' => $dislikes_on_this + 1]);



            }

            foreach ($community_id as $com_id) {
                for ($i = 0; $i < 4; $i++) {

                $community = Community::find($com_id);

                $community->posts()->create([
                    'title' => fake()->title(),
                    'content' => fake()->text(),
                    'type' => $randomType,
                    'user_id' => $id
                ]);

                $community->update([
                    'subscriber_counts' => $community->subscriber_counts + 1
                ]);
            }}
        }
        $this->call(FriendSeeder::class);

    }
}
