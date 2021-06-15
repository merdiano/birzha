<?php namespace TPS\Birzha\Models;

use October\Rain\Database\Model;

class Settings extends Model
{
    const SETTINGS_CODE = 'tps_birzha_settings';
    public static $arCacheValue = [];
    public $implement = [
        'System.Behaviors.SettingsModel',
        '@RainLab.Translate.Behaviors.TranslatableModel',
    ];

    public $settingsFields = 'fields.yaml';

    public $settingsCode = 'birzha_settings';

    public $translatable = ['address','site_name'];

    /**
     * Get setting value
     * @param string $sCode
     * @param string $sDefaultValue
     * @return null|string
     */
    public static function getValue($sCode, $sDefaultValue = null)
    {
        if (empty($sCode)) {
            return $sDefaultValue;
        }

        if (isset(static::$arCacheValue[$sCode])) {
            return static::$arCacheValue[$sCode];
        }

        //Get settings object
        $obSettings = static::where('item', static::SETTINGS_CODE)->first();
        if (empty($obSettings)) {
            static::$arCacheValue[$sCode] = static::instance()->getAttributeTranslated($sCode);

            return static::$arCacheValue[$sCode];
        }

        $sValue = $obSettings->$sCode;
        if ($sValue === null) {
            return $sDefaultValue;
        }

        static::$arCacheValue[$sCode] = $sValue;

        return $sValue;
    }
}
