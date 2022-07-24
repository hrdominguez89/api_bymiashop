<?php /** @noinspection SpellCheckingInspection */

namespace App\DataFixtures;

use App\Entity\AboutUs;
use App\Entity\Advertisements;
use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Configuration;
use App\Entity\ContactUs;
use App\Entity\CouponDiscount;
use App\Entity\CoverImage;
use App\Entity\Customer;
use App\Entity\PayPal;
use App\Entity\Product;
use App\Entity\ProductImages;
use App\Entity\ProductReviews;
use App\Entity\ProductSpecification;
use App\Entity\ProductSubcategory;
use App\Entity\SocialNetwork;
use App\Entity\Specification;
use App\Entity\Subcategory;
use App\Entity\TermsConditions;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

class AppFixtures extends Fixture
{
    use FixturesTrait;

    /** @var UserPasswordHasherInterface $encoder */
    private $encoder;

    /**
     * AppFixtures constructor.
     * @param UserPasswordHasherInterface $encoder
     */
    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $this
            ->createSocialNetworksLinks($manager)
            ->createCouponDiscount($manager)
            ->createUsers($manager)
            ->createCustomers($manager)
            ->createPaymentMethods($manager)
            ->createAds($manager)
            ->createContactUs($manager)
            ->createTermConditions($manager)
            ->createAboutUs($manager)
            ->createFeatures($manager)
            ->createBanners($manager)
            ->createBrands($manager)
            ->createAttributes($manager)
            ->createCategories($manager)
            ->createProducts($manager);
    }

    /**
     * @param ObjectManager $manager
     * @return AppFixtures
     */
    private function createSocialNetworksLinks(ObjectManager $manager): AppFixtures
    {
        foreach ($this->getSocialNetwork() as $item) {
            $entity = new SocialNetwork();
            $manager->persist(
                $entity
                    ->setName(strtoupper($item['type']))
                    ->setUrl($item['url'])
                    ->setType($item['type'])
                    ->setColor($item['color'])
                    ->setIcon($item['icon'])
            );
        }

        $manager->flush();

        return $this;
    }

    /**
     * @param ObjectManager $manager
     * @return AppFixtures
     */
    private function createCouponDiscount(ObjectManager $manager): AppFixtures
    {
        for ($i = 0; $i < 50; $i++) {

            $isPercent = rand(2, 10) % 2;

            $entity = new CouponDiscount();
            $manager->persist(
                $entity
                    ->setNumberOfUses(rand(5, 50))
                    ->setPercent($isPercent)
                    ->setValue($isPercent ? 10 : rand(20, 50))
                    ->setNro(Uuid::v6())
            );
        }

        $manager->flush();

        return $this;
    }

    /**
     * @param ObjectManager $manager
     * @return AppFixtures
     */
    private function createPaymentMethods(ObjectManager $manager): AppFixtures
    {
        $entity = new PayPal();
        $manager->persist(
            $entity
                ->setClientIdSandBox('AePp7Mw0k1bUpjOmul2IOA6l4bXCmgftJ0uL-SCba5jxJVQFpsnRZQQAiC0yVAB8IaIzhjWCstOXH1i2')
                ->setClientSecretSandBox(
                    'EDWNc8fK0d3zQ0eL8X0nMla1UujaC6-78Mi_zQvhRn5J_S4y6c7rceRRHVIDjmP0v4Fd9vG_Zy9zJ0Dg'
                )
        );

        $manager->flush();

        return $this;
    }

    /**
     * @param ObjectManager $manager
     * @return AppFixtures
     */
    private function createAds(ObjectManager $manager): AppFixtures
    {
        $entity = new Advertisements();
        $manager->persist(
            $entity
                ->setSrc1('assets/images/banners/banner-1.jpg')
                ->setSrcSm1('assets/images/banners/banner-1-mobile.jpg')
                ->setSrc2('assets/images/banners/banner-1.jpg')
                ->setSrcSm2('assets/images/banners/banner-1-mobile.jpg')
                ->setSrc3('assets/images/banners/banner-1.jpg')
                ->setSrcSm3('assets/images/banners/banner-1-mobile.jpg')
        );

        $manager->flush();

        return $this;
    }

    /**
     * @param ObjectManager $manager
     * @return AppFixtures
     */
    private function createContactUs(ObjectManager $manager): AppFixtures
    {
        $entity = new ContactUs();
        $manager->persist(
            $entity
                ->setEmail('solyag@example.com')
                ->setAddress('715 Fake Street, New York 10021 USA')
                ->setPhoneMain('(800) 060-0730')
                ->setPhoneOther('(800) 060-0730')
                ->setUrl(
                    'https://maps.google.com/maps?q=Calle%20H,%20Santo%20Domingo,%20Rep%C3%BAblica%20Dominicana&t=&z=13&ie=UTF8&iwloc=&output=embed'
                )
        );

        $manager->flush();

        return $this;
    }

    /**
     * @param ObjectManager $manager
     * @return AppFixtures
     */
    private function createAboutUs(ObjectManager $manager): AppFixtures
    {
        $entity = new AboutUs();
        $manager->persist(
            $entity
                ->setDescription(
                    "<p>
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras lacus metus, convallis ut leo nec, tincidunt
eleifend justo. Ut felis orci, hendrerit a pulvinar et, gravida ac lorem. Sed vitae molestie sapien, at
sollicitudin tortor.
</p>
<p>
Duis id volutpat libero, id vestibulum purus.Donec euismod accumsan felis,egestas lobortis velit tempor vitae.
Integer eget velit fermentum, dignissim odio non, bibendum velit.
</p>"
                )
        );

        $manager->flush();

        return $this;
    }

    /**
     * @param ObjectManager $manager
     * @return AppFixtures
     */
    private function createTermConditions(ObjectManager $manager): AppFixtures
    {
        $entity = new TermsConditions();
        $manager->persist(
            $entity
                ->setDescription(
                    "<p>
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec facilisis neque ut purus fermentum, ac pretium
nibh facilisis. Vivamus venenatis viverra iaculis. Suspendisse tempor orci non sapien ullamcorper dapibus.
Suspendisse at velit diam. Donec pharetra nec enim blandit vulputate. Suspendisse potenti. Pellentesque et
molestie ante. In feugiat ante vitae ultricies malesuada.
</p>

<h2>Definitions</h2>

<ol>
<li>
<strong>Risus</strong> — Morbi posuere eleifend sollicitudin. Praesent eget ante in enim scelerisque
scelerisque. Donec mi lorem, molestie a sapien non, laoreet convallis felis. In semper felis in lacus
venenatis, sit amet commodo leo interdum. Maecenas congue ut leo et auctor.
</li>
<li>
<strong>Praesent</strong> — Class aptent taciti sociosqu ad litora torquent per conubia nostra, per
inceptos himenaeos. Nulla orci ante, viverra in imperdiet in, pharetra et leo
</li>
<li>
<strong>Vestibulum</strong> — Vestibulum arcu tellus, aliquam vel fermentum vestibulum, lacinia pulvinar
ipsum. In hac habitasse platea dictumst. Integer felis libero, blandit scelerisque mauris eget, porta
elementum sapien. Mauris luctus arcu non enim lobortis gravida.
</li>
</ol>

<h2>Ornare dolor</h2>

<p>
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec facilisis neque ut purus fermentum, ac pretium
nibh facilisis. Vivamus venenatis viverra iaculis. Suspendisse tempor orci non sapien ullamcorper dapibus.
Suspendisse at velit diam. Donec pharetra nec enim blandit vulputate. Suspendisse potenti. Pellentesque et
molestie ante. In feugiat ante vitae ultricies malesuada.
</p>"
                )
        );

        $manager->flush();

        return $this;
    }

    /**
     * @param ObjectManager $manager
     * @return AppFixtures
     */
    private function createFeatures(ObjectManager $manager): AppFixtures
    {
        foreach ($this->getFeatures() as $featureDef) {
            $entity = new Configuration();
            $manager->persist(
                $entity
                    ->setTitle($featureDef['title'])
                    ->setDescription($featureDef['description'])
            );
        }

        $manager->flush();

        return $this;
    }

    /**
     * @param ObjectManager $manager
     * @return AppFixtures
     */
    private function createBanners(ObjectManager $manager): AppFixtures
    {
        foreach ($this->getBannersImages() as $key => $bannersImageDef) {
            $entity = new CoverImage();
            $manager->persist(
                $entity
                    ->setTitle($bannersImageDef['title'])
                    ->setDescription($bannersImageDef['text'])
                    ->setImageLg($bannersImageDef['image_full'])
                    ->setImageSm($bannersImageDef['image_mobile'])
                    ->setNameBtn('Buy now')
                    ->setMain($key == 0)
            );
        }

        $manager->flush();

        return $this;
    }

    /**
     * @param ObjectManager $manager
     * @return $this
     */
    private function createBrands(ObjectManager $manager): AppFixtures
    {
        foreach ($this->getBrands() as $brandDef) {
            $entity = new Brand();
            $manager->persist($entity->setName($brandDef['name'])->setApiId(Uuid::v6())->setImage($brandDef['image']));
        }

        $manager->flush();

        return $this;
    }

    /**
     * @param ObjectManager $manager
     * @return AppFixtures
     */
    private function createCategories(ObjectManager $manager): AppFixtures
    {
        foreach ($this->getCategories() as $categoryDef) {

            $entity = new Category();
            $manager->persist(
                $entity
                    ->setName($categoryDef['name'])
                    ->setImage($categoryDef['image'])
                    ->setApiId(Uuid::v6())
            );

            $manager->flush();

            if (isset($categoryDef['children'])) {
                foreach ($categoryDef['children'] as $childDef) {
                    $entitySub = new Subcategory();
                    $manager->persist(
                        $entitySub
                            ->setCategoryId($entity)
                            ->setName($childDef['name'])
                            ->setApiId(Uuid::v6())
                    );
                }
            }
            $manager->flush();

        }

        return $this;
    }

    /**
     * @param ObjectManager $manager
     * @return AppFixtures
     */
    private function createAttributes(ObjectManager $manager): AppFixtures
    {
        foreach ($this->getAttributes() as $attributeDef) {
            $spf = new Specification();
            $manager->persist($spf->setName($attributeDef['name']));
        }
        $manager->flush();

        return $this;
    }

    /**
     * @param ObjectManager $manager
     * @return AppFixtures
     * @throws \Exception
     */
    private function createProducts(ObjectManager $manager): AppFixtures
    {
        $categories = $manager->getRepository(Subcategory::class)->findAll();
        $attributes = $manager->getRepository(Specification::class)->findAll();
        $brands = $manager->getRepository(Brand::class)->findAll();
        $customers = $manager->getRepository(Customer::class)->findAll();

        foreach ($categories as $category) {
            foreach ($this->getProducts() as $key => $productDef) {

                $parentId = Uuid::v6();

                $inOffer = rand(2, 10) % 2;
                $offerStartDate = $inOffer ? new \DateTime() : null;
                $offerEndDate = $inOffer ? new \DateTime('today +'.rand(5, 20)) : null;
                $offerPrice = $inOffer ? $productDef['price'] - rand(5, 10) : null;

                /** @var Brand $brand */
                $brand = $brands[array_rand($brands)];

                for ($j = 0; $j < 3; $j++) {

                    $entity = new Product();

                    $manager->persist(
                        $entity
                            ->setParentId($parentId)
                            ->setShortDescription(
                                'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ornare, mi in ornare elementum, libero nibh
                lacinia urna, quis convallis lorem erat at purus. Maecenas eu varius nisi.'
                            )
                            ->setDescription(
                                '<p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas fermentum, diam non iaculis finibus,
                    ipsum arcu sollicitudin dolor, ut cursus sapien sem sed purus. Donec vitae fringilla tortor, sed
                    fermentum nunc. Suspendisse sodales turpis dolor, at rutrum dolor tristique id. Quisque pellentesque
                    ullamcorper felis, eget gravida mi elementum a. Maecenas consectetur volutpat ante, sit amet molestie
                    urna luctus in. Nulla eget dolor semper urna malesuada dictum. Duis eleifend pellentesque dui et
                    finibus. Pellentesque dapibus dignissim augue. Etiam odio est, sodales ac aliquam id, iaculis eget
                    lacus. Aenean porta, ante vitae suscipit pulvinar, purus dui interdum tellus, sed dapibus mi mauris
                    vitae tellus.
                </p>
                <h3>Etiam lacus lacus mollis in mattis</h3>
                <p>
                    Praesent mattis eget augue ac elementum. Maecenas vel ante ut enim mollis accumsan. Vestibulum vel
                    eros at mi suscipit feugiat. Sed tortor purus, vulputate et eros a, rhoncus laoreet orci. Proin sapien
                    neque, commodo at porta in, vehicula eu elit. Vestibulum ante ipsum primis in faucibus orci luctus et
                    ultrices posuere cubilia Curae; Curabitur porta vulputate augue, at sollicitudin nisl molestie eget.
                </p>
                <p>
                    Nunc sollicitudin, nunc id accumsan semper, libero nunc aliquet nulla, nec pretium ipsum risus ac
                    neque. Morbi eu facilisis purus. Quisque mi tortor, cursus in nulla ut, laoreet commodo quam.
                    Pellentesque et ornare sapien. In ac est tempus urna tincidunt finibus. Integer erat ipsum, tristique
                    ac lobortis sit amet, dapibus sit amet purus. Nam sed lorem nisi. Vestibulum ultrices tincidunt turpis,
                    sit amet fringilla odio scelerisque non.
                </p>'
                            )
                            ->setName($productDef['name'].'-'.$key.$j)
                            ->setBadges($productDef['badges'] ?? null)
                            ->setReviews($productDef['reviews'] + $j)
                            ->setRating($productDef['rating'])
                            ->setPrice($productDef['price'] + $j)
                            ->setSku(Uuid::v6())
                            ->setAvailability($productDef['availability'])
                            ->setStock(rand(2, 10))
                            ->setFeatured(rand(2, 10) % 2)
                            ->setOfferEndDate($offerEndDate)
                            ->setOfferStartDate($offerStartDate)
                            ->setOfferPrice($offerPrice)
                            ->setSales(rand(2, 100))
                            ->setBrandId($brand)
                    );

                    $pCategory = new ProductSubcategory($entity, $category);
                    $manager->persist($pCategory);

                    foreach ($productDef['images'] as $imageDef) {
                        $pImage = new ProductImages();
                        $manager->persist($pImage->setProductId($entity)->setImage($imageDef));
                    }

                    foreach ($this->getReviews() as $review) {
                        $r = new ProductReviews($entity, $customers[array_rand($customers)]);
                        $manager->persist($r->setMessage($review['text'])->setRating($review['rating']));
                    }

                    /** @var Specification $brand */
                    $spf = $attributes[array_rand($attributes)];

                    foreach ($this->getAttributes() as $attributeDef) {
                        if ($attributeDef['name'] == $spf->getName()) {
                            foreach ($attributeDef['values'] as $valueDef) {
                                $pSpecification = new ProductSpecification($entity, $spf);
                                $manager->persist(
                                    $pSpecification
                                        ->setCustomFieldsType($valueDef['customFieldsType'] ?? '')
                                        ->setCustomFieldsValue($valueDef['customFieldsValue'] ?? '')
                                        ->setValue(rand(2, 10))
                                );
                            }
                        }
                    }

                    $manager->flush();

                    $manager->flush();

                }
            }
        }

        return $this;

    }

    /**
     * @param ObjectManager $manager
     * @return AppFixtures
     */
    private function createUsers(ObjectManager $manager): AppFixtures
    {

        for ($i = 0; $i < 3; $i++) {
            $entity = new User();
            $entity
                ->setName($this->getNames())
                ->setEmail('user'.$i.'@mailinator.com')
                ->setImage('assets/images/avatars/avatar-3.jpg');

            $password = $this->encoder->hashPassword($entity, '1234567890');
            $entity->setPassword($password);

            $manager->persist($entity);
        }

        $manager->flush();

        return $this;
    }

    /**
     * @param ObjectManager $manager
     * @return AppFixtures
     */
    private function createCustomers(ObjectManager $manager): AppFixtures
    {

        for ($i = 0; $i < 3; $i++) {
            $entity = new Customer();
            $entity
                ->setName($this->getFullName())
                ->setEmail('customer'.$i.'@mailinator.com')
                ->setImage('assets/images/avatars/avatar-3.jpg')
                ->setBillingFirstName($this->getNames())
                ->setBillingLastName($this->getLastNames())
                ->setBillingAddress('Toltecas 166')
                ->setBillingCity('Ciudad de México')
                ->setBillingCompanyName('AMCO')
                ->setBillingCountry('Mexico')
                ->setBillingEmail('customer'.$i.'@mailinator.com')
                ->setBillingPhone('+525555555555')
                ->setBillingPostcode('01185')
                ->setBillingStreetAddress('Ferrocarril 13')
                ->setBillingState('Distrito Federal')
                ->setApiId(Uuid::v6());

            $password = $this->encoder->hashPassword($entity, '1234567890');
            $entity->setPassword($password);

            $manager->persist($entity);
        }

        $manager->flush();

        return $this;
    }
}
