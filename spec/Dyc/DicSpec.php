<?php

namespace spec\Dyc;

use Dyc\Dic;
use Dyc\Exception\ServiceNotFoundException;
use Fixtures\Bar\Github\Client;
use Fixtures\Foo\Currency\RateConverter;
use Fixtures\Foo\Currency\RateConverter\Fixer;
use Fixtures\Bar\Http\Controller as BarController;
use Fixtures\Foo\Http\Controller as FooController;
use Fixtures\Foo\Repository\ProductRepository;
use HaydenPierce\ClassFinder\ClassFinder;
use PhpSpec\ObjectBehavior;

class DicSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Dic::class);
    }

    function it_loads_manually_services()
    {
        $this->set(RateConverter::class, function(Dic $dic) {
            return new Fixer();
        });
        $this->set(ProductRepository::class, function(Dic $dic) {
            return new ProductRepository($dic->get(RateConverter::class));
        });
        $this->set(FooController::class, function(Dic $dic) {
            return new FooController($dic->get(ProductRepository::class));
        });
        /** @var FooController $controller */
        $controller = $this->get(FooController::class);
        $controller->getPriceAction(132)->shouldBe(2644);
    }

    function it_can_define_dependencies_in_random_order()
    {
        $this->set(FooController::class, function(Dic $dic) {
            return new FooController($dic->get(ProductRepository::class));
        });
        $this->set(ProductRepository::class, function(Dic $dic) {
            return new ProductRepository($dic->get(RateConverter::class));
        });
        $this->set(RateConverter::class, function(Dic $dic) {
            return new Fixer();
        });
        /** @var FooController $controller */
        $controller = $this->get(FooController::class);
        $controller->getPriceAction(132)->shouldBe(2644);
    }

    function it_can_autowire_if_interface_dependencies_are_defined()
    {
        $classes = ClassFinder::getClassesInNamespace('Fixtures\Foo', ClassFinder::RECURSIVE_MODE);
        $this->autowire($classes);
        $this->set(RateConverter::class, function(Dic $dic) {
            return new Fixer();
        });
        /** @var FooController $controller */
        $controller = $this->get(FooController::class);
        $controller->getPriceAction(132)->shouldBe(2644);
    }

    function it_can_autowire_if_scalar_dependencies_are_defined()
    {
        $classes = ClassFinder::getClassesInNamespace('Fixtures\Bar', ClassFinder::RECURSIVE_MODE);
        $this->autowire($classes);
        $this->set(Client::class, function(Dic $dic) {
            return new Client('some api key');
        });
        /** @var BarController $controller */
        $controller = $this->get(BarController::class);
        $controller->getStatsActon('package name')->shouldBe(124);
    }

    function it_throws_an_exception_if_the_interface_is_not_defined()
    {
        $classes = ClassFinder::getClassesInNamespace('Fixtures\Foo', ClassFinder::RECURSIVE_MODE);
        $this->autowire($classes);
        $this->shouldThrow(ServiceNotFoundException::class)->during('get', [FooController::class]);
    }

    function it_throws_an_exception_if_a_scalar_is_not_defined()
    {
        $classes = ClassFinder::getClassesInNamespace('Fixtures\Bar', ClassFinder::RECURSIVE_MODE);
        $this->autowire($classes);
        $this->shouldThrow(ServiceNotFoundException::class)->during('get', [BarController::class]);
    }
}
