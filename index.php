<?php
require_once __DIR__ . '/vendor/autoload.php';

Tracy\Debugger::enable();

use App\Resolver;
use App\ShippingRestriction;
use DVDoug\BoxPacker\Test\TestItem;

// always use grams and milimeters
$restrictionForCzechPost = new ShippingRestriction('Ceska Posta', 50000, 1500, 5);
$restrictionForGls = new ShippingRestriction('GLS', 30000, 500, 2);
$restrictionForPilulkaAuto = new ShippingRestriction('Pilulka Auto', 5000, 300, 1);
$restrictionForPpl = new ShippingRestriction('PPL', null, null, null);

$resolver = new Resolver();
$resolver->addShippingRestriction($restrictionForCzechPost);
$resolver->addShippingRestriction($restrictionForGls);
$resolver->addShippingRestriction($restrictionForPilulkaAuto);
$resolver->addShippingRestriction($restrictionForPpl);

$item1 = new TestItem('Item 1', 596, 590, 520, 200, false);
$item2 = new TestItem('Item 2', 566, 590, 1020, 500, false);
$item3 = new TestItem('Item 3', 596, 590, 1040, 5900, false);

$resolver->addItem($item1);
$resolver->addItem($item2);
$resolver->addItem($item3);

$result = $resolver->resolve();

dump($result);
