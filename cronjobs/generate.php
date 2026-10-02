<?php
/**
 * File containing the generate.php cronjob
 *
 * @copyright Copyright (C) 1999 - 2014 Brookins Consulting. All rights reserved.
 * @copyright Copyright (C) 2008 all2e GmbH. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package bcgooglesitemaps
 */

/**
 * File contains an eZ Publish cronjob part (script) to automatically
 * fetch all the content of the eZ Publish siteaccess database content
 * tree content nodes, transform the nodes fetched into an xml based
 * sitemap and writes the sitemap to disk.
 *
 * Sitemap is based on custom extension settings (array of siteaccess name strings),
 * this script iterate over each siteaccess building an array of site languages
 * (site locale and site url), then iterating over site language information fetch
 * the root node of the content tree (settings based) in each language and then all
 * child nodes in each language. Next iterating over an array of all nodes in all
 * locales, for each node, generate the sitemap xml representing that node.
 *
 * Finally a valid xml sitemap file is written out to disk (settings based var/ dir root by default)
 *
 */


/**
 * Test to replace php8 only function in php7
 */

// The code is in extension/bcgooglesitemaps/classes/runnable/cronjobs/generate.php (#207); this file is the entry point.
return \Exponential\Cronjob\Extension\Bcgooglesitemaps\Generate::main( __FILE__, get_defined_vars() );
