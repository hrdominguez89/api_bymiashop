<?php /** @noinspection SpellCheckingInspection */

namespace App\DataFixtures;

trait FixturesTrait
{

    /**
     * @return \string[][]
     */
    public function getSocialNetwork(): array
    {
        return [
            ["type" => 'facebook', "url" => "/", "icon" => 'fab fa-facebook-f', 'color' => '#3c5a99'],
            ["type" => 'twitter', "url" => "/", "icon" => 'fab fa-twitter', 'color' => '#00a2e8'],
            ["type" => 'youtube', "url" => "/", "icon" => 'fab fa-youtube', 'color' => '#e52e2e'],
            ["type" => 'instagram', "url" => "/", "icon" => 'fab fa-instagram', 'color' => '#815dc7'],
            ["type" => 'rss', "url" => "/", "icon" => 'fas fa-rss', 'color' => '#ffc338'],
        ];
    }

    /**
     * @return array[]
     */
    public function getReviews(): array
    {
        return [
            [
                "avatar" => 'assets/images/avatars/avatar-1.jpg',
                "author" => 'Samantha Smith',
                "rating" => 4,
                "text" => 'Phasellus id mattis nulla. Mauris velit nisi, imperdiet vitae sodales in, maximus ut lectus. Vivamus commodo '.
                    'scelerisque lacus, at porttitor dui iaculis id. Curabitur imperdiet ultrices fermentum.',
            ],
            [
                "avatar" => 'assets/images/avatars/avatar-2.jpg',
                "author" => 'Adam Taylor',
                "rating" => 3,
                "text" => 'Aenean non lorem nisl. Duis tempor sollicitudin orci, eget tincidunt ex semper sit amet. Nullam neque justo, '.
                    'sodales congue feugiat ac, facilisis a augue. Donec tempor sapien et fringilla facilisis. Nam maximus consectetur '.
                    'diam. Nulla ut ex mollis, volutpat tellus vitae, accumsan ligula.',
            ],
            [
                "avatar" => 'assets/images/avatars/avatar-3.jpg',
                "author" => 'Helena Garcia',
                "rating" => 5,
                "text" => 'Duis ac lectus scelerisque quam blandit egestas. Pellentesque hendrerit eros laoreet suscipit ultrices.',
            ],
        ];
    }

    /**
     * @return string[][]
     */
    public function getFeatures(): array
    {
        return [
            [
                'title' => 'Free Shipping',
                'image' => '#',
                'description' => 'For orders from $50',
            ],
            [
                'title' => 'Support 24/7',
                'image' => '#',
                'description' => 'Call us anytime',
            ],
            [
                'title' => '100% Safety',
                'image' => '#',
                'description' => 'Only secure payments',
            ],
            [
                'title' => 'Hot Offers',
                'image' => '#',
                'description' => 'Discounts up to 90%',
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function getCategories(): array
    {
        return [
            [
                "name" => "Instruments",
                "slug" => "instruments",
                "image" => 'assets/images/categories/category-1.jpg',
                "items" => 272,
                "children" => [
                    [
                        "name" => "Power Tools",
                        "slug" => "power-tools",
                        "image" => "assets/images/categories/category-1.jpg",
                        "items" => 370,
                        "children" => [],
                    ],
                    [
                        "name" => "Hand Tools",
                        "slug" => "hand-tools",
                        "image" => "assets/images/categories/category-2.jpg",
                        "items" => 134,
                    ],
                    [
                        "name" => "Machine Tools",
                        "slug" => "machine-tools",
                        "image" => "assets/images/categories/category-3.jpg",
                        "items" => 302,
                    ],
                    [
                        "name" => "Power Machinery",
                        "slug" => "power-machinery",
                        "image" => "assets/images/categories/category-4.jpg",
                        "items" => 79,
                    ],
                    [
                        "name" => "Measurement",
                        "slug" => "measurement",
                        "image" => "assets/images/categories/category-5.jpg",
                        "items" => 366,
                    ],
                    [
                        "name" => "Clothes and PPE",
                        "slug" => "clothes-and-ppe",
                        "image" => "assets/images/categories/category-6.jpg",
                        "items" => 82,
                    ],
                ],
            ],
            [
                "name" => "Electronics",
                "slug" => "electronics",
                "image" => "assets/images/categories/category-2.jpg",
                "items" => 54,
                "children" => [
                    [
                        "name" => "Drills Mixers",
                        "slug" => "drills-mixers",
                        "items" => 57,
                    ],
                    [
                        "name" => "Cordless Screwdrivers",
                        "slug" => "cordless-screwdrivers",
                        "items" => 15,
                    ],
                    [
                        "name" => "Screwdrivers",
                        "slug" => "screwdrivers",
                        "items" => 126,
                    ],
                    [
                        "name" => "Wrenches",
                        "slug" => "wrenches",
                        "items" => 12,
                    ],
                    [
                        "name" => "Grinding Machines",
                        "slug" => "grinding-machines",
                        "items" => 25,
                    ],
                    [
                        "name" => "Milling Cutters",
                        "slug" => "milling-cutters",
                        "items" => 78,
                    ],
                    [
                        "name" => "Electric Spray Guns",
                        "slug" => "electric-spray-guns",
                        "items" => 3,
                    ],
                ],
            ],
            [
                "name" => "Computers",
                "slug" => "computers",
                "items" => 421,
                "image" => "assets/images/categories/category-3.jpg",
                "children" => [
                    [
                        "name" => "Tool Kits",
                        "slug" => "tool-kits",
                        "items" => 57,
                    ],
                    [
                        "name" => "Hammers",
                        "slug" => "hammers",
                        "items" => 15,
                    ],
                    [
                        "name" => "Spanners",
                        "slug" => "spanners",
                        "items" => 5,
                    ],
                    [
                        "name" => "Handsaws",
                        "slug" => "handsaws",
                        "items" => 54,
                    ],
                    [
                        "name" => "Paint Tools",
                        "slug" => "paint-tools",
                        "items" => 13,
                    ],
                ],
            ],
            [
                "name" => "Automotive",
                "slug" => "automotive",
                "items" => 182,
                "image" => "assets/images/categories/category-4.jpg",
                "children" => [
                    [
                        "name" => "Lathes",
                        "slug" => "lathes",
                        "items" => 104,
                    ],
                    [
                        "name" => "Milling Machines",
                        "slug" => "milling-machines",
                        "items" => 12,
                    ],
                    [
                        "name" => "Grinding Machines",
                        "slug" => "grinding-machines",
                        "items" => 67,
                    ],
                    [
                        "name" => "CNC Machines",
                        "slug" => "cnc-machines",
                        "items" => 5,
                    ],
                    [
                        "name" => "Sharpening Machines",
                        "slug" => "sharpening-machines",
                        "items" => 88,
                    ],
                ],
            ],
            [
                "name" => "Furniture Appliances",
                "slug" => "furniture-appliances",
                "image" => "assets/images/categories/category-5.jpg",
                "items" => 15,
                "children" => [
                    [
                        "name" => "Generators",
                        "slug" => "generators",
                        "items" => 23,
                    ],
                    [
                        "name" => "Compressors",
                        "slug" => "compressors",
                        "items" => 76,
                    ],
                    [
                        "name" => "Winches",
                        "slug" => "winches",
                        "items" => 43,
                    ],
                    [
                        "name" => "Plasma Cutting",
                        "slug" => "plasma-cutting",
                        "items" => 128,
                    ],
                    [
                        "name" => "Electric Motors",
                        "slug" => "electric-motors",
                        "items" => 76,
                    ],
                ],
            ],
        ];
    }


    /**
     * @return array[]
     */
    public function getProducts(): array
    {
        return [
            [
                "slug" => "electric-planer-brandix-kl370090g-300-watts",
                "name" => "Electric Planer Brandix KL370090G 300 Watts",
                "price" => 749,
                "images" => [
                    "assets/images/products/product-1.jpg",
                    "assets/images/products/product-1-1.jpg",
                ],
                "badges" => "new",
                "rating" => 4,
                "reviews" => 12,
                "availability" => "in-stock",
                "brand" => "brandix",
                "categories" => [
                    "screwdrivers",
                ],
                "attributes" => [
                    [
                        "slug" => "color",
                        "values" => "yellow",
                    ],
                    [
                        "slug" => "speed",
                        "values" => "750-rpm",
                        "featured" => true,
                    ],
                    [
                        "slug" => "battery-capacity",
                        "values" => "2-Ah",
                        "featured" => true,
                    ],
                ],
            ],
            [
                "slug" => "undefined-tool-iradix-dps3000sy-2700-watts",
                "name" => "Undefined Tool IRadix DPS3000SY 2700 Watts",
                "price" => 1019,
                "images" => [
                    "assets/images/products/product-2.jpg",
                    "assets/images/products/product-2-1.jpg",
                    "assets/images/products/product-2.jpg",
                    "assets/images/products/product-2-1.jpg",
                ],
                "badges" => "hot",
                "rating" => 5,
                "reviews" => 3,
                "availability" => "in-stock",
                "brand" => "zosch",
                "categories" => [
                    "power-tools",
                ],
                "attributes" => [
                    [
                        "slug" => "color",
                        "values" => [
                            "silver",
                            "cerise",
                        ],
                    ],
                    [
                        "slug" => "speed",
                        "values" => "750-rpm",
                        "featured" => true,
                    ],
                    [
                        "slug" => "power-source",
                        "values" => "cordless-electric",
                        "featured" => true,
                    ],
                    [
                        "slug" => "battery-cell-type",
                        "values" => "lithium",
                        "featured" => true,
                    ],
                    [
                        "slug" => "voltage",
                        "values" => "20-volts",
                        "featured" => true,
                    ],
                    [
                        "slug" => "battery-capacity",
                        "values" => "2-Ah",
                        "featured" => true,
                    ],
                ],
            ],
            [
                "slug" => "drill-screwdriver-brandix-alx7054-200-watts",
                "name" => "Drill Screwdriver Brandix ALX7054 200 Watts",
                "price" => 850,
                "images" => [
                    "assets/images/products/product-3.jpg",
                    "assets/images/products/product-3-1.jpg",
                    "assets/images/products/product-3-1.jpg",
                ],
                "rating" => 4,
                "reviews" => 8,
                "availability" => "in-stock",
                "brand" => "brandix",
                "categories" => [
                    "power-tools",
                ],
                "attributes" => [
                    [
                        "slug" => "voltage",
                        "values" => "20-volts",
                        "featured" => true,
                    ],
                    [
                        "slug" => "battery-capacity",
                        "values" => "2-Ah",
                        "featured" => true,
                    ],
                ],
            ],
            [
                "slug" => "drill-series-3-brandix-ksr4590pqs-1500-watts",
                "name" => "Drill Series 3 Brandix KSR4590PQS 1500 Watts",
                "price" => 949,
                "compareAt" => 1189,
                "images" => [
                    "assets/images/products/product-4.jpg",
                    "assets/images/products/product-4-1.jpg",
                ],
                "badges" => "sale",
                "rating" => 3,
                "reviews" => 15,
                "availability" => "in-stock",
                "brand" => "brandix",
                "categories" => [
                    "power-tools",
                ],
                "attributes" => [
                    [
                        "slug" => "color",
                        "values" => "white",
                    ],
                    [
                        "slug" => "speed",
                        "values" => "750-rpm",
                        "featured" => true,
                    ],
                    [
                        "slug" => "power-source",
                        "values" => "cordless-electric",
                        "featured" => true,
                    ],
                    [
                        "slug" => "battery-cell-type",
                        "values" => "lithium",
                        "featured" => true,
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string[][]
     */
    public function getBannersImages(): array
    {
        return [
            [
                "title" => "Big choice of<br>Plumbing products",
                "text" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>Etiam pharetra laoreet dui quis molestie.",
                "image_classic" => "assets/images/slides/slide-1.jpg",
                "image_full" => "assets/images/slides/slide-1-full.jpg",
                "image_mobile" => "assets/images/slides/slide-1-mobile.jpg",
            ],
            [
                "title" => "Screwdrivers<br>Professional Tools",
                "text" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>Etiam pharetra laoreet dui quis molestie.",
                "image_classic" => "assets/images/slides/slide-2.jpg",
                "image_full" => "assets/images/slides/slide-2-full.jpg",
                "image_mobile" => "assets/images/slides/slide-2-mobile.jpg",
            ],
            [
                "title" => "One more<br>Unique header",
                "text" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>Etiam pharetra laoreet dui quis molestie.",
                "image_classic" => "assets/images/slides/slide-3.jpg",
                "image_full" => "assets/images/slides/slide-3-full.jpg",
                "image_mobile" => "assets/images/slides/slide-3-mobile.jpg",
            ],
        ];
    }

    /**
     * @return string[][]
     */
    public function getBrands(): array
    {
        return [
            [
                "name" => "Brandix",
                "slug" => "brandix",
                "image" => "assets/images/logos/logo-1.png",
            ],
            [
                "name" => "Wakita",
                "slug" => "wakita",
                "image" => "assets/images/logos/logo-2.png",
            ],
            [
                "name" => "Zosch",
                "slug" => "zosch",
                "image" => "assets/images/logos/logo-3.png",
            ],
            [
                "name" => "WeVALT",
                "slug" => "wevalt",
                "image" => "assets/images/logos/logo-4.png",
            ],
            [
                "name" => "Hammer",
                "slug" => "hammer",
                "image" => "assets/images/logos/logo-5.png",
            ],
            [
                "name" => "Mitasia",
                "slug" => "mitasia",
                "image" => "assets/images/logos/logo-6.png",
            ],
            [
                "name" => "Metaggo",
                "slug" => "metaggo",
                "image" => "assets/images/logos/logo-7.png",
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function getAttributes(): array
    {
        return [
            [
                "name" => "Color",
                "slug" => "color",
                "values" => [
                    [
                        "name" => "Green",
                        "slug" => "green",
                        "customFieldsValue" => "assets/images/products/product-8.jpg",
                        "customFieldsType" => "image",
                    ],
                    [
                        "name" => "Blue",
                        "slug" => "blue",
                        "customFieldsValue" => "assets/images/products/product-8.jpg",
                        "customFieldsType" => "image",
                    ],
                ],
            ],
            [
                "name" => "Color",
                "slug" => "color",
                "values" => [
                    [
                        "name" => "Red",
                        "slug" => "red",
                        "customFieldsValue" => "#d60000",
                        "customFieldsType" => "color",
                    ],
                    [
                        "name" => "White",
                        "slug" => "white",
                        "customFieldsValue" => "#ffffff",
                        "customFieldsType" => "color",
                    ],
                ],
            ],
            [
                "name" => "Speed",
                "slug" => "speed",
                "values" => [
                    [
                        "name" => "750 RPM",
                        "slug" => "750-rpm",
                        "customFieldsType" => "select",
                    ],
                    [
                        "name" => "850 RPM",
                        "slug" => "850-rpm",
                        "customFieldsType" => "select",
                    ],
                ],
            ],
            [
                "name" => "Power Source",
                "slug" => "power-source",
                "values" => [
                    [
                        "name" => "Cordless-Electric",
                        "slug" => "cordless-electric",
                        "customFieldsType" => "check",
                    ],
                ],
            ],
            [
                "name" => "Battery Cell Type",
                "slug" => "battery-cell-type",
                "values" => [
                    [
                        "name" => "Lithium",
                        "slug" => "lithium",
                        "customFieldsType" => "check",
                    ],
                ],
            ],
            [
                "name" => "Voltage",
                "slug" => "voltage",
                "values" => [
                    [
                        "name" => "20 Volts",
                        "slug" => "20-volts",
                        "customFieldsType" => "check",
                    ],
                    [
                        "name" => "21 Volts",
                        "slug" => "21-volts",
                        "customFieldsType" => "check",
                    ],
                ],
            ],
            [
                "name" => "Battery Capacity",
                "slug" => "battery-capacity",
                "values" => [
                    [
                        "name" => "2 Ah",
                        "slug" => "2-Ah",
                        "customFieldsType" => "check",
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->getNames().' '.$this->getLastNames();
    }

    /**
     * @return string
     */
    public function getNames(): string
    {
        $man = array(
            'Antonio',
            'José',
            'Manuel',
            'Francisco',
            'Juan',
            'David',
            'José Antonio',
            'José Luis',
            'Jesús',
            'Javier',
            'Francisco Javier',
            'Carlos',
            'Daniel',
            'Miguel',
            'Rafael',
            'Pedro',
            'José Manuel',
            'Ángel',
            'Alejandro',
            'Miguel Ángel',
            'José María',
            'Fernando',
            'Luis',
            'Sergio',
            'Pablo',
            'Jorge',
            'Alberto',
        );
        $woman = array(
            'María Carmen',
            'María',
            'Carmen',
            'Josefa',
            'Isabel',
            'Ana María',
            'María Dolores',
            'María Pilar',
            'María Teresa',
            'Ana',
            'Francisca',
            'Laura',
            'Antonia',
            'Dolores',
            'María Angeles',
            'Cristina',
            'Marta',
            'María José',
            'María Isabel',
            'Pilar',
            'María Luisa',
            'Concepción',
            'Lucía',
            'Mercedes',
            'Manuela',
            'Elena',
            'Rosa María',
        );

        return mt_rand() % 2 ? $man[array_rand($man)] : $woman[array_rand($woman)];
    }

    /**
     * @return string
     */
    public function getLastNames(): string
    {
        $lastNames = array(
            'García',
            'González',
            'Rodríguez',
            'Fernández',
            'López',
            'Martínez',
            'Sánchez',
            'Pérez',
            'Gómez',
            'Martín',
            'Jiménez',
            'Ruiz',
            'Hernández',
            'Díaz',
            'Moreno',
            'Álvarez',
            'Muñoz',
            'Romero',
            'Alonso',
            'Gutiérrez',
            'Navarro',
            'Torres',
            'Domínguez',
            'Vázquez',
            'Ramos',
            'Gil',
            'Ramírez',
            'Serrano',
            'Blanco',
            'Suárez',
            'Molina',
            'Morales',
            'Ortega',
            'Delgado',
            'Castro',
            'Ortíz',
            'Rubio',
            'Marín',
            'Sanz',
            'Iglesias',
            'Nuñez',
            'Medina',
            'Garrido',
        );

        return $lastNames[array_rand($lastNames)].' '.$lastNames[array_rand($lastNames)];
    }

}
