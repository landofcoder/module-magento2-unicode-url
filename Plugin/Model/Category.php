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
namespace Lof\UnicodeUrl\Plugin\Model;

class Category
{
    public function __construct(
        \Magento\Framework\Filter\FilterManager $filter
    ) {
        $this->filter = $filter;
    }

    public function aroundFormatUrlKey(
        \Magento\Catalog\Model\Category $subject,
        callable $proceed,
        $str
    ) {
        return $this->filter->translitUrlCategory($str);
    }
}
