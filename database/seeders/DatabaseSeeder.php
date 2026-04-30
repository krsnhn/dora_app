<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Destination;
use App\Models\TourPackage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::firstOrCreate(
            ['email' => 'admin@dora.app'],
            [
                'name' => 'DORA Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        // Create Demo Agency
        $agency = User::firstOrCreate(
            ['email' => 'agency@dora.app'],
            [
                'name' => 'Island Hopper Tours',
                'password' => Hash::make('password'),
                'role' => 'agency',
                'status' => 'active',
                'business_name' => 'Island Hopper Tours',
                'phone' => '+63 917 123 4567',
                'address' => 'Puerto Princesa, Palawan',
                'agency_status' => 'approved',
            ]
        );

        // Create Demo Traveler
        User::firstOrCreate(
            ['email' => 'traveler@dora.app'],
            [
                'name' => 'Alex Reyes',
                'password' => Hash::make('password'),
                'role' => 'traveler',
                'status' => 'active',
            ]
        );

        // Destinations Data
        $destinations = [
            [
                'name' => 'El Nido, Palawan',
                'country' => 'Philippines',
                'description' => 'A breathtaking archipelago with dramatic karst cliffs, turquoise lagoons, and pristine white sand beaches. One of Asia\'s most spectacular island destinations, famous for its dramatic limestone formations and crystal-clear waters perfect for snorkeling and island hopping.',
                'latitude' => 11.1759,
                'longitude' => 119.3872,
                'tags' => 'beach,island,snorkeling,diving,nature',
                'image_url' => 'https://images.unsplash.com/photo-1518548419970-58e3b4079ab2?w=800',
                'is_approved' => true,
            ],
            [
                'name' => 'Boracay Island',
                'country' => 'Philippines',
                'description' => 'World-renowned for its stunning White Beach and powdery white sand, Boracay offers a perfect blend of relaxation and nightlife. With crystal-clear waters and vibrant coral reefs, it remains one of the Philippines\' top beach destinations.',
                'latitude' => 11.9674,
                'longitude' => 121.9248,
                'tags' => 'beach,nightlife,water sports,resort,relaxation',
                'image_url' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=800',
                'is_approved' => true,
            ],
            [
                'name' => 'Chocolate Hills, Bohol',
                'country' => 'Philippines',
                'description' => 'A geological formation of over 1,200 perfectly cone-shaped hills spread across 50 square kilometers. During dry season, the grass-covered hills turn chocolate brown, creating a surreal landscape unique to the Philippines.',
                'latitude' => 9.8826,
                'longitude' => 124.1260,
                'tags' => 'nature,hiking,UNESCO,geology,tarsier',
                'image_url' => 'https://images.unsplash.com/photo-1555400038-63f5ba517a47?w=800',
                'is_approved' => true,
            ],
            [
                'name' => 'Intramuros, Manila',
                'country' => 'Philippines',
                'description' => 'The historic walled city of Manila, built during the Spanish colonial era in the 16th century. A living museum with cobblestone streets, ancient churches, and fortified walls that tell the story of the Philippines\' rich history.',
                'latitude' => 14.5891,
                'longitude' => 120.9758,
                'tags' => 'history,culture,heritage,architecture,city',
                'image_url' => 'https://images.unsplash.com/photo-1588400393236-d24e9b3be8d8?w=800',
                'is_approved' => true,
            ],
            [
                'name' => 'Siargao Island',
                'country' => 'Philippines',
                'description' => 'Known as the surfing capital of the Philippines, Siargao boasts world-class waves at Cloud 9, crystal lagoons, mangrove forests, and a laid-back island vibe. Perfect for surfers, nature lovers, and those seeking tropical paradise.',
                'latitude' => 9.8490,
                'longitude' => 126.0458,
                'tags' => 'surfing,beach,island,adventure,nature',
                'image_url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800',
                'is_approved' => true,
            ],
            [
                'name' => 'Batanes Islands',
                'country' => 'Philippines',
                'description' => 'The northernmost province of the Philippines, featuring rolling green hills, stone houses, dramatic cliffs, and a unique Ivatan culture. The landscapes are so stunning they feel like they belong to another world.',
                'latitude' => 20.4487,
                'longitude' => 121.9701,
                'tags' => 'culture,nature,photography,heritage,remote',
                'image_url' => 'https://images.unsplash.com/photo-1586348943529-beaae6c28db9?w=800',
                'is_approved' => true,
            ],
            [
                'name' => 'Vigan City',
                'country' => 'Philippines',
                'description' => 'A UNESCO World Heritage Site, Vigan preserves the best-surviving example of a planned Spanish colonial town in Asia. Walk along cobblestone Calle Crisologo, ride a kalesa, and savor Ilocano cuisine in this living heritage city.',
                'latitude' => 17.5747,
                'longitude' => 120.3869,
                'tags' => 'UNESCO,history,heritage,culture,architecture',
                'image_url' => 'https://images.unsplash.com/photo-1596422846543-75c6fc197f07?w=800',
                'is_approved' => true,
            ],
            [
                'name' => 'Mount Apo',
                'country' => 'Philippines',
                'description' => 'The highest mountain in the Philippines at 2,954 meters, Mount Apo is a protected natural park offering challenging treks through diverse ecosystems including mossy forest, volcanic lakes, and geothermal activity.',
                'latitude' => 6.9882,
                'longitude' => 125.2705,
                'tags' => 'hiking,trekking,nature,adventure,wildlife',
                'image_url' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=800',
                'is_approved' => true,
            ],
            [
                'name' => 'Coron, Palawan',
                'country' => 'Philippines',
                'description' => 'Famous for its stunning lakes, Japanese WWII wrecks, and pristine coral reefs, Coron is a diver\'s paradise. Kayangan Lake, Twin Lagoon, and Barracuda Lake are among the most beautiful in Southeast Asia.',
                'latitude' => 11.9987,
                'longitude' => 120.2038,
                'tags' => 'diving,snorkeling,lake,wreck diving,nature',
                'image_url' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800',
                'is_approved' => true,
            ],
            [
                'name' => 'Camiguin Island',
                'country' => 'Philippines',
                'description' => 'The "Island Born of Fire" has more volcanoes per square kilometer than any island in the world. With white island sandbar, sunken cemetery, cold and hot springs, and cascading waterfalls, it offers an incredibly diverse experience.',
                'latitude' => 9.1651,
                'longitude' => 124.7204,
                'tags' => 'volcano,beach,adventure,hot springs,nature',
                'image_url' => 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?w=800',
                'is_approved' => true,
            ],
            [
                'name' => 'Rice Terraces, Banaue',
                'country' => 'Philippines',
                'description' => 'Often called the "Eighth Wonder of the World," these 2,000-year-old terraces were carved into the mountains by the Ifugao people. A UNESCO World Heritage Site and living testament to human engineering in harmony with nature.',
                'latitude' => 16.9178,
                'longitude' => 121.0589,
                'tags' => 'UNESCO,culture,heritage,nature,photography,trekking',
                'image_url' => 'https://images.unsplash.com/photo-1551410224-699683e15636?w=800',
                'is_approved' => true,
            ],
            [
                'name' => 'Apo Island',
                'country' => 'Philippines',
                'description' => 'A protected marine sanctuary famous for sea turtle encounters and world-class snorkeling and diving. The community-managed reef is one of the most biodiverse in the Coral Triangle, teeming with marine life.',
                'latitude' => 9.0760,
                'longitude' => 123.2691,
                'tags' => 'diving,sea turtles,marine sanctuary,snorkeling,nature',
                'image_url' => 'https://images.unsplash.com/photo-1583212292454-1fe6229603b7?w=800',
                'is_approved' => true,
            ],
            [
                'name' => 'Hundred Islands, Pangasinan',
                'country' => 'Philippines',
                'description' => 'A national park featuring 124 islands and islets of varying sizes in the Lingayen Gulf. Explore sea caves, coral gardens, and secluded beaches on a day trip from Manila.',
                'latitude' => 16.1877,
                'longitude' => 119.9926,
                'tags' => 'island,beach,snorkeling,cave,nature',
                'image_url' => 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?w=800',
                'is_approved' => true,
            ],
            [
                'name' => 'Tubbataha Reef',
                'country' => 'Philippines',
                'description' => 'A UNESCO World Heritage Site and one of the world\'s best dive sites, accessible only from March to June. This remote reef in the Sulu Sea is home to sharks, manta rays, and hundreds of fish and coral species.',
                'latitude' => 8.9985,
                'longitude' => 119.9261,
                'tags' => 'diving,UNESCO,wildlife,remote,marine sanctuary',
                'image_url' => 'https://images.unsplash.com/photo-1546026423-cc4642628d2b?w=800',
                'is_approved' => true,
            ],
            [
                'name' => 'Puerto Princesa Underground River',
                'country' => 'Philippines',
                'description' => 'A UNESCO World Heritage Site and one of the New Seven Wonders of Nature. Navigate through stunning limestone karst landscape featuring ancient forest and an eight-kilometer underground river.',
                'latitude' => 10.1817,
                'longitude' => 118.8816,
                'tags' => 'UNESCO,cave,nature,wildlife,adventure',
                'image_url' => 'https://images.unsplash.com/photo-1591204579000-af11f62e9bed?w=800',
                'is_approved' => true,
            ],
            [
                'name' => 'Lake Sebu, South Cotabato',
                'country' => 'Philippines',
                'description' => 'Home of the T\'boli people, Lake Sebu offers cultural immersion, zipline adventures over waterfalls, tilapia fishing, and the famous Seven Falls. A gem of Mindanao rarely visited by tourists.',
                'latitude' => 6.1667,
                'longitude' => 124.6333,
                'tags' => 'culture,lake,zipline,waterfall,indigenous',
                'image_url' => 'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?w=800',
                'is_approved' => true,
            ],
            [
                'name' => 'Bali, Indonesia',
                'country' => 'Indonesia',
                'description' => 'The Island of the Gods captivates with its dramatic landscapes, rich spiritual culture, and world-class hospitality. From terraced rice paddies and ancient temples to vibrant markets and sunset beach clubs.',
                'latitude' => -8.3405,
                'longitude' => 115.0920,
                'tags' => 'culture,temple,beach,yoga,spiritual,spa',
                'image_url' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=800',
                'is_approved' => true,
            ],
            [
                'name' => 'Angkor Wat, Siem Reap',
                'country' => 'Cambodia',
                'description' => 'The largest religious monument in the world and a masterpiece of Khmer architecture. Watch the sunrise over its iconic spires, explore hidden jungle temples, and immerse yourself in a 1,000-year-old civilization.',
                'latitude' => 13.4125,
                'longitude' => 103.8670,
                'tags' => 'UNESCO,history,temple,culture,sunrise,architecture',
                'image_url' => 'https://images.unsplash.com/photo-1508159452718-d22f6734a236?w=800',
                'is_approved' => true,
            ],
            [
                'name' => 'Ha Long Bay',
                'country' => 'Vietnam',
                'description' => 'A UNESCO World Heritage Site featuring thousands of towering limestone karsts and islets, rising from emerald waters. Cruise through mystical mists, explore sea caves, and kayak through hidden lagoons in this otherworldly landscape.',
                'latitude' => 20.9101,
                'longitude' => 107.1839,
                'tags' => 'UNESCO,cruise,kayaking,cave,nature,photography',
                'image_url' => 'https://images.unsplash.com/photo-1510412729103-f0b1f4a6f2ae?w=800',
                'is_approved' => true,
            ],
            [
                'name' => 'Komodo Island',
                'country' => 'Indonesia',
                'description' => 'Home to the legendary Komodo dragon and some of the world\'s most spectacular diving. Trek through rugged landscapes to spot the world\'s largest lizard, then dive into waters teeming with manta rays and diverse marine life.',
                'latitude' => -8.5500,
                'longitude' => 119.4833,
                'tags' => 'wildlife,diving,trekking,UNESCO,adventure,nature',
                'image_url' => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=800',
                'is_approved' => true,
            ],
            [
                'name' => 'Phi Phi Islands',
                'country' => 'Thailand',
                'description' => 'A stunning archipelago of six islands in the Andaman Sea, made famous as a filming location for "The Beach." With towering limestone cliffs, turquoise bays, and vibrant nightlife, Phi Phi is quintessential Thailand.',
                'latitude' => 7.7407,
                'longitude' => 98.7784,
                'tags' => 'beach,diving,snorkeling,nightlife,island',
                'image_url' => 'https://images.unsplash.com/photo-1504214208698-ea1916a2195a?w=800',
                'is_approved' => true,
            ],
            [
                'name' => 'Kyoto, Japan',
                'country' => 'Japan',
                'description' => 'Japan\'s ancient imperial capital enchants with over 1,600 Buddhist temples, 400 Shinto shrines, and traditional wooden machiya townhouses. Experience the magic of geishas, tea ceremonies, and spring cherry blossoms.',
                'latitude' => 35.0116,
                'longitude' => 135.7681,
                'tags' => 'culture,temple,history,cherry blossoms,geisha,UNESCO',
                'image_url' => 'https://images.unsplash.com/photo-1528360983277-13d401cdc186?w=800',
                'is_approved' => true,
            ],
        ];

        foreach ($destinations as $destData) {
            $destination = Destination::firstOrCreate(
                ['name' => $destData['name'], 'country' => $destData['country']],
                array_merge($destData, ['created_by' => 1])
            );

            // Create a sample package for each destination
            TourPackage::firstOrCreate(
                ['name' => 'Explore ' . $destData['name'], 'agency_id' => $agency->id],
                [
                    'destination_id' => $destination->id,
                    'agency_id' => $agency->id,
                    'description' => 'Discover the best of ' . $destData['name'] . ' with our expertly curated tour package. Includes accommodation, guided tours, and local experiences.',
                    'price' => rand(2999, 15999),
                    'duration' => rand(3, 7),
                    'inclusions' => "Round-trip airfare\nHotel accommodation\nBreakfast daily\nTour guide\nAirport transfers\nEntrance fees",
                    'status' => 'active',
                ]
            );
        }

        $this->command->info('✅ Seeded: 1 admin, 1 agency, 1 traveler, ' . count($destinations) . ' destinations with packages.');
        $this->command->info('');
        $this->command->info('Login credentials:');
        $this->command->info('  Admin:    admin@dora.app / password');
        $this->command->info('  Agency:   agency@dora.app / password');
        $this->command->info('  Traveler: traveler@dora.app / password');
    }
}
