<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Catégories principales
        $parfums = Category::firstOrCreate(['slug' => 'parfums'], ['name' => 'Parfums']);
        $accessoires = Category::firstOrCreate(['slug' => 'accessoires'], ['name' => 'Accessoires']);

        // Sous-catégories
        $parfumsHomme = Category::firstOrCreate(['slug' => 'parfums-homme'], ['name' => 'Parfums Homme', 'parent_id' => $parfums->id]);
        $parfumsFemme = Category::firstOrCreate(['slug' => 'parfums-femme'], ['name' => 'Parfums Femme', 'parent_id' => $parfums->id]);
        $bijoux = Category::firstOrCreate(['slug' => 'bijoux'], ['name' => 'Bijoux', 'parent_id' => $accessoires->id]);
        $sacs = Category::firstOrCreate(['slug' => 'sacs'], ['name' => 'Sacs', 'parent_id' => $accessoires->id]);

        // Un utilisateur vendeur test
        $vendorUser = User::firstOrCreate(
            ['email' => 'vendeur@parfums-marketplace.test'],
            [
                'name' => 'Vendeur Test',
                'password' => bcrypt('Vendeur@1234'),
                'email_verified_at' => now(),
            ]
        );
        $vendorUser->assignRole('vendeur');

        $vendor = Vendor::firstOrCreate(
            ['user_id' => $vendorUser->id],
            [
                'boutique_name' => 'Essence de Cotonou',
                'slug' => Str::slug('Essence de Cotonou'),
                'description' => 'Boutique spécialisée en parfums et accessoires de qualité.',
                'status' => 'approved',
            ]
        );

        // Produits de démonstration
        $products = [
            ['name' => 'Eau de Parfum Boisée', 'category' => $parfumsHomme, 'price' => 25000],
            ['name' => 'Parfum Oriental Intense', 'category' => $parfumsHomme, 'price' => 32000],
            ['name' => 'Fleur de Jasmin', 'category' => $parfumsFemme, 'price' => 28000],
            ['name' => 'Rose Élégance', 'category' => $parfumsFemme, 'price' => 30000],
            ['name' => 'Bracelet Doré Fin', 'category' => $bijoux, 'price' => 12000],
            ['name' => 'Collier Perles Naturelles', 'category' => $bijoux, 'price' => 18000],
            ['name' => 'Sac à Main Cuir', 'category' => $sacs, 'price' => 45000],
            ['name' => 'Sac Bandoulière Élégant', 'category' => $sacs, 'price' => 38000],
        ];

        foreach ($products as $item) {
            Product::firstOrCreate(
                ['slug' => Str::slug($item['name'])],
                [
                    'vendor_id' => $vendor->id,
                    'category_id' => $item['category']->id,
                    'name' => $item['name'],
                    'description' => 'Un produit de qualité, soigneusement sélectionné pour sublimer votre style.',
                    'price' => $item['price'],
                    'stock' => rand(5, 50),
                    'status' => 'active',
                ]
            );
        }
    }
}