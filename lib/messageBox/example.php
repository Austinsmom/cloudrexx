<?php

/**
<<<<<<< HEAD
 * Contrexx
 *
 * @link      http://www.contrexx.com
 * @copyright Comvation AG 2007-2014
 * @version   Contrexx 4.0
 * 
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Contrexx" is a registered trademark of Comvation AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

/**
=======
>>>>>>> f7ee35166c3ea0314d3113cfac8fc8894c4d0211
 * messageBox Example
 *
 * @copyright   CONTREXX CMS - COMVATION AG
 * @author      COMVATION Development Team <info@comvation.com>
 * @package     contrexx
 * @subpackage  lib_framework
 */

/**
 * @ignore
 */
require_once("StatusMessage.class.php") ;

# Create new status message Object
$msg = new StatusMessage();

# Set Box
# $message string
# $mode 'error','warning','confirmation'
$msg->setBox($message="Achtung: Schweinegefahr", $mode="error");

# Generate box
$htmlBox = $msg->generateBox();
?>

<html>
<body bgcolor='white'>
<h2>StatusMessage Example</h2>
<? echo $htmlBox ?>
</body>
</html>
