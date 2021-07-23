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
namespace Lof\UnicodeUrl\Plugin\MagentoStore\App\Request;

class PathInfoProcessor
{
    /**
     *
     * Handle UTF-8 URL keys before app resolve the path info
     *
     * @param \Magento\Store\App\Request\PathInfoProcessor $subject
     * @param \Magento\Framework\App\RequestInterface $request
     * @param $pathInfo
     * @return array
     */
    public function beforeProcess(
        \Magento\Store\App\Request\PathInfoProcessor $subject,
        \Magento\Framework\App\RequestInterface $request,
        $pathInfo
    )
    {
        // handle UTF-8 url keys
        $pathInfo = urldecode($pathInfo);

        // return the modified parameters
        return array(
            $request,
            $pathInfo
        );
    }
}