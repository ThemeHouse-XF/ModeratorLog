<?php

class ThemeHouse_ModeratorLog_Listener_LoadClass extends ThemeHouse_Listener_LoadClass
{

    protected function _getExtendedClasses()
    {
        return array(
            'ThemeHouse_ModeratorLog' => array(
                'model' => array(
                    'XenForo_Model_Log'
                ), /* END 'model' */
            ), /* END 'ThemeHouse_ModeratorLog' */
        );
    } /* END _getExtendedClasses */

    public static function loadClassModel($class, array &$extend)
    {
        $extend = self::createAndRun('ThemeHouse_ModeratorLog_Listener_LoadClass', $class, $extend, 'model');
    } /* END loadClassModel */
}