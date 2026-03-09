<?php

namespace App\Service;

use App\Entity\Catagories;
use Doctrine\ORM\EntityManagerInterface;

class CategoryDefaults
{
    /**
     * Ensure that the set of default categories (user = null) exists once.
     * Returns number of categories created.
     */
    public function ensureDefaultCategories(EntityManagerInterface $em): int
    {
        $defaults = [
            ['name' => 'Vaste Lasten',     'icon' => 'bi bi-house-heart-fill'],
            ['name' => 'Benodigdheden',    'icon' => 'bi bi-bag-heart-fill'],
            ['name' => 'Vervoer',          'icon' => 'bi bi-bus-front-fill'],
            ['name' => 'Vrije Tijd',       'icon' => 'bi bi-sun'],
            ['name' => 'Andere',           'icon' => 'bi bi-three-dots'],
        ];

        $created = 0;
        foreach ($defaults as $def) {
            $existing = $em->getRepository(Catagories::class)->findOneBy([
                'name' => $def['name'],
                'user' => null,
            ]);
            if (!$existing) {
                $cat = new Catagories();
                $cat->setName($def['name']);
                $cat->setIcon($def['icon']);
                $cat->setUser(null);
                $em->persist($cat);
                $created++;
            }
        }

        if ($created > 0) {
            $em->flush();
        }

        return $created;
    }
}

