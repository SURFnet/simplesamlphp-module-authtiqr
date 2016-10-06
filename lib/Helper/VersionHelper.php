<?php

class sspmod_authTiqr_Helper_VersionHelper {

    public static function useOldVersion() {
        $config = SimpleSAML_Configuration::getInstance();
        list($majorVersion, $minorVersion, $revisionVersion) = explode('.', $config->getVersion());

        if ($majorVersion == 'master') {
            return false;
        }

        if ((int)$majorVersion === 1 && (int)$minorVersion < 14) {
            return true;
        }
        return false;
    }
}