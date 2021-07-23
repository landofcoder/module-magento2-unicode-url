<?php
/**
 * Landofcoder
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * https://landofcoder.com/license
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category   Landofcoder
 * @package    Lof_UnicodeUrl
 * @copyright  Copyright (c) 2021 Landofcoder (https://landofcoder.com/)
 * @license    https://landofcoder.com/LICENSE-1.0.html
 */
namespace Lof\UnicodeUrl\Test\Unit;

class TranslitUrlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Framework\Filter\TranslitUrl
     */
    protected $model;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->model = $objectManager->getObject('Lof\UnicodeUrl\Filter\AbstractTranslitUrl');
    }

    /**
     * @param string $testString
     * @param string $result
     * @dataProvider filterDataProvider
     */
    public function testFilter($testString, $result)
    {
        $this->assertEquals($result, $this->model->filter($testString));
    }

    /**
     * @return array
     */
    public function filterDataProvider()
    {
        return [
            ['test', 'test'],
            ['привет мир', 'привет-мир'],
            ['Weiß, Goldmann, Göbel, Weiss, Göthe, Goethe und Götz', 'weiß-goldmann-göbel-weiss-göthe-goethe-und-götz'],
            ['my-product-❤', 'my-product-❤'],
            ['החולצה המהממת שלנו!','החולצה-המהממת-שלנו']
        ];
    }
}
