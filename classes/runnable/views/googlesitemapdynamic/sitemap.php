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
    public function run( array $scope )
    {
        // the including function's variables ($Params, $Module, $cli, ...)
        foreach ( array_keys( $scope ) as $__name )
            if ( $__name !== 'this' && $__name !== 'scope' )
                ${$__name} = &$scope[$__name];
        unset( $__name );

        $Module = $Params['Module'];

        if ( isset( $Params["NodeID"] ) ) {
            $NodeID = $Params["NodeID"];
        }
        else {
            $NodeID = 2;
        }

        $tpl = \eZTemplate::factory();
        $tpl->setVariable( "start_node_id", $NodeID );

        header( 'Content-Type: text/xml' );

        $Result = array();
        $Result['content'] = $tpl->fetch( "design:googlesitemapdynamic/sitemap.tpl" );
        $Result['pagelayout'] = 'googlesitemapdynamic_pagelayout.tpl';

        return $this->viewResult( isset( $Result ) ? $Result : null, null );
    }
}

}
