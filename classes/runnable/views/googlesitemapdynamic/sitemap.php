<?php
/**
 * The code of extension/bcgooglesitemaps/modules/googlesitemapdynamic/sitemap.php, moved into a class (#207 stage 1). The file extension/bcgooglesitemaps/modules/googlesitemapdynamic/sitemap.php is one call to it.
 * Guide: doc/bc/6.0/cli_cronjob_view_abstractions.md
 */
/*
 * The original header of extension/bcgooglesitemaps/modules/googlesitemapdynamic/sitemap.php:
 *
 *
 * File containing the bcgooglesitemaps siteaccess sitemap generator cronjob part
 *
 * @copyright Copyright (C) 1999 - 2014 Brookins Consulting. All rights reserved.
 * @copyright Copyright (C) 2008 MEDIATA Communications GmbH. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package bcgooglesitemaps
 *
 */

namespace Exponential\View\Extension\Bcgooglesitemaps\Googlesitemapdynamic
{

class Sitemap extends \Exponential\Runnable\ModuleView
{
    /**
     * The content type of the sitemap response
     */
    const CONTENT_TYPE = 'application/xml; charset=utf-8';

    public function run( array $scope )
    {
        // the including function's variables ($Params, $Module, $cli, ...)
        foreach ( array_keys( $scope ) as $__name )
            if ( $__name !== 'this' && $__name !== 'scope' )
                ${$__name} = &$scope[$__name];
        unset( $__name );

        $Module = $Params['Module'];

        $NodeID = isset( $Params['NodeID'] ) ? (int)$Params['NodeID'] : 2;
        if ( $NodeID < 1 )
            $NodeID = 2;

        $tpl = \eZTemplate::factory();
        $tpl->setVariable( 'start_node_id', $NodeID );

        // The sitemap is the whole response. It is not handed to the kernel as a module result: the kernel would
        // put its debug output (DebugOutput=enabled) after </urlset>, and the XML would not be valid any more. The
        // pagelayout template still wraps the entries, so an override of it keeps working.
        $tpl->setVariable( 'module_result', array( 'content' => $tpl->fetch( 'design:googlesitemapdynamic/sitemap.tpl' ) ) );
        $xml = trim( $tpl->fetch( 'design:googlesitemapdynamic_pagelayout.tpl' ) ) . "\n";

        // Sent after everything that may fail. Under the Velocity engine cleanExit() ends the request by throwing,
        // so this part must never sit inside a try/catch( Exception ). Whatever was printed before (a blank line
        // after a closing PHP tag somewhere) is dropped from the current output buffer, as the kernel drops it
        // before a pagelayout: the XML declaration must be the first byte. The buffers themselves stay open: the
        // engine collects the response in its own, and closing it hangs the request.
        if ( ob_get_level() > 0 && ob_get_length() )
            ob_clean();
        header( 'Content-Type: ' . self::CONTENT_TYPE );
        echo $xml;
        \eZExecution::cleanExit();
    }
}

}
