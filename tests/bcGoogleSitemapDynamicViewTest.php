<?php
/**
 * The dynamic sitemap view sends the XML itself and ends the request, so the kernel's debug output
 * (DebugOutput=enabled) is never put after </urlset>. Reads the view's code only, without a kernel or a database:
 *
 *   php vendor/bin/phpunit extension/bcgooglesitemaps/tests/bcGoogleSitemapDynamicViewTest.php
 *
 *  SV-01 - The view ends with eZExecution::cleanExit() and returns no module result for a pagelayout
 *  SV-02 - No try/catch around it (under the Velocity engine cleanExit() ends the request by throwing)
 *  SV-03 - The content type is application/xml in UTF-8
 *  SV-04 - The pagelayout template still wraps the entries in <urlset>
 *
 * @copyright Copyright (C) 1998 - 2026 7x & Exponential Foundation. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @package tests
 */

use PHPUnit\Framework\TestCase;

class bcGoogleSitemapDynamicViewTest extends TestCase
{
    private static function code(): string
    {
        $source = (string)file_get_contents( dirname( __DIR__ ) . '/classes/runnable/views/googlesitemapdynamic/sitemap.php' );
        // comments are not code
        return (string)preg_replace( array( '#/\*.*?\*/#s', '#//[^\n]*#' ), '', $source );
    }

    /** SV-01 */
    public function testEndsTheRequestItself()
    {
        $code = self::code();
        $this->assertStringContainsString( '\eZExecution::cleanExit();', $code );
        $this->assertStringNotContainsString( "'pagelayout'", $code );
        $this->assertStringNotContainsString( 'viewResult', $code );
        // the engine collects the response in its own output buffer: closing them hangs the request under Velocity
        $this->assertStringNotContainsString( 'ob_end_clean', $code );
        $this->assertLessThan( strpos( $code, 'cleanExit' ), strpos( $code, 'echo $xml' ) );
    }

    /** SV-02 */
    public function testNoTryCatch()
    {
        $code = self::code();
        $this->assertDoesNotMatchRegularExpression( '/\btry\s*\{/', $code );
        $this->assertDoesNotMatchRegularExpression( '/\bcatch\s*\(/', $code );
    }

    /** SV-03 */
    public function testContentType()
    {
        $this->assertStringContainsString( "const CONTENT_TYPE = 'application/xml; charset=utf-8';", self::code() );
        $this->assertStringContainsString( "header( 'Content-Type: ' . self::CONTENT_TYPE );", self::code() );
    }

    /** SV-04 */
    public function testPagelayoutWrapsTheEntries()
    {
        $layout = (string)file_get_contents( dirname( __DIR__ ) . '/design/standard/templates/googlesitemapdynamic_pagelayout.tpl' );
        $this->assertMatchesRegularExpression( '/^<\?xml version="1\.0" encoding="UTF-8"\?>\s*<urlset [^>]*>\s*\{\$module_result\.content\}\s*<\/urlset>\s*$/', $layout );
    }
}
