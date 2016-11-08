<?php

class sspmod_authTiqr_Helper_VersionHelper {

    public static function useOldVersion() {
        $config = SimpleSAML_Configuration::getInstance();
        $version = $config->getVersion();
        if ($version == 'master') {
            return false;
        }
        list($majorVersion, $minorVersion, $revisionVersion) = explode('.', $version);


        if ((int)$majorVersion === 1 && (int)$minorVersion < 14) {
            return true;
        }
        return false;
    }
}
