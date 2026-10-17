<?php
/**
 * The dynamic sitemap builds its addresses with the installation's own URL builder, so they carry the scheme, host,
 * port and siteaccess path the site is served on. Reads the template only, without a kernel or a database:
 *
 *   php vendor/bin/phpunit extension/bcgooglesitemaps/tests/bcGoogleSitemapDynamicTemplateTest.php
 *
 *  SM-01 - Every <loc> is {$node.url_alias|ezurl( 'no', 'full' )}
 *  SM-02 - No address is put together from 'http://', HTTP_HOST or REQUEST_URI
 *  SM-03 - The site URL handed on to the child views is '/'|ezurl( 'no', 'full' )
 *
 * @copyright Copyright (C) 1998 - 2026 7x & Exponential Foundation. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @package tests
 */

use PHPUnit\Framework\TestCase;

class bcGoogleSitemapDynamicTemplateTest extends TestCase
{
    private static function source(): string
    {
        $source = (string)file_get_contents( dirname( __DIR__ ) . '/design/standard/override/templates/googlesitemapdynamic/sitemap.tpl' );
        // template comments are not output
        return (string)preg_replace( '/\{\*.*?\*\}/s', '', $source );
    }

    /** SM-01 */
    public function testEveryLocIsAFullUrl()
    {
        preg_match_all( '/<loc>(.*?)<\/loc>/s', self::source(), $matches );
        $this->assertSame( array( "{\$node.url_alias|ezurl( 'no', 'full' )}" ), $matches[1] );
    }

    /** SM-02 */
    public function testNoHandBuiltAddress()
    {
        $source = self::source();
        $this->assertStringNotContainsString( "'http://'", $source );
        $this->assertStringNotContainsString( 'HTTP_HOST', $source );
        $this->assertStringNotContainsString( 'REQUEST_URI', $source );
    }

    /** SM-03 */
    public function testSiteUrlIsBuiltByTheInstallation()
    {
        $this->assertStringContainsString( "{def \$sitemap_siteurl='/'|ezurl( 'no', 'full' )}", self::source() );
    }
}
