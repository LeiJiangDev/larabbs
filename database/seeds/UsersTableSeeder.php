<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //获取Faker实例
        $faker = app(Faker\Generator::class);

        //头像假数据

        $avatars = [
            'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/s5ehp11z6s.png?imageView2/1/w/200/h/200',
            'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/Lhd1SHqu86.png?imageView2/1/w/200/h/200',
            'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/LOnMrqbHJn.png?imageView2/1/w/200/h/200',
            'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/xAuDMxteQy.png?imageView2/1/w/200/h/200',
            'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/ZqM7iaP4CR.png?imageView2/1/w/200/h/200',
            'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/NDnzMutoxX.png?imageView2/1/w/200/h/200',
        ];

        //生成数据集合
        $users = factory(User::class)
                        ->times(10)
                        ->make()
                        ->each(function ($user,$index)
                            use ($faker,$avatars)
                        {
                            $user->avatar = $faker ->randomElement($avatars);
                        });


        //让隐藏字段可见，并将数据集合转换成数组
        $user_array = $users ->makeVisible(['password','remember_token'])->toArray();

        //插入到数据库
        User::insert($user_array);

        //单独处理第一个数据
        $user = User::find(1);
        $user->name = 'LeiJiang';
        $user->email='lei@jiang.com';
        $user->avatar = 'https://lccdn.phphub.org/uploads/avatars/21088_1519183385.jpg?imageView2/1/w/200/h/200';
        $user->save();

    }
}
