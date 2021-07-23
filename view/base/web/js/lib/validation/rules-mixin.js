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
define([
    'jquery',
    'underscore',
    'Magento_Ui/js/lib/validation/utils',
    'moment',
    'tinycolor',
    'jquery/validate',
    'mage/translate'
], function ($, _, utils, moment, tinycolor) {
    'use strict';

    return function (validator) {
        var validators = {
            'validate-identifier': [
                function (value) {
                    return utils.isEmptyNoTrim(value) || /^[a-z0-9][a-z0-9_\/-]+(\.[a-z0-9_-]+)|(?:[\u0590-\u05FF]+|\w+)+?$/.test(value);
                },
                $.mage.__('Please enter a valid URL Key (Ex: "example-page", "example-page.html" or "anotherlevel/example-page").')//eslint-disable-line max-len
            ],
        };

        validators = _.mapObject(validators, function (data) {
            return {
                handler: data[0],
                message: data[1]
            };
        });

        return $.extend(validator, validators);
    };
});