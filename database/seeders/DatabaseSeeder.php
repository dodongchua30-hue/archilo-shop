<?php
namespace Database\Seeders;
use App\Models\{Product, User};
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Demo logins (password: "password") - change before going live
        User::forceCreate(['name' => 'Owner', 'email' => 'admin@archilo.test', 'password' => 'password', 'role' => 'admin']);
        User::forceCreate(['name' => 'Staff', 'email' => 'staff@archilo.test', 'password' => 'password', 'role' => 'staff']);
        User::forceCreate(['name' => 'Customer', 'email' => 'customer@archilo.test', 'password' => 'password', 'role' => 'customer']);

        $items = [['Flared Jeans','Jeans',35],['Boyfriend Jeans','Jeans',60],['Denim Skirt','Skirts',35],['Mini Skirt','Skirts',60],
                  ['Sunny Dress','Dresses',64],['Floral Midi Dress','Dresses',58],['Fitted Tee','Tops',18],['Ruffle Blouse','Tops',28]];
        foreach ($items as [$name, $cat, $price]) {
            $p = Product::create(['name' => $name, 'category' => $cat, 'price' => $price, 'description' => 'A boutique favourite. Visit us in store to try it on.']);
            foreach (['S','M','L','XL'] as $size) $p->variants()->create(['size' => $size, 'stock' => rand(0, 12)]);
        }
    }
}
