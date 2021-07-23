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
namespace Lof\UnicodeUrl\Filter;

/**
 * Lof custom filter factory
 */
class Factory extends \Magento\Framework\Filter\AbstractFactory
{
    /**
     * Set of filters
     *
     * @var array
     */
    protected $invokableClasses = [
        'translitUrlCategory' => 'Lof\UnicodeUrl\Filter\TranslitUrlCategory',
        'translitUrlProduct' => 'Lof\UnicodeUrl\Filter\TranslitUrlProduct',
    ];
}
