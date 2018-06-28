<?php
/**
 * IMP Hooks configuration file.
 *
 * For more information please see the hooks.php.dist file.
 */

class IMP_Hooks
{
    /**
     * PREFERENCE INIT: Set preference values on login.
     *
     * See horde/config/hooks.php.dist for more information.
     */
    public function prefs_init($pref, $value, $username, $scope_ob)
    {
        switch ($pref) {
        case 'add_source':
            // Dynamically set the add_source preference.
            try {
                $add_source = $GLOBALS['registry']->call('contacts/getDefaultShare');
            }
            catch (Horde_Exception $e) {
                $add_source = $value;
            }
            return is_null($username)
                ? $value
                : $add_source;


        case 'search_fields':
        case 'search_sources':
            // Dynamically set the search_fields/search_sources preferences.
            if (!is_null($username)) {
                try {
                    $sources = $GLOBALS['registry']->call('contacts/sources');
                }
                catch (Horde_Exception $e) {
                    $sources = array();
                }

                if ($pref == 'search_fields') {
                    $out = array();
                    foreach (array_keys($sources) as $source) {
                        $out[$source] = array_keys($GLOBALS['registry']->call('contacts/fields', array($source)));
                    }
                } else {
                    $out = array_keys($sources);
                }

                return json_encode($out);
            }

            return $value;
        }
    }
}
