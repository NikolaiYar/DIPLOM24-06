<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Recipe;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RecipeSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();

        $recipes = [
            [
                'title' => 'Шоколадный торт',
                'description' => 'Нежный бисквитный торт с шоколадным кремом',
                'cook_time' => 120,
                'difficulty' => 2,
                'calories' => 450,
                'instructions' => "Замесить тесто.\nДать подойти.\nВыпекать при 180°C 40 минут.\nОхладите, намажьте крем и украсьте.",
                'image' => 'chococake.jpg'
            ],
            [
                'title' => 'Салат Цезарь',
                'description' => 'Классический салат с курицей, крутонами и соусом',
                'cook_time' => 30,
                'difficulty' => 1,
                'calories' => 320,
                'instructions' => "Обжарьте курицу.\nНарежьте салат.\nДобавьте крутоны и пармезан.\nПолейте соусом Цезарь и подавайте.",
                'image' => 'caesar.jpg'
            ],
            [
                'title' => 'Спагетти Карбонара',
                'description' => 'Итальянская паста с беконом, яйцами и пармезаном',
                'cook_time' => 25,
                'difficulty' => 2,
                'calories' => 550,
                'instructions' => "Отварите спагетти.\nОбжарьте бекон.\nВзбейте яйца с сыром.\nСмешайте всё горячим, не доводя до омлета.",
                'image' => 'carbonara.jpg'
            ],
            [
                'title' => 'Омлет с овощами',
                'description' => 'Пышный омлет с болгарским перцем, помидорами и зеленью',
                'cook_time' => 15,
                'difficulty' => 1,
                'calories' => 280,
                'instructions' => "Взбейте яйца.\nНарежьте овощи и слегка обжарьте.\nВылейте яйца.\nГотовьте под крышкой до пышности.",
                'image' => 'omelette.jpg'
            ],
            [
                'title' => 'Борщ',
                'description' => 'Традиционный украинский борщ со свеклой и мясом',
                'cook_time' => 90,
                'difficulty' => 3,
                'calories' => 380,
                'instructions' => "Отварите мясо.\nДобавьте капусту, картошку.\nПотушите свеклу с морковкой и луком.\nСмешайте всё, варите 10 мин.",
                'image' => 'borscht.jpg'
            ],
            [
                'title' => 'Панкейки',
                'description' => 'Американские пышные блинчики с кленовым сиропом',
                'cook_time' => 20,
                'difficulty' => 1,
                'calories' => 320,
                'instructions' => "Смешайте муку, яйца, молоко и разрыхлитель.\nЖарьте на сухой сковороде до золотистой корочки.\nПодавайте с сиропом.",
                'image' => 'pancakes.jpg'
            ],
            [
                'title' => 'Греческий салат',
                'description' => 'Свежие овощи с фетой и оливковым маслом',
                'cook_time' => 15,
                'difficulty' => 1,
                'calories' => 250,
                'instructions' => "Нарежьте помидоры, огурцы, лук, перец.\nДобавьте оливки и фету.\nПолейте оливковым маслом и перемешайте.",
                'image' => 'greek-salad.jpg'
            ],
            [
                'title' => 'Лазанья Болоньезе',
                'description' => 'Итальянская запеканка с мясным соусом и сыром',
                'cook_time' => 60,
                'difficulty' => 3,
                'calories' => 480,
                'instructions' => "Приготовьте соус болоньезе и бешамель.\nВыложите слоями соус и листы лазаньи.\nПосыпьте сыром и запекайте 40 мин.",
                'image' => 'lasagna.jpg'
            ],
            [
                'title' => 'Тыквенный суп-пюре',
                'description' => 'Нежный крем-суп из тыквы со сливками',
                'cook_time' => 40,
                'difficulty' => 2,
                'calories' => 210,
                'instructions' => "Нарежьте тыкву, картошку, лук.\nВарите до мягкости.\nПревратите в пюре, добавьте сливки, прогрейте.",
                'image' => 'pumpkin-soup.jpg'
            ],
            [
                'title' => 'Тирамису',
                'description' => 'Итальянский десерт с кофе и сыром маскарпоне',
                'cook_time' => 45,
                'difficulty' => 3,
                'calories' => 420,
                'instructions' => "Взбейте яйца с сахаром, добавьте маскарпоне.\nОбмакните савоярди в кофе.\nВыложите слоями крем и печенье.\nОхладите.",
                'image' => 'tiramisu.jpg'
            ],



            [
                'title' => 'Плов с курицей',
                'description' => 'Ароматный плов с куриным мясом и специями',
                'cook_time' => 60,
                'difficulty' => 2,
                'calories' => 500,
                'instructions' => "Обжарьте лук и морковь.\nДобавьте курицу и обжарьте.\nДобавьте рис, специи и воду.\nТушите до готовности.",
                'image' => 'chicken-pilaf.jpg'
            ],
            [
                'title' => 'Куриный стейк с овощами',
                'description' => 'Нежный куриный стейк с гарниром из овощей',
                'cook_time' => 35,
                'difficulty' => 2,
                'calories' => 400,
                'instructions' => "Замаринуйте курицу.\nОбжарьте до золотистой корочки.\nПриготовьте овощи на гриле или на сковороде.\nПодавайте вместе.",
                'image' => 'chicken-steak.png'
            ],
            [
                'title' => 'Сырники',
                'description' => 'Традиционные творожные лепёшки с изюмом',
                'cook_time' => 25,
                'difficulty' => 1,
                'calories' => 290,
                'instructions' => "Смешайте творог, яйца, сахар и муку.\nСформируйте сырники.\nОбжарьте до румяной корочки.\nПодавайте со сметаной.",
                'image' => 'syrniki.jpg'
            ],
            [
                'title' => 'Ризотто с грибами',
                'description' => 'Кремовая итальянская каша с белыми грибами',
                'cook_time' => 40,
                'difficulty' => 3,
                'calories' => 420,
                'instructions' => "Обжарьте лук и грибы.\nДобавьте рис арборио.\nПостепенно вливайте бульон.\nВ конце добавьте масло и сыр.",
                'image' => 'mushroom-risotto.jpg'
            ],
            [
                'title' => 'Хачапури по-аджарски',
                'description' => 'Грузинская лепешка с яйцом и сыром внутри',
                'cook_time' => 50,
                'difficulty' => 3,
                'calories' => 600,
                'instructions' => "Приготовьте тесто и сформируйте лодочку.\nВыложите сыр.\nЗапекайте до румяности.\nДобавьте яйцо и немного допеките.",
                'image' => 'khachapuri.jpg'
            ],
            [
                'title' => 'Плов с бараниной',
                'description' => 'Узбекский плов с сочной бараниной и морковью',
                'cook_time' => 90,
                'difficulty' => 3,
                'calories' => 600,
                'instructions' => "Обжарьте мясо.\nДобавьте лук и морковь.\nВсыпьте рис, налейте воду и тушите до готовности.",
                'image' => 'plov.jpg'
            ],
            [
                'title' => 'Блины с творогом',
                'description' => 'Тонкие блины с начинкой из сладкого творога',
                'cook_time' => 35,
                'difficulty' => 2,
                'calories' => 300,
                'instructions' => "Приготовьте тесто.\nЖарьте блины.\nЗаверните творожную начинку в каждый блин.",
                'image' => 'bliny.jpg'
            ],
            [
                'title' => 'Куриные котлеты',
                'description' => 'Сочные котлеты из куриного фарша',
                'cook_time' => 40,
                'difficulty' => 2,
                'calories' => 400,
                'instructions' => "Смешайте фарш с луком и хлебом.\nСформируйте котлеты.\nОбжарьте до румяной корочки.",
                'image' => 'chicken-cutlets.jpg'
            ],
            [
                'title' => 'Овощное рагу',
                'description' => 'Тушёные овощи с томатным соусом',
                'cook_time' => 45,
                'difficulty' => 1,
                'calories' => 220,
                'instructions' => "Нарежьте овощи.\nОбжарьте лук и морковь.\nДобавьте остальные овощи и тушите под крышкой.",
                'image' => 'vegetable-stew.jpg'
            ],
            [
                'title' => 'Французский багет',
                'description' => 'Хрустящий багет с мягкой серединкой',
                'cook_time' => 180,
                'difficulty' => 3,
                'calories' => 280,
                'instructions' => "Замесить тесто.\nДать подойти.\nСформируйте багеты и выпекать при 220°C 25 минут.",
                'image' => 'baguette.jpg'
            ],
            [
                'title' => 'Картофельное пюре',
                'description' => 'Классическое пюре с молоком и маслом',
                'cook_time' => 25,
                'difficulty' => 1,
                'calories' => 200,
                'instructions' => "Отварите картофель.\nРазомните с молоком и сливочным маслом.\nПриправьте по вкусу.",
                'image' => 'mashed-potatoes.jpg'
            ],
            [
                'title' => 'Чизкейк Нью-Йорк',
                'description' => 'Кремовый чизкейк на песочной основе',
                'cook_time' => 90,
                'difficulty' => 3,
                'calories' => 450,
                'instructions' => "Приготовьте основу.\nВзбейте начинку.\nВыпекать при 160°C час и остудите.",
                'image' => 'cheesecake.jpg'
            ],
            [
                'title' => 'Том Ям',
                'description' => 'Острый тайский суп с креветками и кокосовым молоком',
                'cook_time' => 40,
                'difficulty' => 3,
                'calories' => 290,
                'instructions' => "Сварите бульон с лемонграссом и галангалом.\nДобавьте креветки, грибы и кокосовое молоко.\nПодавать с лаймом.",
                'image' => 'tom-yam.jpg'
            ],
            [
                'title' => 'Тост с авокадо',
                'description' => 'Хрустящий тост с авокадо, яйцом и специями',
                'cook_time' => 10,
                'difficulty' => 1,
                'calories' => 270,
                'instructions' => "Обжарьте хлеб.\nНамажьте размятое авокадо.\nДобавьте яйцо пашот, приправьте.",
                'image' => 'avocado-toast.jpg'
            ],
            [
                'title' => 'Суши с лососем',
                'description' => 'Классические маки с лососем и рисом',
                'cook_time' => 50,
                'difficulty' => 3,
                'calories' => 300,
                'instructions' => "Подготовьте рис.\nНарежьте рыбу.\nЗаверните в нори с рисом и нарежьте.",
                'image' => 'salmon-sushi.jpg'
            ],
            [
                'title' => 'Тако с говядиной',
                'description' => 'Мексиканские тако с острым мясным фаршем',
                'cook_time' => 30,
                'difficulty' => 2,
                'calories' => 340,
                'instructions' => "Обжарьте фарш с приправами.\nПодготовьте лепёшки.\nНачините и подавайте с овощами.",
                'image' => 'beef-taco.jpg'
            ],
            [
                'title' => 'Хумус',
                'description' => 'Кремовая закуска из нута с тахини и лимоном',
                'cook_time' => 15,
                'difficulty' => 1,
                'calories' => 180,
                'instructions' => "Измельчите нут, тахини, чеснок и лимонный сок.\nДобавьте оливковое масло и соль.\nПодавайте с питой.",
                'image' => 'hummus.jpg'
            ],
            [
                'title' => 'Фалафель',
                'description' => 'Жареные шарики из нута с пряностями',
                'cook_time' => 45,
                'difficulty' => 2,
                'calories' => 290,
                'instructions' => "Измельчите нут с чесноком и зеленью.\nСформируйте шарики.\nОбжарьте до золотистости.",
                'image' => 'falafel.jpg'
            ],
            [
                'title' => 'Рамен с курицей',
                'description' => 'Японский суп с лапшой, яйцом и куриным бульоном',
                'cook_time' => 50,
                'difficulty' => 3,
                'calories' => 430,
                'instructions' => "Варите бульон.\nОтварите лапшу и курицу.\nДобавьте яйцо, зелень и подавайте.",
                'image' => 'chicken-ramen.jpg'
            ],
            [
                'title' => 'Брускетта с томатами',
                'description' => 'Закуска из хрустящего хлеба с томатами и базиликом',
                'cook_time' => 15,
                'difficulty' => 1,
                'calories' => 210,
                'instructions' => "Поджарьте багет.\nНарежьте томаты и базилик.\nВыложите на хлеб и сбрызните маслом.",
                'image' => 'bruschetta.jpg'
            ],
            [
                'title' => 'Шакшука',
                'description' => 'Яйца, запечённые в пряном томатном соусе',
                'cook_time' => 25,
                'difficulty' => 2,
                'calories' => 270,
                'instructions' => "Обжарьте лук и перец.\nДобавьте томаты и специи.\nРазбейте яйца и готовьте до схватывания.",
                'image' => 'shakshuka.jpg'
            ],

        ];


        foreach ($recipes as $recipeData) {
            $recipe = Recipe::create([
                'title' => $recipeData['title'],
                'description' => $recipeData['description'],
                'cook_time' => $recipeData['cook_time'],
                'difficulty' => $recipeData['difficulty'],
                'calories' => $recipeData['calories'],
                'instructions' => $recipeData['instructions'],
                'image' => $this->storeImage($recipeData['image']),
                'user_id' => $user->id,
            ]);

            // Привязка категорий на основе названия рецепта
            $categoryIds = $this->determineCategoryIds($recipeData['title']);
            $recipe->categories()->attach($categoryIds);
        }
    }

    private function storeImage($filename)
    {
        $fromPath = public_path("assets/images/{$filename}");
        $toPath = "recipes/{$filename}";

        Storage::disk('public')->put(
            $toPath,
            file_get_contents($fromPath)
        );

        return Storage::url($toPath);
    }

    private function determineCategoryIds($title)
    {
        $title = mb_strtolower($title);

        $map = [
            'салат' => 'Салаты',
            'суп' => 'Супы',
            'торт' => 'Десерты',
            'панкейк' => 'Десерты',
            'тирамису' => 'Десерты',
            'омлет' => 'Завтраки',
            'паста' => 'Основные блюда',
            'спагетти' => 'Основные блюда',
            'лазанья' => 'Основные блюда',
            'борщ' => 'Супы',
            'тост с авокадо' => 'Завтраки',
            'французский багет' => 'Завтраки',
        ];

        $matched = [];

        foreach ($map as $keyword => $categoryName) {
            if (str_contains($title, $keyword)) {
                $category = Category::where('name', $categoryName)->first();
                if ($category) {
                    $matched[] = $category->id;
                }
            }
        }

        // Если ничего не подошло — привязать к категории "Разное" (если есть)
        if (empty($matched)) {
            $default = Category::where('name', 'Разное')->first();
            if ($default) {
                $matched[] = $default->id;
            }
        }

        return $matched;
    }
}


