<?php

namespace App\Tests\Entity;

use App\Entity\Wishlist;
use PHPUnit\Framework\TestCase;

class WishlistTest extends TestCase
{
    public function testWishlistCanSetAndGetAnAdvertId()
    {
        $wishlist = new Wishlist();
        $wishlist->setAdvertId(2);

        $this->assertEquals(2, $wishlist->getAdvertId());
    }

    public function testWishlistCanSetAndGetAnUserId()
    {
        $wishlist = new Wishlist();
        $wishlist->setUserId(2);

        $this->assertEquals(2, $wishlist->getUserId());
    }
    
}